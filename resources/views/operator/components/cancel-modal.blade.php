<div class="modal fade" id="cancelWorkModal{{ $index }}" tabindex="-1" role="dialog"
    aria-labelledby="cancelWorkModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelWorkModalLongTitle">Sei sicuro di voler annullare il completamento di
                    questa lavorazione?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('operator.undoCompletedQuantity', compact('task')) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="number" name="undoQuantity" id="undoQuantity" class="form-control" min="0"
                        max="{{ $task->completedQuantity }}" value="{{ old('undoQuantity') }}">
                    <button type="submit" class="btn btn-danger shadow my-4">Annulla Lavorazione</button>
                </form>
            </div>
        </div>
    </div>
</div>
