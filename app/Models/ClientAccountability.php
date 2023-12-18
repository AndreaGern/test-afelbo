<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAccountability extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'client_accountabilities';

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
