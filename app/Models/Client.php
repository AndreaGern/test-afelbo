<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function movements()
    {
        return $this->morphMany(Movement::class, 'movementable');
    }

    public function accountability()
    {
        return $this->hasOne(ClientAccountability::class);
    }

    public function getRevenue()
    {
        return $this->movements->sum->amount;

    }

    public function getTotalDue()
    {
        return $this->commissions->sum('importo_totale');
    }

    public function getUnpaidSum()
    {
        return round(($this->getTotalDue() - $this->getRevenue()), 2);
    }

    public function updateAccountability()
    {
        if ($this->accountability) {
            return $this->accountability->update([
                'revenue' => $this->getRevenue(),
                'total_due' => $this->getTotalDue(),
                'unpaid' => $this->getUnpaidSum(),
            ]);
        } else {
            return $this->accountability()->create([
                'revenue' => $this->getRevenue(),
                'total_due'=> $this->getTotalDue(),
                'unpaid' => $this->getUnpaidSum(),
            ]);
        }
    }

    public function getCommissionMovements(Commission $commission)
    {
        return $this->movements()->where('commission_id', $commission->id)->get();
    }
}
