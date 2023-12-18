<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Operator;
use App\Models\Order;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Order $order)
    {
        $operators = Operator::all();
        $order->load('product.stones.stoneType', 'product.stones.settingType', 'product.stones.stoneClass', 'distributions');

        return view('admin.distributions.create', compact('operators', 'order'));
    }
}
