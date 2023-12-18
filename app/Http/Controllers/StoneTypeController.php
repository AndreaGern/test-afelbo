<?php

namespace App\Http\Controllers;

use App\Models\StoneType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoneTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stoneTypes = StoneType::all();
        return view('admin.stone-types.stone-types-main', compact('stoneTypes'));
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
            'code'=>'required',
            'description'=>'required',
        ])->validate();


        StoneType::create($request->all());
        return redirect(route('stoneType.index'))->with('message', 'Tipo di pietra creato con successo.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoneType  $stoneType
     * @return \Illuminate\Http\Response
     */
    public function edit(StoneType $stoneType)
    {
        return view('admin.stone-types.edit', compact('stoneType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoneType  $stoneType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoneType $stoneType)
    {
        $stoneType->update($request->all());
        return redirect(route('stoneType.index'))->with('message', 'Tipo di pietra modificato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoneType  $stoneType
     * @return \Illuminate\Http\Response
     */
    public function delete(StoneType $stoneType)
    {
        $stoneType->delete();
        return redirect(route('stoneType.index'))->with('message', 'Tipo di pietra eliminato con successo.');
    }
}
