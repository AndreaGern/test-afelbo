<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Codice</th>
                <th>Nome Cognome</th>
                <th>Postazione</th>
                <th>Pietre da lavorare</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($operators as $operator)
                <tr data-route="{{ route('operator.show', compact('operator')) }}">
                    <td>{{ $operator->code }}</td>
                    <td>{{ $operator->user->name }}</td>
                    <td>{{ $operator->workstation }}</td>
                    <td>{{ $operator->getAssignedStonesNotCompleted() }}</td>
                    <td class="text-right td-options-row d-flex justify-content-end">
                        <a href="{{ route('operator.show', compact('operator')) }}"
                            class="btn btn-primary shadow text-white">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('operator.edit', compact('operator')) }}"
                            class="btn btn-warning shadow text-white">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <button type="button" class="btn btn-danger shadow" data-toggle="modal"
                            data-target="#exampleModalCenter{{ $operator->id }}">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                        </span>
                    </td>
                </tr>
                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter{{ $operator->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalCenter{{ $operator->id }}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Confermi di voler eliminare <strong
                                        class="font-weight-bold">{{ $operator->user->name }}</strong>?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body d-flex justify-content-end">
                                <form action="{{ route('operator.delete', compact('operator')) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger shadow" type="submit">Elimina</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>
