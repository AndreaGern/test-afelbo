<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Process extends Pivot
{
    use HasFactory;

    public $table = 'processes';
    public $incrementing = true;

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'distributions')->using(Distribution::class)->as('distribution')->withPivot('order_id', 'process_id', 'operator_id', 'quantity');
    }
    public function stone()
    {
        return $this->belongsTo(Stone::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
