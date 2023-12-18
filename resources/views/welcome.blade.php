@extends('layout')
@section('content')
@section('style')
    <style>
        .badge {
            padding: 0;
        }
    </style>
@endsection

<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="container-fluid">
                        <div class="row welcome">
                            <div class="col-12 col-lg-8 ">
                                <h3 class="text-info font-weight-bold">Ciao, @auth {{ Auth::user()->name }}
                                    @else
                                        Mario
                                    Rossi! @endauth
                                </h3>
                                <h5 class="text-info font-weight-bold">Benvenuto nel gestionale di Afelbo</h5>
                            </div>
                            <div class="col-12 col-lg-4 clock-system d-none">
                                <div class="card bg-white text-info">
                                    <h3 class="card-title text-center">
                                        <div class="d-flex flex-wrap flex-column justify-content-center mt-2 text-info">
                                            <div>
                                                Sono le
                                                <span class="badge text-info hours"></span>:<span
                                                    class="badge text-info min"></span>:<span
                                                    class="badge text-info sec"></span>
                                            </div>
                                            <div>
                                                del
                                                <span class="badge text-info day"></span>.<span
                                                    class="badge text-info month"></span>.<span
                                                    class="badge text-info year"></span>
                                            </div>
                                        </div>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- se sei admin => elenco ordini --}}
                    @if (Auth::user()->isAdmin())
                        <div class="container-fluid mb-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="text-info font-weight-bold">Elenco ordini</h3>
                                            <h4 class="text-info font-weight-bold">Pietre da lavorare:
                                                {{ $tasksNotCompleted }}</h4>
                                        </div>
                                        <div>
                                            <a class="btn btn-primary shadow"
                                                href="{{ route('commission.create') }}">Nuovo
                                                ordine</a>
                                        </div>
                                    </div>
                                    <div class="row align-items-baseline">
                                        <div class="col-12 col-md-6">
                                            <h6 class="font-weight-bold mt-5">Filtra elenco per data di consegna</h6>
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group m-3">
                                                        <label for="min">Data minima</label>
                                                        <input type="text" id="min" name="min"
                                                            class="form-control">
                                                    </div>

                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group m-3">
                                                        <label for="max">Data massima</label>
                                                        <input type="text" id="max" name="max"
                                                            class="form-control">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <h6 class="font-weight-bold mt-5">Filtra elenco per codice cliente
                                            </h6>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group m-3">
                                                        <label for="filtro-codice-cliente">Codice
                                                            cliente:</label>
                                                        <input type="text" id="filtro-codice-cliente"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table admin-table table-hover">
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
                                                @foreach ($notDeliveredCommissions as $commission)
                                                    <tr
                                                        data-route="{{ route('commission.edit', compact('commission')) }}">
                                                        <td>{{ $commission->getCommissionCode() }}</td>
                                                        <td>{{ $commission->client->code }}</td>
                                                        <td>{{ $commission->commission_description }}</td>
                                                        <td>{{ $commission->deadline->format('d/m/y') }}</td>
                                                        <td>{{ $commission->stato_lavorazione }}</td>
                                                        <td>{{ $commission->importo_totale }}<span>€</span></td>
                                                        <td class="d-flex justify-content-lg-end">
                                                            <a class="btn btn-info shadow"
                                                                href="{{ route('commission.downloadCommissionPdf', compact('commission')) }}">
                                                                Scarica <i class="fa-solid fa-file-pdf"></i>
                                                            </a>
                                                            <a class="btn btn-primary btn-sm shadow"
                                                                href="{{ route('commission.edit', compact('commission')) }}">Apri</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- se sei solo operatore e hai tasks =>  elenco accordion --}}
                    @if (Auth::user()->isOnlyOperator() && count($tasks) > 0)
                        <div class="row">
                            <div class="col-12">
                                {{-- <h5 class="text-info font-weight-bold mt-4 ml-3">Lavorazioni a te assegnate ancora da
                                completare:
                                {{$tasks->count()}}</h5> --}}
                                <div class="accordion" id="accordionTasks">
                                    @forelse ($tasks as $task)
                                        <div class="card">
                                            <div class="card-header" id="heading{{ $loop->index }}">
                                                <h5 class="mb-0">
                                                    @include(
                                                        'operator.components.distribution-header-button',
                                                        ['task' => $task, 'loop' => $loop]
                                                    )
                                                </h5>
                                            </div>
                                            <div id="collapse{{ $loop->index }}" class="collapse "
                                                aria-labelledby="heading{{ $loop->index }}"
                                                data-parent="#accordionTasks">
                                                <div class="card-body">
                                                    <div class="card-text">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="px-5 py-2 mb-2"
                                                                        style="background-color: #F4F7FA">
                                                                        <p class="text-secondary">Questa lavorazione fa
                                                                            parte del prodotto:
                                                                            {{ $task->getProductCode() ?? 'N.D.' }}</p>
                                                                        <div class="d-flex justify-content-between">
                                                                            <div>
                                                                                <p>Peso oro:
                                                                                    {{ $task->process->product->gold_weight ?? 'N.D.' }}
                                                                                </p>
                                                                                <p>Descrizione:
                                                                                    {{ $task->process->product->description ?? 'N.D.' }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div
                                                                    class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                    <label class="mb-0 font-weight-bold">Tipo
                                                                        pietra</label>
                                                                    <input disabled class="form-control w-100"
                                                                        value="{{ $task->process ? $task->process->stone->stoneType->code : 'N.D.' }}">
                                                                </div>
                                                                <div
                                                                    class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                    <label class="mb-0">Numero di
                                                                        pietre</label>
                                                                    <input disabled class="form-control w-100"
                                                                        type="number"
                                                                        value="{{ $task->process ? $task->process->quantity : 'N.D.' }}">
                                                                </div>
                                                                <div
                                                                    class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                    <label class="mb-0">Peso pietra</label>
                                                                    <input disabled class="form-control w-100"
                                                                        type="number" step="0.0001"
                                                                        value="{{ $task->process ? $task->process->stone->stone_weight : 'N.D.' }}">

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div
                                                                    class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                    <label class="mb-0">Codice
                                                                        classe</label>
                                                                    <input disabled class="form-control w-100"
                                                                        value="{{ $task->process ? $task->process->stone->stoneClass->description : 'N.D.' }}">
                                                                </div>
                                                                <div
                                                                    class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                    <label class="mb-0">Codice
                                                                        incastonatura</label>
                                                                    <input disabled class="form-control w-100"
                                                                        value="{{ $task->process ? $task->process->stone->settingType->code : 'N.D.' }}">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div
                                                                    class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                    <label class="mb-0">Quantità assegnata</label>
                                                                    <input disabled class="form-control w-100"
                                                                        value="{{ $task->distribution->quantity }}"
                                                                        required>
                                                                </div>
                                                                <div
                                                                    class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                    <label class="mb-0">Quantità completata</label>
                                                                    <input disabled class="form-control w-100"
                                                                        value="{{ $task->completedQuantity }}/{{ $task->distribution->quantity }}"
                                                                        required>
                                                                </div>
                                                                <div
                                                                    class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                    <label class="mb-0">Prezzario
                                                                        operatore</label>
                                                                    <input disabled class="form-control w-100"
                                                                        type="number"
                                                                        value="{{ $task->getCostoOperatore() ?? 'N.D.' }}"
                                                                        step="0.01">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        @if (!$task->distribution->order)
                                                                            <h5 class="text-secondary mt-2">Questa
                                                                                lavorazione fa parte di un ordine
                                                                                eliminato.</h5>
                                                                        @else
                                                                            {{-- Questo pulsante pulsante aprirà una modale con un input per cambiare il numero di quantity completati all'interno del task a meno che la quantità assegnata sia 1  --}}
                                                                            @if ($task->distribution->quantity > 1)
                                                                                <button type="button"
                                                                                    class="btn btn-primary shadow"
                                                                                    data-toggle="modal"
                                                                                    data-target="#changeQuantity{{ $loop->index }}">
                                                                                    Rendi completato
                                                                                </button>

                                                                                {{-- Modale per cambiare la quantità completata --}}
                                                                                <div class="modal fade"
                                                                                    id="changeQuantity{{ $loop->index }}"
                                                                                    tabindex="-1" role="dialog"
                                                                                    aria-labelledby="changeQuantityCenterTitle"
                                                                                    aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered"
                                                                                        role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title"
                                                                                                    id="modifyModalLongTitle">
                                                                                                    Aggiorna quantità
                                                                                                    completata</h5>
                                                                                                <button type="button"
                                                                                                    class="close"
                                                                                                    data-dismiss="modal"
                                                                                                    aria-label="Close">
                                                                                                    <span
                                                                                                        aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <form
                                                                                                    action="{{ route('operator.updateCompletedQuantity', compact('task')) }}"
                                                                                                    method="POST">
                                                                                                    @csrf
                                                                                                    @method('PUT')
                                                                                                    <div
                                                                                                        class="container">
                                                                                                        <div
                                                                                                            class="row">
                                                                                                            <div
                                                                                                                class="col-12">
                                                                                                                {{-- Input per aggiornare il campo completedQuantity del task che ha come massimo il numero di quantity della distribution associata al task --}}
                                                                                                                <label
                                                                                                                    for="completedQuantity">Quantità
                                                                                                                    completata</label>
                                                                                                                <input
                                                                                                                    type="number"
                                                                                                                    name="completedQuantity"
                                                                                                                    id="completedQuantity"
                                                                                                                    class="form-control"
                                                                                                                    min="0"
                                                                                                                    max="{{ $task->distribution->quantity }}"
                                                                                                                    value="{{ $task->completedQuantity }}">
                                                                                                                <button
                                                                                                                    type="submit"
                                                                                                                    class="btn btn-primary shadow my-4">Salva</button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <form
                                                                                    action="{{ route('operator.updateCompletedQuantity', compact('task')) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <div class="container">
                                                                                        <div class="row">
                                                                                            <div class="col-12">
                                                                                                <input type="hidden"
                                                                                                    name="completedQuantity"
                                                                                                    id="completedQuantity"
                                                                                                    value="1">
                                                                                                <button type="submit"
                                                                                                    class="btn btn-primary shadow my-4">Rendi
                                                                                                    completato</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                    {{ $tasks->links() }}
                                </div>
                            </div>
                        </div>

                        {{-- se sei solo operatore e non hai tasks => messaggio --}}
                    @elseif (Auth::user()->isOnlyOperator() && !count($tasks) > 0)
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-info font-weight-bold mt-4 ml-3">Nessuna lavorazione ancora da
                                    completare</h5>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

@push('scripts')
    <script>
        $(document).ready(function() {
            setInterval(() => {
                let years = new Date().getFullYear();
                let months = new Date().getMonth() + 1;
                let days = new Date().getDate();
                let hours = new Date().getHours();
                let minutes = new Date().getMinutes();
                let seconds = new Date().getSeconds();

                $(".year").html((years < 10 ? "0" : "") + years)
                $(".month").html((months < 10 ? "0" : "") + months)
                $(".day").html((days < 10 ? "0" : "") + days);
                $(".hours").html((hours < 10 ? "0" : "") + hours);
                $(".min").html((minutes < 10 ? "0" : "") + minutes);
                $(".sec").html((seconds < 10 ? "0" : "") + seconds);
                $('.clock-system').removeClass('d-none');
            }, 1000);

        });
    </script>

    {{-- Filtro codice cliente --}}
    <script>
        $(document).ready(function() {
            $('#filtro-codice-cliente').on('keyup', function() {
                var filtro = $(this).val();
                // Applica il filtro alla colonna del codice cliente
                var table = $('.admin-table').DataTable();
                table.column('[name="codice_cliente"]').search(filtro).draw();
            });
        });
    </script>
@endpush

@endsection
