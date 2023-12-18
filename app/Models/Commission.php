<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'deadline' => 'datetime:Y-m-d',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'orders')->using(Order::class)->as('order')->withPivot('id', 'code', 'quantity', 'quantityToDistribute', 'importo_unitario', 'importo_totale', 'completed', 'deliveredProducts');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    //? One commission has many movements
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function getCommissionMovements()
    {
        return $this->movements->sum->amount;
    }

    public function updateStatus()
    {
        //? If all the orders are completed update the status to "completato"
        $isCompleted = $this->orders->every(function ($order) {
            return $order->completed;
        });

        //? If all the orders are delivered update the status to "consegnato"
        $isDelivered = $this->orders->every(function ($order) {
            return $order->deliveredProducts == $order->quantity;
        });

        //? If even one of the orders has quantityToDistribute not equal to quantity update the status to "in lavorazione"
        $isInWork = $this->orders->some(function ($order) {
            return $order->quantityToDistribute != $order->quantity;
        });

        // If commission is completed update the status to "completato" but if is also delivered update the status to "consegnato" if not update the status to "in lavorazione"
        switch (true) {
            case $isCompleted && $isDelivered:
                return $this->update(['stato_lavorazione' => 'consegnato']);
                break;
            case $isCompleted:
                return $this->update(['stato_lavorazione' => 'completato']);
                break;
            case $isDelivered:
                return $this->update(['stato_lavorazione' => 'consegnato']);
                break;
            case $isInWork:
                return $this->update(['stato_lavorazione' => 'in lavorazione']);
                break;
            default:
                return $this->update(['stato_lavorazione' => 'da lavorare']);
                break;
        }
    }

    public function getCommissionCode()
    {
        $code = $this->id;
        switch ($code) {
            case $code < 10:
                $code = "000$code";
                break;
            case $code < 100:
                $code = "00$code";
                break;
            case $code < 1000:
                $code = "0$code";
                break;
            default:
                break;
        }
        return $code;
    }

    /**
     * Deliver the commission by delivering each order associated with it.
     *
     * @return void
     */
    public function deliver()
    {
        $this->orders->each(function ($order) {
            $order->deliver($this);
        });
    }
}
