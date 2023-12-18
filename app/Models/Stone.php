<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stone extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'processes')->using(Process::class)->as('process')->withPivot('id', 'quantity');
    }

    public function stoneClass()
    {
        return $this->belongsTo(StoneClass::class);
    }

    public function settingType()
    {
        return $this->belongsTo(SettingType::class);
    }

    public function stoneType()
    {
        return $this->belongsTo(StoneType::class);
    }
}
