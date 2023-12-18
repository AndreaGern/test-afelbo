        <div class="form-group">
            @if (!$task->distribution->order)
                <h5 class="text-secondary mt-2">Questa lavorazione fa parte di un ordine eliminato.</h5>
            @else
                @if ($task->completedQuantity === $task->distribution->quantity)
                @else
                    @if ($task->distribution->quantity > 1)
                        <button type="button" class="btn btn-primary shadow" data-toggle="modal"
                            data-target="#changeQuantityModal{{ $loop->index }}">
                            Rendi completato
                        </button>
                        @include('operator.components.completed-modal', ['index' => $loop->index])
                    @else
                        <form action="{{ route('operator.updateCompletedQuantity', compact('task')) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <input type="hidden" name="completedQuantity" id="completedQuantity"
                                            value="1">
                                        <button type="submit" class="btn btn-primary shadow my-4">Rendi
                                            completato</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                @endif
            @endif
        </div>
