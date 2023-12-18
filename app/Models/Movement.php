<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $table = 'financials_movements';

    protected $guarded = [];

    //? Get operator or client financial movement
    public function movementable()
    {
        return $this->morphTo();
    }

    //? One commission has many movements.
    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }
    
    
}
