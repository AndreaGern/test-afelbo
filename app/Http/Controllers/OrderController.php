<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Operator;
use App\Models\Order;
use App\Models\Process;
use App\Models\Product;
use App\Models\SettingType;
use App\Models\Stone;
use App\Models\StoneClass;
use App\Models\StoneType;
use App\Traits\ProductTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ProductTrait;

    /**
     * Create a new order for a given product quantity, commission and product.
     *
     * @param int $product_quantity_order The quantity of the product to order.
     * @param Commission $commission The commission for which the order is created.
     * @param Product $product The product to order.
     * @return bool Returns true if the order was successfully created.
     */
    private function createOrder($product_quantity_order, Commission $commission, Product $product)
    {
        //? Tengo traccia del numero di ordini creati per commessa
        $commission->update(['orderCount' => $commission->orderCount + 1]);

        //? Creo l'ordine
        //* Codice ordine = Codice commessa + orderCount Commessa
        $orderCode = $commission->getCommissionCode() . "/$commission->orderCount";
        $importoUnitario = $product->stones()->get()->sum('client_cost');
        $importoTotale = $importoUnitario * $product_quantity_order;
        $commission->products()->attach($product, ['code' => $orderCode, 'quantity' => $product_quantity_order, 'quantityToDistribute' => $product_quantity_order, 'importo_unitario' => $importoUnitario, 'importo_totale' => $importoTotale]);

        //? Aggiorno il campo importo totale della commessa:
        $importoTotaleCommessa = $commission->orders->sum->importo_totale;
        $commission->update(['importo_totale' => $importoTotaleCommessa]);

        //? Aggiorno la contabilità del cliente
        $commission->client->updateAccountability();

        return true;
    }

    /**
     * Update the deliveredProducts field of an Order with the quantity of delivered products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commission  $commission
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deliverProducts(Request $request, Commission $commission, Order $order)
    {
        //? Check if the delivered products are less than or equal to the distributed products per the order ( $order->getDistributedProductsQuantity() )
        //? Check also that the delivered products are equal or more than the already delivered products quantity for the order
        Validator::make(
            $request->all(),
            [
                'deliveredProducts' => 'required|numeric|min:' . $order->getDeliveredProductsQuantity() . '|max:' . $order->getDistributedProductsQuantity(),
            ],
            [
                'deliveredProducts.required' => 'Il campo quantità consegnata è obbligatorio',
                'deliveredProducts.numeric' => 'Il campo quantità consegnata deve essere un numero',
                'deliveredProducts.min' => 'Il campo quantità consegnata deve essere maggiore o uguale a 0',
                'deliveredProducts.max' => 'Il campo quantità consegnata deve essere minore o uguale a ' . $order->getDistributedProductsQuantity(),
            ]
        )->validate();

        $order->deliver($commission, $request->deliveredProducts);

        return redirect()->back()->with('message', 'Quantità consegnata aggiornata con successo.');
    }

    public function selectDbProduct(Commission $commission)
    {
        // Retrieve all products that are archived
        $products = Product::where('archive', true)->get()
            // Load the sum of the client cost for each product's stones
            ->loadSum('stones', 'client_cost')
            // Load the sum of the prezzario user cost for each product's stones
            ->loadSum('stones', 'prezzariouser');

        return view('admin.orders.select-db-product', compact('products', 'commission'));
    }

    public function createFromDb(Commission $commission, Product $product)
    {
        $products = Product::all();
        $processes = $product->stones;
        $stoneClasses = StoneClass::all();
        $settingTypes = SettingType::all();
        $stoneTypes = StoneType::all();
        $stones = Stone::all();
        $createFromDb = true;

        return view('admin.orders.create-from-db', compact('commission', 'product', 'products', 'stoneClasses', 'settingTypes', 'stoneTypes', 'stones', 'processes'));
    }

    //* TODO: Combine storeFromDb and Store making the product parameter optional
    public function storeFromDb(Request $request, Commission $commission, Product $product)
    {
        // $this->storeProduct($request, true); -> Commentato perché quando si crea un prodotto per la commessa a partire dal DB non si aggiunge allo schedario.
        $product = $this->storeProduct($request, false, $product);

        //? Associo il prodotto alla commessa creando quindi un ordine
        $this->createOrder($request->product_quantity_order, $commission, $product);

        return redirect(route('commission.edit', compact('commission')))->with('message', 'Prodotto aggiunto con successo.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Commission $commission)
    {
        $products = Product::all();
        $stoneClasses = StoneClass::all();
        $settingTypes = settingType::all();
        $stoneTypes = StoneType::all();

        $stones = Stone::all();
        return view('admin.orders.create', compact('products', 'stoneClasses', 'settingTypes', 'stoneTypes', 'stones', 'commission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Commission $commission)
    {
        Validator::make($request->all(), [
            'description' => 'required',
            'type' => 'required',
            'gold_weight' => 'required',
            'product_quantity_order' => 'required',
            'stones' => ['required'],
        ], [
            'required' => 'Attenzione! É richiesta almeno una lavorazione.',
        ])->validate();

        $this->storeProduct($request, true);
        $product = $this->storeProduct($request);

        //? Associo il prodotto alla commessa creando quindi un ordine
        $this->createOrder($request->product_quantity_order, $commission, $product);

        //? Update Commission stato lavorazione 
        $commission->updateStatus();

        return redirect(route('commission.edit', compact('commission')))->with('message', 'Prodotto aggiunto con successo.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Commission $commission, Order $order)
    {
        //? SE IL PRODOTTO É GIÀ STATO RIPARTITO NON SI MODIFICA NIENTE
        //? SE SI VUOLE MODIFICARE SI ELIMINANO LE RIPARTIZIONI PRIMA E POI SI FA
        if ($order->completed) {
            return redirect()->back()->withErrors('Attenzione! Quest\'ordine è completato e non può essere modificato.');
        }
        $product = $order->product;
        $processes = $product->processes;
        $stoneClasses = StoneClass::all();
        $settingTypes = SettingType::all();
        $stoneTypes = StoneType::all();
        $stones = Stone::all();
        return view('admin.orders.edit', compact('commission', 'order', 'product', 'processes', 'stoneClasses', 'settingTypes', 'stoneTypes', 'stones'));
    }

    /**
     * Display the specified order.
     *
     * @param  Commission  $commission
     * @param  Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Commission $commission, Order $order)
    {
        $product = $order->product;
        $processes = $product->processes;
        $stoneClasses = StoneClass::all();
        $settingTypes = SettingType::all();
        $stoneTypes = StoneType::all();
        $stones = Stone::all();
        return view('admin.orders.show', compact('commission', 'order', 'product', 'processes', 'stoneClasses', 'settingTypes', 'stoneTypes', 'stones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commission $commission, Order $order)
    {
        // Valido che il campo quantity sia superiore a $order->getDistributedProductsQuantity()
        Validator::make(
            $request->all(),
            [
                'product_quantity_order' => 'required|numeric|min:' . $order->getDistributedProductsQuantity(),
            ],
            [
                'product_quantity_order.required' => 'Il campo quantità è obbligatorio',
                'product_quantity_order.numeric' => 'Il campo quantità deve essere un numero',
                'product_quantity_order.min' => 'Il campo quantità deve essere maggiore o uguale a ' . $order->getDistributedProductsQuantity(),
            ]
        )->validate();

        //? Aggiorno il prodotto
        $product = $this->updateProduct($request, false, $order->product);


        $importoUnitario = $product->stones()->get()->sum('client_cost');

        $product_quantity_order = $request->product_quantity_order;

        $importoTotale = $importoUnitario * $product_quantity_order;

        $order->update(['quantity' => $product_quantity_order, 'importo_unitario' => $importoUnitario, 'importo_totale' => $importoTotale]);
        $order->updateQuantityToDistribute();


        //? Aggiorno il campo importo totale della commessa:
        $importoTotaleCommessa = $commission->orders->sum->importo_totale;
        $commission->update(['importo_totale' => $importoTotaleCommessa]);

        //? Aggiorno la contabilità dei clienti e operatori
        $commission->client->updateAccountability();
        Operator::all()->each(fn ($operator) => $operator->updateAccountability());

        return redirect(route('commission.edit', compact('commission')))->with('message', 'Ordine modificato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commission $commission, Order $order)
    {
        $order->delete();

        //? Aggiorno il campo importo totale della commessa:
        $importoTotaleCommessa = $commission->orders->sum->importo_totale;
        $commission->update(['importo_totale' => $importoTotaleCommessa]);

        //? Aggiorno la contabilità dei clienti e operatori
        $commission->client->updateAccountability();
        Operator::all()->each(fn ($operator) => $operator->updateAccountability());

        //? Aggiorno lo stato lavorazione della commessa
        $commission->updateStatus();

        return redirect()->back()->with('message', 'Ordine eliminato con successo.');
    }
}
