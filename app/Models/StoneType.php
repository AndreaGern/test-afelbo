<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoneType extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function stones()
    {
        return $this->hasMany(Stone::class);
    }
}
