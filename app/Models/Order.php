<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Order extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    protected $table = 'orders';

    protected $guarded = [];

    //? Un ordine ha più LAVORAZIONI tramite i PRODOTTI

    public function processes()
    {
        return $this->hasManyThrough(
            Process::class,
            Product::class,
            'id', // Foreign key on the Product table...
            'product_id', // Foreign key on the Process table...
            'product_id', // Local key on the Order table...
            'id' // Local key on the Product table...
        );
    }

    //check if the order has at least one distribution
    public function hasDistributions()
    {
        return $this->distributions()->exists();
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    //? Get how many product are delivered (completed)
    public function getDeliveredProductsQuantity()
    {
        return $this->deliveredProducts;
    }

    //? Is delivered
    public function isDelivered()
    {
        return $this->deliveredProducts == $this->quantity;
    }

    //? Get how many product are distributed (assigned)
    public function getDistributedProductsQuantity()
    {
        return $this->quantity - $this->quantityToDistribute;
    }

    public function getUnitaryDistributions()
    {
        return $this->distributions()->where('type', 'unitary')->get();
    }

    public function getPartialDistributions()
    {
        return $this->distributions()->where('type', 'partial')->get();
    }

    public function updateQuantityToDistribute()
    {
        $newTotal = $this->quantity - $this->distributions()->sum('quantity');
        return $this->update(['quantityToDistribute' => ($newTotal)]);
    }

    public function quantityIsNotExceed(int $quantityToDistribute)
    {
        if ($this->quantity - ($this->distributions()->sum('quantity') + $quantityToDistribute) >= 0) {
            return true;
        }
        return false;
    }

    //Get the total of the completed products of the order from the distributions
    public function getCompletedProducts()
    {
        return $this->distributions->sum(fn ($distribution) => $distribution->tasks->sum(fn ($task) => $task->completed));
    }

    public function checkIsCompletedAndUpdate()
    {
        $isCompleted = $this->distributions->every(fn ($distribution) => $distribution->tasks->every(fn ($task) => $task->completed));
        $isTotallyDistributed = $this->quantityToDistribute == 0;
        if ($isCompleted && $isTotallyDistributed) {
            return $this->update(['completed' => true]);
        }
        return $this->update(['completed' => false]);
    }


    /**
     * Deliver the products for the given commission.
     *
     * @param Commission $commission The commission to deliver the products for.
     * @param int|null $deliveredProducts The number of delivered products. If null, the number of distributed products will be used.
     * @return void
     */
    public function deliver(Commission $commission, $deliveredProducts = null)
    {
        $this->update(['deliveredProducts' => $deliveredProducts ?? $this->getDistributedProductsQuantity()]);

        //? if the delivered products are equal to the distributed products and they are both equal to the order products quantity (which is the total) , the order is completed
        if ($this->deliveredProducts == $this->getDistributedProductsQuantity() && ($this->deliveredProducts == $this->quantity)) {
            $this->update(['completed' => true]);
        }

        //? Update the commission status to check if the commission is delivered 
        $commission->updateStatus();
    }
}
