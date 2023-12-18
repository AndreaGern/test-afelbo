<div class="modal fade" id="changeQuantityModal{{ $index }}" tabindex="-1" role="dialog"
    aria-labelledby="changeQuantityCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyModalLongTitle">Quanti ne hai completati?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('operator.updateCompletedQuantity', compact('task')) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <label for="completedQuantity">Quantità completata</label>
                                <input type="number" name="completedQuantity" id="completedQuantity"
                                    class="form-control" min="0" max="{{ $task->distribution->quantity }}"
                                    value="{{ $task->completedQuantity }}">
                                <button type="submit" class="btn btn-primary shadow my-4">Salva</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
