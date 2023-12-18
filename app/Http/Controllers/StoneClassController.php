<?php

namespace App\Http\Controllers;

use App\Models\StoneClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoneClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stoneClasses = StoneClass::all();
        return view('admin.stone-classes.stone-classes-main', compact('stoneClasses'));
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
            'max_weight'=>'required',
            'min_weight'=>'required'
        ])->validate();

        StoneClass::create($request->all());
        return redirect(route('stoneClass.index'))->with('message','Classe di pietra creata con successo.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoneClass  $stoneClass
     * @return \Illuminate\Http\Response
     */
    public function edit(StoneClass $stoneClass)
    {
        return view('admin.stone-classes.edit', compact('stoneClass'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoneClass  $stoneClass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoneClass $stoneClass)
    {
        $stoneClass->update($request->all());
        return redirect(route('stoneClass.index'))->with('message','Classe di pietra modificata con successo.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoneClass  $stoneClass
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoneClass $stoneClass)
    {
        $stoneClass->delete();
        return redirect(route('stoneClass.index'))->with('message','Classe di pietra eliminata con successo.');
    }
}
