<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'workstation', 'firstLogin'];

    /**
     * Get the user that owns the operator.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tasks assigned to the operator.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the movements associated with the operator.
     */
    public function movements()
    {
        return $this->morphMany(Movement::class, 'movementable');
    }

    /**
     * Get the accountability record associated with the operator.
     */
    public function accountability()
    {
        return $this->hasOne(OperatorAccountability::class);
    }

    /**
     * Get the sum of all movements associated with the operator.
     *
     * @return float
     */
    public function getMovementsSum()
    {
        return $this->movements->sum->amount;
    }

    /**
     * Calcoliamo il Cottimo totale assegnato all'operatore
     * solo per i task completati.
     * Get the sum of all piecework assigned to the operator.
     *
     * @return float
     */
    public function getPieceworkSum()
    {
        // When the operator is assigned a distribution, the cost is calculated by multiplying the operator's cost by the quantity of stone processed. We take in consideration solo i task completati.
        return $this->load('tasks', 'tasks.process', 'tasks.process.stone', 'tasks.distribution')->tasks()->has('process')->has('distribution.order')->where('completed', true)->get()->reduce(function ($totalPiecework, $task) {
            return $totalPiecework + ($task->getCostoOperatore() * $task->distribution->quantity);
        });
    }

    /**
     * Get the sum of all unpaid piecework assigned to the operator.
     * Cottimo non pagato di quelle completate
     *
     * @return float
     */
    public function getUnpaidSum()
    {
        return round(($this->getPieceworkSum() - $this->getMovementsSum()), 2);
    }

    /**
     * Numero di pietre assegnate all'operatore che non sono state completate
     *
     * @return float
     */
    public function getAssignedStonesNotCompleted()
    {
        return $this->load('tasks.process.stone')->tasks()->has('process')->has('distribution.order')->where('completed', false)->get()->reduce(function ($pietreAssegnate, $task) {
            return $pietreAssegnate + ($task->distribution->quantity * $task->process->quantity);
        });
    }

    /**
     * Update the accountability record for the operator.
     * Se c'é giá un record di contabilitá per l'operatore lo aggiorna, altrimenti lo crea.
     * @return bool Whether the update was successful or not.
     */
    public function updateAccountability()
    {
        if ($this->accountability) {
            return $this->accountability->update([
                'payments_sum' => $this->getMovementsSum(),
                'piecework' => $this->getPieceworkSum() ?? 0,
                'unpaid' => $this->getUnpaidSum(),
            ]);
        } else {
            return $this->accountability()->create([
                'payments_sum' => $this->getMovementsSum(),
                'piecework' => $this->getPieceworkSum() ?? 0,
                'unpaid' => $this->getUnpaidSum(),
            ]);
        }
    }
}
