 <div class="btn btn-link @if ($task->completed) text-success @else text-dark @endif" type="button"
     data-toggle="collapse" data-target="#collapse{{ $loop->index }}" aria-expanded="true" aria-controls="collapse">
     Lavorazione n.{{ $loop->index + 1 }} -
     Ordine:
     {{ $task->distribution->order ? $task->distribution->order->code : 'N.D.' }}
     -
     Quantità:
     {{ $task->distribution->quantity }} - <span
         class="font-weight-bold">{{ $task->completed ? 'Completata' : 'Non completata' }}</span>
     - Creata il
     {{ $task->created_at->format('d/m/Y') }} -
     Codice Cliente:
     <span class="font-weight-bold">{{ $task->getClientCode() ?? 'N.D.' }}</span>
     -
     <br>
     Codice Prodotto:
     <span class="font-weight-bold">{{ $task->getProductCode() ?? 'N.D.' }}</span>
     -
     Costi Operatore:
     <span class="font-weight-bold">
         {{ $task->getCostoOperatore() ?? 'N.D.' }}
     </span>
 </div>
 <div class="d-flex justify-content-center">
     @if ($task->process)
         @include('operator.components.completed-row', [
             'task' => $task,
             'loop' => $loop,
         ])
         @include('operator.components.cancel-row', [
             'task' => $task,
             'loop' => $loop,
         ])
     @endif
 </div>
