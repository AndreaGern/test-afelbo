<div class="form-group">
    @if (!$task->distribution->order)
    @else
        @if ($task->completedQuantity > 0)
            @if ($task->completedQuantity > 1)
                <button type="button" class="btn btn-danger shadow" data-toggle="modal"
                    data-target="#cancelWorkModal{{ $loop->index }}">
                    Annulla lavorazioni
                </button>
                @include('operator.components.cancel-modal', ['index' => $loop->index])
            @else
                <form action="{{ route('operator.undoCompletedQuantity', compact('task')) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="undoQuantity" id="undoQuantity" value="1">
                    <button type="submit" class="btn btn-danger shadow my-4">Annulla lavorazione</button>
                </form>
            @endif
        @endif
    @endif
</div>
