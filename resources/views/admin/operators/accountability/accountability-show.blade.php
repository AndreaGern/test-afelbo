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
                                        <div class="card-header ">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12 col-lg-8 mb-2">
                                                        <h5 class="font-weight-bold">{{ $operator->user->name }}</h5>
                                                    </div>
                                                    <div class="col-12 col-lg-4">
                                                        <div class="d-flex justify-content-lg-end">
                                                            <div class="d-flex align-items-baseline mr-5">
                                                                <p>Codice</p>
                                                                <h6 class="pl-2 font-weight-bold">{{ $operator->code }}</h6>
                                                            </div>
                                                            <div class="d-flex align-items-baseline mr-5">
                                                                <p>Banco</p>
                                                                <h6 class="pl-2 font-weight-bold">
                                                                    {{ $operator->workstation }}</h6>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!--[ daily sales section ] start-->
                                                <div class="col-md-6 col-xl-4">
                                                    <div class="card daily-sales">
                                                        <div class="card-block">
                                                            <!-- la cifra che il dipendente ha ricevuto finora perché gli è stato pagato -->
                                                            <h6 class="mb-4">Pagamenti effettuati</h6>
                                                            <div class="row d-flex align-items-center">
                                                                <div class="col-9">
                                                                    <h3 class="f-w-300 d-flex align-items-center m-b-0">€
                                                                        {{ $operator->accountability->payments_sum ?? 0 }}
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--[ daily sales section ] end-->
                                                <!--[ Monthly  sales section ] starts-->
                                                <div class="col-md-6 col-xl-4">
                                                    <div class="card Monthly-sales">
                                                        <div class="card-block">
                                                            <!-- totale sia retribuiti che non retribuiti -->
                                                            <h6 class="mb-4">Totale Cottimo</h6>
                                                            <div class="row d-flex align-items-center">
                                                                <div class="col-9">
                                                                    <h3 class="f-w-300 d-flex align-items-center m-b-0">€
                                                                        {{ $operator->accountability->piecework ?? 0 }}</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--[ Monthly  sales section ] end-->
                                                <!--[ year  sales section ] starts-->
                                                <div class="col-md-12 col-xl-4">
                                                    <div class="card yearly-sales">
                                                        <div class="card-block">
                                                            <!-- ciò che il dipendente ha accumulato e che l'azienda Afelbo gli deve -->
                                                            <h6 class="mb-4">Non Retribuito</h6>
                                                            <div class="row d-flex align-items-center">
                                                                <div class="col-9">
                                                                    <h3 class="f-w-300 d-flex align-items-center  m-b-0">€
                                                                        {{ round($operator->accountability->unpaid, 2) ?? 0 }}
                                                                    </h3>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row card mx-0 py-3">
                                                <form
                                                    action="{{ route('accountability.newMovementOperator', compact('operator')) }}"
                                                    method="POST" class="px-2">
                                                    @csrf
                                                    <h5 class="aggiungi-pagamento">AGGIUNGI PAGAMENTO</h5>
                                                    <div class="row">
                                                        <div
                                                            class="col-12 col-md d-flex flex-column mt-3 align-items-start form-group ">
                                                            <label for="name" class="mb-0">Causale</label>
                                                            <input class="form-control w-100" name='causal' id='name'
                                                                type="text" value="{{ old('causal') }}" required>
                                                        </div>
                                                        <div
                                                            class="col-12 col-md d-flex flex-column mt-3 align-items-start form-group ">
                                                            <label for="code" class="mb-0">Importo</label>
                                                            <input class="form-control w-100" type="number" step="0.01"
                                                                name='amount' id='code' value="{{ old('amount') }}"
                                                                required>
                                                        </div>

                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit"
                                                            class="ml-3 btn btn-primary shadow">Aggiungi</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="card-block table-border-style">
                                                <div class="row mb-3">
                                                    <div class="col-12 text-right">
                                                        <a class="btn btn-danger shadow px-4 py-2 btn-sm text-light"
                                                            data-toggle="modal" data-target="#azzeraContatori">Azzera
                                                            Contantori</a>
                                                        <x-modal modal-id="azzeraContatori"
                                                            modal-title="Azzera i contatori dell'operatore"
                                                            modal-body="Sei sicuro di voler azzerare i contatori?">
                                                            <!-- Altri contenuti della modale, se necessario -->
                                                            <form
                                                                action="{{ route('accountability.resetCountersOperator', compact('operator')) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit"
                                                                    class="btn btn-primary shadow my-2">Salva</button>
                                                            </form>
                                                        </x-modal>

                                                    </div>
                                                </div>
                                                <h6 class="font-weight-bold">Filtra elenco</h6>
                                                <div class="d-flex flex-md-row flex-column align-items-baseline">
                                                    <div class="form-group m-3">
                                                        <label for="min">Data minima</label>
                                                        <input type="text" id="min" name="min">
                                                    </div>
                                                    <div class="form-group m-3">
                                                        <label for="max">Data massima</label>
                                                        <input type="text" id="max" name="max">
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Causale</th>
                                                                <th>Importo</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($operator->movements as $movement)
                                                                <tr>
                                                                    <td>{{ $movement->created_at->format('d/m/y') }}</td>
                                                                    <td>{{ $movement->causal }}</td>
                                                                    <td>{{ $movement->amount }} €</td>
                                                                    <td
                                                                        class="text-right td-options-row d-flex justify-content-end">

                                                                        <button type="button" class="btn btn-danger"
                                                                            data-toggle="modal"
                                                                            data-target="#deleteMovementModal{{ $loop->index }}">
                                                                            <i class="fa-solid fa-trash-can"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>

                                                                <!-- Modal eliminazione pagamento -->
                                                                <div class="modal fade"
                                                                    id="deleteMovementModal{{ $loop->index }}"
                                                                    tabindex="-1" role="dialog"
                                                                    aria-labelledby="exampleModalLabel"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="exampleModalLabel">
                                                                                    Elimina pagamento</h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Sei sicuro di voler eliminare questo
                                                                                pagamento?
                                                                                L'azione sarà irreversibile.
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-dismiss="modal">Annulla</button>
                                                                                <form
                                                                                    action="{{ route('accountability.deleteMovementOperator', compact('operator', 'movement')) }}"
                                                                                    method="post">
                                                                                    @method('DELETE')
                                                                                    @csrf
                                                                                    <button type="submit"
                                                                                        class="btn btn-danger shadow">Elimina</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [ Hover-table ] end -->

                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
