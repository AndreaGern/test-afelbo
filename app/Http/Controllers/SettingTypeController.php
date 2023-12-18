<?php

namespace App\Http\Controllers;

use App\Models\SettingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settingTypes = SettingType::all();
        return view('admin.setting-types.setting-types-main', compact('settingTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        SettingType::create($request->all());
        return redirect(route('settingType.index'))->with('message','Tipo di incastonatura creato con successo.');;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SettingType  $settingType
     * @return \Illuminate\Http\Response
     */
    public function show(SettingType $settingType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SettingType  $settingType
     * @return \Illuminate\Http\Response
     */
    public function edit(SettingType $settingType)
    {
        return view('admin.setting-types.edit', compact('settingType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SettingType  $settingType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SettingType $settingType)
    {
        $settingType->update($request->all());
        return redirect(route('settingType.index'))->with('message','Tipo di incastonatura modificato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SettingType  $settingType
     * @return \Illuminate\Http\Response
     */
    public function destroy(SettingType $settingType)
    {
        $settingType->delete();
        return redirect(route('settingType.index'))->with('message','Tipo di incastonatura eliminato con successo.');;
    }
}
