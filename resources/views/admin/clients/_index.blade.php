<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Codice</th>
                <th>Nome</th>
                <th>Codice Fiscale</th>
                <th>Partita IVA</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr data-route="{{ route('client.edit', compact('client')) }}">
                    <td>{{ $client->code }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->codice_fiscale }}</td>
                    <td>{{ $client->partita_iva }}</td>
                    <td class="text-right td-options-row d-flex justify-content-end">
                        <a class="btn btn-warning" href="{{ route('client.edit', compact('client')) }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <button type="button" class="btn btn-danger" data-toggle="modal"
                            data-target="#deleteClient{{ $client->id }}">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="deleteClient{{ $client->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="deleteClient{{ $client->id }}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Confermi di voler eliminare <strong
                                        class="font-weight-bold">{{ $client->name }}</strong>?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body d-flex justify-content-end">
                                <form action="{{ route('client.delete', compact('client')) }}" method="post">
                                    @method('DELETE')
                                    @csrf
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
