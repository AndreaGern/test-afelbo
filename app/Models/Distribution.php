<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Distribution extends Pivot
{
    use HasFactory;

    protected $with = ['tasks'];
    public $incrementing = true;
    protected $table = 'distributions';
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'distribution_id');
    }

    public function getUnitaryOperatorId()
    {
        return $this->tasks->first() ? $this->tasks->first()->operator->id : null;
    }

    public function toggleCompleted()
    {
        if ($this->completed) {
            return $this->update(['completed' => false]);
        }
        return $this->update(['completed' => true]);
    }

    public function checkIsCompletedAndUpdate()
    {
        $isCompleted = $this->tasks->every(fn ($task) => $task->completed);

        if ($isCompleted) {
            return $this->update(['completed' => true]);
        }

        return $this->update(['completed' => false]);
    }

    public function getCostoAlCliente()
    {
        return $this->tasks->reduce(function ($acc, $task) {
            return $acc + $task->process->stone->client_cost;
        });
    }

    public function getCostoAllOperatore()
    {
        return $this->tasks->reduce(function ($acc, $task) {
            return $acc + $task->process->stone->prezzariouser;
        });
    }
}
