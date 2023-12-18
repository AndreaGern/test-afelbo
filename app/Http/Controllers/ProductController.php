<?php

namespace App\Http\Controllers;

use App\Models\Process;
use App\Models\Product;
use App\Models\SettingType;
use App\Models\Stone;
use App\Models\StoneClass;
use App\Models\StoneType;
use App\Traits\ProductTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ProductTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('archive', true)->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $stoneClasses = StoneClass::all();
        $settingTypes = SettingType::all();
        $stoneTypes = StoneType::all();
        $stones = Stone::all();
        return view('admin.products.create', compact('products', 'stoneClasses', 'settingTypes', 'stoneTypes', 'stones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'description'=>'required',
            'type'=>'required',
            'gold_weight'=>'required',
            'stones' => ['required'],
        ], [
            'stones.required' => 'Attenzione! É richiesta almeno una lavorazione.',
        ])->validate();

        $this->storeProduct($request, true);
        return redirect(route('product.index'))->with('message', 'Prodotto aggiunto con successo.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function delete(Product $product)
    {
        // TODO: Se il prodotto é dello schedario -> eliminare il processo corrispondente e la pietra corrispondente che non influenzano commesse o altro e restano vuote nel db
        // PSEUDOCODE:
        // if($product->doesntHave('commissions')){
        //     $product->stones()->delete();
        // }

        $product->delete();

        return redirect(route('product.index'));
    }
}
