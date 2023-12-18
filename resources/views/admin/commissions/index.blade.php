@extends('layout')
@section('content')
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ Hover-table ] start -->
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12 col-lg-8 mb-4">
                                                        <h5 class="font-weight-bold">ORDINI</h5>
                                                    </div>
                                                    <div class="col-12 col-lg-4">
                                                        <div class="d-flex flex-column align-items-lg-end">
                                                            <a class="btn btn-info ml-3"
                                                                href="{{ route('commission.create') }}">Aggiungi nuovo
                                                                ordine</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block table-border-style">
                                            <div class="row">
                                                <div class="col-12 col-md-4 mb-3">
                                                    <div class="">
                                                        <label for="filtro-codice-cliente">Filtra per codice
                                                            cliente:</label>
                                                        <input type="text" id="filtro-codice-cliente"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Codice ordine</th>
                                                            <th name="codice_cliente">Codice cliente</th>
                                                            <th>Descrizione ordine</th>
                                                            <th>Data di consegna</th>
                                                            <th>Stato</th>
                                                            <th>Importo totale</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($commissions as $commission)
                                                            <tr
                                                                data-route="{{ route('commission.edit', compact('commission')) }}">
                                                                <td>{{ $commission->getCommissionCode() }}</td>
                                                                <td>{{ $commission->client->code }}</td>
                                                                <td>{{ $commission->commission_description }}</td>
                                                                <td>{{ $commission->deadline->format('d/m/y') }}</td>
                                                                <td>{{ $commission->stato_lavorazione }}</td>
                                                                <td>{{ $commission->importo_totale }}<span>€</span></td>
                                                                <td class="d-flex justify-content-end">
                                                                    <a class="btn btn-success text-light shadow"
                                                                        data-toggle="modal"
                                                                        data-target="#deliverAllModal{{ $commission->id }}">
                                                                        Consegna tutto <i class="fa-solid fa-check"></i>
                                                                    </a>
                                                                    <a class="btn btn-info shadow"
                                                                        href="{{ route('commission.downloadCommissionPdf', compact('commission')) }}">
                                                                        Scarica <i class="fa-solid fa-file-pdf"></i>
                                                                    </a>
                                                                    <a class="btn btn-primary btn-sm shadow"
                                                                        href="{{ route('commission.edit', compact('commission')) }}">Apri</a>
                                                                    <button class="btn btn-danger shadow"
                                                                        data-toggle="modal"
                                                                        data-target="#deleteCommissionModal{{ $commission->id }}">
                                                                        <i class="fa-solid fa-trash-can"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>

                                                            <!-- Modal di Consegna -->
                                                            <div class="modal fade"
                                                                id="deliverAllModal{{ $commission->id }}" tabindex="-1"
                                                                role="dialog"
                                                                aria-labelledby="deliverAllModal{{ $commission->id }}Title"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLongTitle">
                                                                                Confermi di voler consegnare l'ordine
                                                                                <strong
                                                                                    class="font-weight-bold">{{ $commission->getCommissionCode() }}</strong>?
                                                                                Ogni quantità già distribuita verrà marcata
                                                                                come consegnata.
                                                                            </h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body d-flex justify-content-end">
                                                                            <form
                                                                                action="{{ route('commission.deliverCommission', ['commission' => $commission]) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <button class="btn btn-success shadow"
                                                                                    type="submit">Conferma</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Modal di eliminazione-->
                                                            <div class="modal fade"
                                                                id="deleteCommissionModal{{ $commission->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="deleteCommissionModal{{ $commission->id }}Title"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLongTitle">
                                                                                Confermi di voler eliminare l'ordine:
                                                                                <strong
                                                                                    class="font-weight-bold">{{ $commission->getCommissionCode() }}</strong>?
                                                                            </h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body d-flex justify-content-end">
                                                                            <form
                                                                                action="{{ route('commission.delete', ['client' => $commission->client, 'commission' => $commission]) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button class="btn btn-danger shadow"
                                                                                    type="submit">Elimina</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [ Hover-table ] end -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#filtro-codice-cliente').on('keyup', function() {
                    var filtro = $(this).val();
                    // Applica il filtro alla colonna del codice cliente
                    var table = $('.table').DataTable();
                    table.column('[name="codice_cliente"]').search(filtro).draw();
                });
            });
        </script>
    @endpush
@endsection
