<?php

namespace App\Http\Livewire;

use App\Models\Distribution;
use App\Models\Operator;
use App\Models\Order;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ManageDistribution extends Component
{
    public Order $order;
    public Collection $operators;
    public Collection $unitaries;
    public Collection $partials;
    public $toDistribute;
    public $quantityToAssign;
    public $distributionType;
    public $showMessageOverlay = false;
    private $justUpdatedUnitary;


    protected $listeners = ['deletedDistributionAndTasks'];

    protected array $rules = [
        'unitaries.*.operator_id' => 'sometimes',
        'partials.*.tasks.*.operator_id' => 'sometimes'
    ];

    public function mount()
    {
        $this->order->load('distributions', 'distributions.tasks', 'distributions.tasks.operator', 'distributions.tasks.process');
        $this->unitaries = $this->order->getUnitaryDistributions();
        $this->partials = $this->order->getPartialDistributions();
        $this->toDistribute = $this->order->quantityToDistribute;
        $this->showMessageOverlay = session()->has('message') || $this->getErrorBag()->isNotEmpty();
    }

    public function onUnitariesOperatorChange($value, $oldValue, $index)
    {
        $updatedUnitary = $this->unitaries->get($index);
        if ($value === '' || $value == null) {
            $this->mount();
            $this->addError('emptyValue', 'Attenzione! Perfavore selezionare un\'operatore o eliminare questa ripartizione.');
            return;
        } elseif ($updatedUnitary->tasks->every(fn($task) => !$task->completed)) {
            $updatedUnitary->update(['operator_id' => $value]);
            $updatedUnitary->tasks->each(fn($task) => $task->update(['operator_id' => $value]));
            //? Update operators accountability
            $oldOperator = Operator::find($oldValue);
            if($oldOperator) {
                $oldOperator->updateAccountability();
            }
            Operator::find($value)->updateAccountability();
            session()->flash('message', 'Ripartizione aggiornata con successo!');
            $this->showMessageOverlay = true;
        } else {
            $this->addError('alreadyCompleted', 'Attenzione! Impossibile cambiare operatore perchè questa ripartizione è già stata completata.');
            $this->showMessageOverlay = true;

        }

    }

    public function onPartialOperatorChange($value, $oldValue, $partialIndex, $taskIndex)
    {
        $changedTask =  $this->partials->get($partialIndex)->tasks->get($taskIndex);

        if ($value === '' || $value == null) {
            $this->mount();
            $this->addError('emptyValue', 'Attenzione! Perfavore selezionare un\'operatore o eliminare questa ripartizione.');
            $this->showMessageOverlay = true;
            return;
        } elseif (!$changedTask->completed) {
            $changedTask->update(['operator_id' => $value]);
            //? Update operators accountability
            $oldOperator = Operator::find($oldValue);
            if($oldOperator) {
                $oldOperator->updateAccountability();
            }
            // TODO: Da capire se ha senso tenerlo quello per l'operatore nuovo visto che ora la contabilità viene aggiornata solo quando il task é completato.
            Operator::find($value)->updateAccountability();
            session()->flash('message', 'Ripartizione aggiornata con successo!');
            $this->showMessageOverlay = true;
        } else {
            $this->mount();
            $this->addError('alreadyCompleted', 'Attenzione! Impossibile cambiare operatore perchè questa ripartizione è già stata completata.');
            $this->showMessageOverlay = true;

        }

    }

    public function newDistribution()
    {
        if ($this->order->quantityIsNotExceed($this->quantityToAssign)) {
            $newDistribution = Distribution::create([
                'quantity' => $this->quantityToAssign,
                'order_id' => $this->order->id,
                'type' => $this->distributionType,
            ]);

            $this->order->processes->each(fn($process) => Task::create(["process_id" => $process->id, "distribution_id" => $newDistribution->id]));

            $this->order->updateQuantityToDistribute();

            $this->renderToDistribute();
            $this->order->load('distributions');
            $this->order->checkIsCompletedAndUpdate();
            ($this->distributionType == 'unitary') ? $this->unitaries->push($newDistribution) : $this->partials->push($newDistribution);

            $this->reset(['quantityToAssign', 'distributionType']);

            session()->flash('message', 'Ripartizione aggiunta con successo!');
            $this->showMessageOverlay = true;
        } else {
            $this->addError('alreadyCompleted', 'Attenzione! La quantità scelta é superiore alla quantità da ripartire rimanente. Cambiarla e riprovare.');
            $this->showMessageOverlay = true;

        }
    }

    public function renderToDistribute()
    {
        $this->toDistribute = $this->order->quantityToDistribute;
    }

    public function deletedDistributionAndTasks(Distribution $distribution)
    {
        if ($distribution->tasks->every(fn($task) => !$task->completed)) {
            $distribution->tasks()->delete();
            $distribution->delete();

            $this->order->updateQuantityToDistribute();
            $this->order->load('distributions');
            $this->order->checkIsCompletedAndUpdate();

            //? Update operators accountability
            Operator::all()->each(fn($operator) => $operator->updateAccountability());

            $this->mount();
            session()->flash('message', 'Ripartizione eliminata con successo!');
            $this->showMessageOverlay = true;

        } else {

            $this->addError('alreadyCompleted', 'Attenzione! Impossibile eliminare questa ripartizione poiché è già stata interamente o parzialmente completata.');
            $this->showMessageOverlay = true;

        }
    }

    public function closeMessage()
    {
        $this->showMessageOverlay = false;
    }

    public function render()
    {
        return view('livewire.manage-distribution');
    }
}
