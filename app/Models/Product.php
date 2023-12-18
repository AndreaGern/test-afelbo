<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stones()
    {
        return $this->belongsToMany(Stone::class, 'processes')->using(Process::class)->as('process')->withPivot('id', 'quantity');
    }

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    public function commissions()
    {
        return $this->belongsToMany(Commission::class, 'orders')->using(Order::class)->as('order')->withPivot('id', 'code', 'quantity', 'importo_unitario', 'importo_totale', 'completed', 'deliveredProducts');
    }

    public function getStoneClassesCodes()
    {
        return StoneClass::find($this->stones()->get()->pluck('stone_class_id'))->map(fn ($stoneClass) => $stoneClass->code)->join(', ');
    }

    public function getSettingTypesCodes()
    {
        return SettingType::find($this->stones()->get()->pluck('setting_type_id'))->map(fn ($settingType) => $settingType->code)->join(', ');
    }
}
