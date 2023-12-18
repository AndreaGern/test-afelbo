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
                                <div class="col-12 col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="font-weight-bold text-uppercase">Storico delle ripartizioni
                                                assegnate all'operatore {{ $operator->name }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    @if ($tasks)
                                                        <form action="{{ route('operator.changeDateTasksIndex') }}"
                                                            method="get">
                                                            @csrf
                                                            <h6 class="mb-3 font-weight-bold">Filtra per data di creazione
                                                            </h6>
                                                            <div class="row text-left">
                                                                <div class="col-12 d-lg-flex">
                                                                    <div class="mb-3">
                                                                        <label for="minDate" class="form-label">Data
                                                                            minima</label>
                                                                        <input type="date" class="form-control"
                                                                            id="minDate" name="minDate"
                                                                            value="{{ $minDate ?? '' }}">
                                                                    </div>
                                                                    <div class="mb-3 mx-lg-3">
                                                                        <label for="maxDate" class="form-label">Data
                                                                            massima</label>
                                                                        <input type="date" class="form-control"
                                                                            id="maxDate" name="maxDate"
                                                                            value="{{ $maxDate ?? '' }}">
                                                                    </div>
                                                                    <input type="hidden" name="page"
                                                                        value="{{ $page }}">
                                                                    <div class="mb-3 text-right">
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm mt-4">Filtra</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <form
                                                            action="{{ route('operator.filterDistributions', compact('operator')) }}"
                                                            method="get" class="my-4">
                                                            @csrf
                                                            {{-- // due radio button uno value completed e l'altro value not-completed --}}
                                                            <h6 class="mb-3 font-weight-bold">Filtra per stato</h6>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="filter1" name="filter"
                                                                    class="custom-control-input" value="all"
                                                                    @if ($filter == 'all') checked @endif>
                                                                <label class="custom-control-label"
                                                                    for="filter1">Tutte</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="filter2" name="filter"
                                                                    class="custom-control-input" value="not-completed"
                                                                    @if ($filter == 'not-completed') checked @endif>
                                                                <label class="custom-control-label" for="filter2">Non
                                                                    completate</label>
                                                            </div>
                                                            <button
                                                                class="btn btn-primary shadow btn-sm px-4 py-2">Filtra</button>
                                                        </form>
                                                        <div class="accordion" id="accordionTasks">
                                                            @forelse ($tasks as $task)
                                                                <div class="card">
                                                                    <div class="card-header"
                                                                        id="heading{{ $loop->index }}">
                                                                        <button
                                                                            class="btn btn-link @if ($task->completed) text-success @else text-dark @endif"
                                                                            type="button" data-toggle="collapse"
                                                                            data-target="#collapse{{ $loop->index }}"
                                                                            aria-expanded="true" aria-controls="collapse">
                                                                            Cliente: {{ $task->getClientCode() }} -
                                                                            Ordine:
                                                                            {{ $task->distribution->order ? $task->distribution->order->code : 'N.D.' }}
                                                                            -
                                                                            Quantità:
                                                                            {{ $task->distribution->quantity }} - <span
                                                                                class="font-weight-bold">{{ $task->completed ? 'Completata' : 'Non completata' }}</span>
                                                                            - Creata il
                                                                            {{ $task->created_at->format('d/m/Y') }}

                                                                        </button>
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
                                                                                                <p
                                                                                                    class="text-secondary mb-0">
                                                                                                    Codice prodotto:
                                                                                                    <b>{{ $task->getProductCode() ?? 'N.D.' }}</b>
                                                                                                </p>
                                                                                                <div
                                                                                                    class="d-flex justify-content-between">
                                                                                                    <div>
                                                                                                        <p class="mb-0">
                                                                                                            Descrizione:
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
                                                                                            <label class="mb-0">Tipo
                                                                                                pietra</label>
                                                                                            <input disabled
                                                                                                class="form-control w-100"
                                                                                                value="{{ $task->process ? $task->process->stone->stoneType->code : 'N.D.' }}">
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                            <label class="mb-0">Numero di
                                                                                                pietre</label>
                                                                                            <input disabled
                                                                                                class="form-control w-100"
                                                                                                type="number"
                                                                                                value="{{ $task->process ? $task->process->quantity : 'N.D.' }}">
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                            <label class="mb-0">Peso
                                                                                                pietra</label>
                                                                                            <input disabled
                                                                                                class="form-control w-100"
                                                                                                type="number"
                                                                                                step="0.0001"
                                                                                                value="{{ $task->process ? $task->process->stone->stone_weight : 'N.D.' }}">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div
                                                                                            class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                            <label class="mb-0">Codice
                                                                                                classe</label>
                                                                                            <input disabled
                                                                                                class="form-control w-100"
                                                                                                value="{{ $task->process ? $task->process->stone->stoneClass->description : 'N.D.' }}">
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                            <label class="mb-0">Codice
                                                                                                incastonatura</label>
                                                                                            <input disabled
                                                                                                class="form-control w-100"
                                                                                                value="{{ $task->process ? $task->process->stone->settingType->code : 'N.D.' }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <hr>
                                                                                    <div class="row">
                                                                                        <div
                                                                                            class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                            <label class="mb-0">Quantità
                                                                                                assegnata
                                                                                            </label>
                                                                                            <input disabled
                                                                                                class="form-control w-100"
                                                                                                value="{{ $task->distribution->quantity }}"
                                                                                                required>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                            <label class="mb-0">Quantità
                                                                                                completata </label>
                                                                                            <input disabled
                                                                                                class="form-control w-100"
                                                                                                value="{{ $task->completedQuantity }}/{{ $task->distribution->quantity }}"
                                                                                                required>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                            <label class="mb-0">Prezzario
                                                                                                operatore</label>
                                                                                            <input disabled
                                                                                                class="form-control w-100"
                                                                                                type="number"
                                                                                                value="{{ $task->getCostoOperatore() ?? 'N.D.' }}"
                                                                                                step="0.01">
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            @empty
                                                                <h3>Nessuna ripartizione a te assegnata al momento</h3>
                                                            @endforelse
                                                            {{ $tasks->links() }}
                                                        </div>
                                                    @else
                                                        <h5>Nessuna ripartizione a te attualmente assegnata</h5>
                                                    @endif
                                                </div>
                                            </div>
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
@endsection
