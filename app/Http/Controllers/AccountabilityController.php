<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Operator;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountabilityController extends Controller
{
    public function operators()
    {
        $operators = Operator::all();
        return view('admin.operators.accountability.accountability-index', compact('operators'));
    }

    public function operator(Operator $operator)
    {
        return view('admin.operators.accountability.accountability-show', compact('operator'));
    }

    public function clients()
    {
        $clients = Client::all();
        return view('admin.clients.accountability.accountability-index', compact('clients'));
    }

    public function client(Client $client)
    {
        return view('admin.clients.accountability.accountability-show', compact('client'));
    }

    /// Summary
    /// Metodo lato admin per aggiungere un pagamento ad un operatore
    /// Parametri: Request, Operator
    /// Return: redirect back con messaggio di successo
    public function newMovementOperator(Request $request, Operator $operator)
    {
        Validator::make($request->all(), [
            'causal' => ['required', 'string'],
            'amount' => 'required',
        ])->validate();

        $operator->movements()->create([
            'causal' => $request->causal,
            'amount' => $request->amount,
        ]);

        // Creo il record di contabilitå per l'operatore o se c'é giá lo aggiorno
        $operator->updateAccountability();

        return redirect()->back()->with('message', 'Pagamento inserito con successo.');
    }

    /// Summary
    /// Metodo lato admin per aggiungere un pagamento ad un cliente
    /// Parametri: Request, Client
    /// Return: redirect back con messaggio di successo
    public function newMovementClient(Request $request, Client $client)
    {
        Validator::make($request->all(), [
            'causal' => ['required', 'string'],
            'amount' => 'required',
            'commission' => 'required|exists:App\Models\Commission,id',
        ])->validate();

        $client->movements()->create([
            'causal' => $request->causal,
            'amount' => $request->amount,
            'commission_id' => $request->commission,
        ]);

        // Creo il record di contabilitå per l'cliente o se c'é giá lo aggiorno
        $client->updateAccountability();

        return redirect()->back()->with('message', 'Pagamento inserito con successo.');
    }

    /// Summary
    /// Metodo lato admin per eliminare un pagamento ad un cliente
    /// Parametri: Client, Movement
    /// Return: redirect back con messaggio di successo
    public function deleteMovementClient(Client $client, Movement $movement)
    {
        $movement->delete();
        $client->updateAccountability();
        return redirect()->back()->with('message', 'Pagamento eliminato con successo.');
    }

    /// Summary
    /// Metodo lato admin per eliminare un pagamento ad un operatore
    /// Parametri: Operator, Movement
    /// Return: redirect back con messaggio di successo
    public function deleteMovementOperator(Operator $operator, Movement $movement)
    {
        $movement->delete();
        $operator->updateAccountability();
        return redirect()->back()->with('message', 'Pagamento eliminato con successo.');
    }

    /// Summary
    /// Metodo lato admin per resettare i contatori di un operatore
    /// Parametri: Operator
    /// Return: redirect back con messaggio di successo
    public function resetCountersOperator(Operator $operator)
    {
        $operator->accountability->update([
            'payments_sum' => 0, // pagamenti
            'piecework' => 0, // cottimo
            'unpaid' => 0, // da pagare
        ]);

        $operator->movements()->create([
            'causal' => 'Azzerato contatori',
            'amount' => 0,
        ]);

        return redirect()->back()->with('message', 'Contatori resettati con successo.');
    }

    /// Summary
    /// Metodo lato admin per resettare i contatori di un cliente
    /// Parametri: Client
    /// Return: redirect back con messaggio di successo
    public function resetCountersClient(Client $client)
    {
        $client->accountability->update([
            'revenue' => 0,
            'total_due' => 0,
            'unpaid' => 0,
        ]);

        $client->movements()->create([
            'causal' => 'Azzerato contatori',
            'amount' => 0,
        ]);

        return redirect()->back()->with('message', 'Contatori resettati con successo.');
    }
}
