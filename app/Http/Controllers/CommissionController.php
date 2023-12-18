<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commission;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commissions = Commission::all();
        return view('admin.commissions.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('admin.commissions.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $commission = Client::find($request->client)->commissions()->create(['deadline' => $request->deadline, 'commission_description' => $request->commission_description]);
        return redirect(route('commission.edit', compact('commission')))->with('message', 'Ordine creato con successo. Aggiungi prodotti o apporta modifiche.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function show(Commission $commission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function edit(Commission $commission)
    {
        return view('admin.commissions.edit', compact('commission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commission $commission)
    {
        Validator::make($request->all(), [
            'deadline' => 'required|date|after_or_equal:today',
        ], ['after_or_equal' => 'La data di consegna dev\'essere successiva o uguale ad oggi.'])->validate();
        $commission->update(['deadline' => $request->deadline, 'commission_description' => $request->commission_description]);
        return redirect()->back()->with('message', 'Ordine modificato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client, Commission $commission)
    {
        // Prendiamo tutto ciò che é collegato alla commessa e lo eliminiamo prima di eliminarla.
        // $commission->orders()->delete();
        // $commission->distributions()->each(fn ($distribution) => $distribution->tasks()->delete());
        // $commission->movements()->delete();
        // $commission->products()->each(fn ($product) => $product->stones()->delete());
        // $commission->products()->delete();


        // $commission->delete();
        // //? Aggiorno la contabilità dopo aver eliminato una commessa.
        // $client->updateAccountability();
        // Operator::all()->each(fn ($operator) => $operator->updateAccountability());

        return redirect()->back()->with('message', 'Ordine eliminato con successo.');
    }

    /**
     * Download a PDF file of the commission details.
     *
     * @param Commission $commission The commission instance to download the PDF for.
     * @return \Illuminate\Http\Response The response containing the PDF file.
     */
    public function downloadCommissionPdf(Commission $commission)
    {
        $commission->load('products.stones.stoneClass', 'products.stones.stoneType', 'products.stones.settingType', 'orders', 'movements');
        $pdf = PDF::loadView('admin.commissions.pdf-commission', ['commission' => $commission]);
        $pdf->setTimeout(300);
        session()->flash('message', 'Pdf scaricato correttamente.');
        return $pdf->download("dettaglio-commessa-$commission->code.pdf");
    }

    /**
     * Deliver the given commission.
     *
     * @param  Commission  $commission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deliverCommission(Commission $commission)
    {
        $commission->deliver();
        return redirect()->back()->with('message', 'Commessa consegnata con successo.');
    }
}
