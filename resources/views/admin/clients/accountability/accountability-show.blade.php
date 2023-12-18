@extends('layout')
@section('content')
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header ">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    @if ($client->name)
                                                        <div class="col-12 col-lg-8 mb-2">
                                                            <h5 class="font-weight-bold text-uppercase">{{ $client->name }}
                                                            </h5>
                                                        </div>
                                                    @endif
                                                    <div class="col-12 col-lg-4 mr-0 ml-auto">
                                                        <div
                                                            class="d-flex align-items-baseline justify-content-lg-end mr-5">
                                                            <p>Codice</p>
                                                            <h6 class="pl-2 font-weight-bold">{{ $client->code }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <!--[ daily sales section ] start-->
                                                    <div class="col-md-6 col-xl-4">
                                                        <div class="card daily-sales">
                                                            <div class="card-block">
                                                                <h6 class="mb-4">Pagamenti</h6>
                                                                <div class="row d-flex align-items-center">
                                                                    <div class="col-9">
                                                                        <h3 class="f-w-300 d-flex align-items-center m-b-0">
                                                                            €
                                                                            {{ $client->getRevenue() }}</h3>
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
                                                                <h6 class="mb-4">Lavori</h6>
                                                                <div class="row d-flex align-items-center">
                                                                    <div class="col-9">
                                                                        <h3
                                                                            class="f-w-300 d-flex align-items-center  m-b-0">
                                                                            €
                                                                            {{ $client->getTotalDue() }}</h3>
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
                                                                <h6 class="mb-4">Diff.Pagamenti - Lavori</h6>
                                                                <div class="row d-flex align-items-center">
                                                                    <div class="col-9">
                                                                        <h3
                                                                            class="f-w-300 d-flex align-items-center  m-b-0">
                                                                            €
                                                                            @if ($client->accountability)
                                                                                {{ round($client->accountability->unpaid, 2) ?? 0 }}
                                                                            @else
                                                                                0
                                                                            @endif
                                                                        </h3>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--[ year  sales section ] end-->

                                                </div>
                                            </div>
                                            <div class="container-fluid">
                                                <div class="row card p-2">
                                                    <form
                                                        action="{{ route('accountability.newMovementClient', compact('client')) }}"
                                                        method="POST">
                                                        @csrf

                                                        <h5 class="aggiungi-pagamento ml-0">AGGIUNGI PAGAMENTO</h5>
                                                        <div class="row mx-2">
                                                            <div
                                                                class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                <label for="causal" class="mb-0">Causale</label>
                                                                <input class="form-control w-100"
                                                                    value="{{ old('causal') }}" name='causal'
                                                                    id='causal' type="text" required>
                                                            </div>
                                                            <div
                                                                class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                <label for="amount" class="mb-0">Importo</label>
                                                                <input class="form-control w-100" type="number"
                                                                    step="0.01" min="1"
                                                                    value="{{ old('amount') }}" name='amount'
                                                                    id='code' required>
                                                            </div>
                                                            <div
                                                                class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                <label class="mb-0" for="commission">Riferito a</label>
                                                                <select class="form-control" id="commission"
                                                                    value="{{ old('commission') }}" name="commission"
                                                                    required>
                                                                    <option value=""></option>

                                                                    @forelse ($client->commissions as $commission)
                                                                        <option value="{{ $commission->id }}">Ordine:
                                                                            {{ $commission->getCommissionCode() }}</option>
                                                                    @empty
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button type="submit"
                                                                class="ml-3 btn btn-primary shadow">Aggiungi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="card-block table-border-style">
                                                <div class="row mb-3">
                                                    <div class="col-12 text-right">
                                                        <a class="btn btn-danger shadow px-4 py-2 btn-sm text-light"
                                                            data-toggle="modal" data-target="#azzeraContatori">Azzera
                                                            Contantori</a>
                                                        <x-modal modal-id="azzeraContatori"
                                                            modal-title="Azzera i contatori del clietne"
                                                            modal-body="Sei sicuro di voler azzerare i contatori?">
                                                            <form
                                                                action="{{ route('accountability.resetCountersClient', compact('client')) }}"
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
                                                                <th>Causale</th>
                                                                <th>Importo</th>
                                                                <th>Data</th>
                                                                {{-- <th>Commessa</th> --}}
                                                                <th>Ordine</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($client->movements as $movement)
                                                                <tr>
                                                                    <td>{{ $movement->causal }}</td>
                                                                    <td>{{ $movement->amount }}€</td>
                                                                    <td>{{ $movement->created_at->format('d/m/y') }}</td>
                                                                    <td>{{ $movement->commission ? $movement->commission->getCommissionCode() : 'N.D.' }}
                                                                    </td>
                                                                    <td
                                                                        class="text-right td-options-row d-flex justify-content-end">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-toggle="modal"
                                                                            data-target="#deleteMovementModal{{ $loop->index }}">
                                                                            <i class="fa-solid fa-trash-can"></i>
                                                                        </button>

                                                                        <!-- Modal -->
                                                                        <div class="modal fade"
                                                                            id="deleteMovementModal{{ $loop->index }}"
                                                                            tabindex="-1" role="dialog"
                                                                            aria-labelledby="exampleModalLabel"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="exampleModalLabel">Elimina
                                                                                            pagamento</h5>
                                                                                        <button type="button"
                                                                                            class="close"
                                                                                            data-dismiss="modal"
                                                                                            aria-label="Close">
                                                                                            <span
                                                                                                aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <p class="text-left">Sei sicuro di
                                                                                            voler
                                                                                            eliminare questo pagamento? <br>
                                                                                            L'azione sarà irreversibile.</p>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary"
                                                                                            data-dismiss="modal">Annulla</button>
                                                                                        <form
                                                                                            action="{{ route('accountability.deleteMovementClient', compact('client', 'movement')) }}"
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

                                                                    </td>
                                                                </tr>
                                                            @empty
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
