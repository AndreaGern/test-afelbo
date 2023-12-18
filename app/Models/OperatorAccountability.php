<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorAccountability extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = 'operator_accountabilities';

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
