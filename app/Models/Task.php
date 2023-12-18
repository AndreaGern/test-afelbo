<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Task extends Pivot
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'tasks';

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id')->withDefault();
    }

    public function distribution()
    {
        return $this->belongsTo(Distribution::class, 'distribution_id')->withDefault();
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public static function notCompletedCount()
    {
        //FATTORE 1
        // Per ogni ordine non completato, da product_id tirarsi fuori la somma dei processes
        //processesSumQuantity = processi totali di ordini non completati
        $orders = Order::where('completed', false)->with('product.processes')->withSum('processes', 'quantity')->get();
        // $orders = Order::with('product.processes')->withSum('processes','quantity')->get();

        // Moltiplicare qeusta somma per la quantity presente nella riga ordine
        // Sommare tutti i risultati di sopra per ogni ordine
        $processesSumQuantity = 0;
        foreach ($orders as $order) {
            $processesQuantity = $order->processes_sum_quantity * $order->quantity;
            $processesSumQuantity = $processesSumQuantity + $processesQuantity;
        }


        // # Secondo fattore
        $processesSumCompletedQuantity = 0;
        // Per ogni ordine non completato, prendi tutte le distributions associate a quell'ordine
        $ordersNotCompleted = Order::where('completed', false)->with('distributions.tasks')->get();
        // Per ogni distributions prendi tutti i tasks associati a quella distributions
        // Per ogni distributions prendi tutti i tasks.Completedquantity associati a quella distributions
        foreach ($ordersNotCompleted as $order) {
            $distribution_ids = $order->distributions->pluck('id');
            // task di quella distribution
            $tasks = Task::with('process')->whereIn('distribution_id', $distribution_ids)->get();

            foreach ($tasks as $task) {
                // quantità completata di un singolo task
                $taskCompletedQuantity = $task->completedQuantity;

                // quantità di ciascun processo associato a quel task (qui abbiamo il process id)
                $processQuantity = Process::where('id', $task->process_id)->pluck("quantity")->first();

                $processesSumCompletedQuantity = $processesSumCompletedQuantity + ($taskCompletedQuantity * $processQuantity);
            }
            //dd("fine");
        }
        // Per ogni distributions prendi tutti i task->process.quantity associati a quella distributions

        // Sommatoria di ( tasks.Completedquantity * task->process.quantity)


        $taskToDo = $processesSumQuantity - $processesSumCompletedQuantity;
        return $taskToDo;
        //return $processesSumQuantity;
    }

    public function getClientCode()
    {
        $client = $this->distribution && $this->distribution->order ? $this->distribution->order->commission->client : null;
        return $client ? $client->code : null;
    }

    public function getProductCode()
    {
        $product = $this->process ? $this->process->product : null;
        return $product ? $product->code : null;
    }

    public function getCostoOperatore()
    {
        $stone = $this->process && $this->process->stone ?  $this->process->stone : null;
        return $stone ? $stone->prezzariouser : null;
    }

    public function getCostoAlCliente()
    {
        $stone = $this->process && $this->process->stone ?  $this->process->stone : null;
        return $stone ? $stone->client_cost : null;
    }
}
