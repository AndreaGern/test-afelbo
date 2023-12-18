<div class="container-fluid">
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ Hover-table ] start -->
        <div class="col-xl-12">

            <div class="card">
                <div class="card-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-lg-8 mb-4">

                                <div wire:loading.delay="updateUnitaries, updatePartials, newDistributions, deletedDistributionAndTasks"
                                    class="overlay-container">
                                    <div class="loader-distribution">
                                        <i class="fas fa-spinner fa-spin"></i> Caricamento in corso...
                                    </div>
                                </div>

                                <div class="d-flex flex-column align-items-start">
                                    <h5 class="font-weight-bold text-uppercase">RIPARTIZIONE - ORDINE
                                        {{ $order->code }}
                                    </h5>
                                    <a href="{{ route('commission.edit', ['commission' => $order->commission]) }}"
                                        class="shadow btn btn-sm btn-primary p-1 mt-3" href=""> <i
                                            class="fa-solid fa-arrow-left"></i> Torna indietro</a>
                                    <!-- Overlay -->
                                    <div class="message-overlay" wire:poll.visible="closeMessage"
                                        style="{{ $showMessageOverlay ? '' : 'display: none;' }}">
                                        <div class="px-4 py-2 message-container">
                                            @if (session()->has('message'))
                                                <div class="alert alert-successo text-shadow">
                                                    {{ session('message') }}
                                                </div>
                                            @endif
                                            @if ($errors->any())
                                                @foreach ($errors->all() as $error)
                                                    <div class="alert alert-errore">
                                                        {{ $error }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="p-2 px-4 importi-commesse">
                                    <h6
                                        class="font-weight-bold text-white d-flex justify-content-between align-items-center">
                                        Codice prodotto
                                        <span class="ml-3 text-uppercase"
                                            style="font-size: larger;">{{ $order->product->code }}</span>
                                    </h6>
                                    <h6
                                        class="font-weight-bold text-white d-flex justify-content-between align-items-center">
                                        Quantità da ripartire <span class="ml-3" style="font-size: larger;"><span
                                                id="quantityToDistribute">{{ $toDistribute }}</span>/{{ $order->quantity }}</span>
                                    </h6>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger my-2 p-2 rounded d-none" id="errorsCard">
                            </div>
                            <form wire:submit.prevent="newDistribution">
                                <div class="row" id="firstLevel">
                                    <div class="col ">
                                        <div class="mb-3 form-group">
                                            <label for="order_quantity">Quantità da assegnare</label>
                                            <input id="quantityToAssign" name="quantityToAssign" type="number"
                                                min="1" max="{{ $order->quantity }}"
                                                class="form-control w-lg-50 shadow" required
                                                wire:model="quantityToAssign">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md">
                                        <div class="mb-3 form-group">
                                            <label class="mb-0">Tipologia di ripartizione</label>
                                            <div class="mb-3 p-2">
                                                <div class="form-check text-dark">
                                                    <input class="form-check-input " type="radio"
                                                        name="distributionType" value="unitary" id="unitaryDistribution"
                                                        required wire:model="distributionType">
                                                    <label class="form-check-label" for="unitaryDistribution">
                                                        Ripartizione unitaria
                                                    </label>
                                                </div>
                                                <div class="form-check text-dark">
                                                    <input class="form-check-input " type="radio"
                                                        name="distributionType" value="partial" id="partialDistribution"
                                                        required wire:model="distributionType">
                                                    <label class="form-check-label" for="partialDistribution">
                                                        Ripartizione parziale
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md">
                                        <div class="m-3 text-right">
                                            <button type="submit" class="btn btn-info btn-sm shadow"
                                                id="createDistributionButton">Aggiungi ripartizione</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form action="{{ route('distribution.store', compact('order')) }}" method="POST"
                                id="createDistributionForm">
                                @csrf
                                @forelse ($unitaries as $distribution)
                                    <div id="distributionRow{{ $loop->index }}" class="row mb-3 pb-3 border-bottom">
                                        <div class="col-12 col-md-2">
                                            <div class="mb-3 form-group">
                                                <label for="quantityAssigned{{ $loop->index }}">Quantità
                                                    assegnata</label>
                                                <input class="form-control  py-3" readonly
                                                    id="quantityAssigned{{ $loop->index }}"
                                                    name="unitaryDistribution[distribution-{{ $loop->index }}][quantityAssigned]"
                                                    value="{{ $distribution->quantity }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="mb-3 form-group">
                                                <label class="mb-0">Alla postazione</label>
                                                <div class="mb-3 p-2">
                                                    <select required class="custom-select shadow"
                                                        name="unitaryDistribution[distribution-{{ $loop->index }}][operatorCode]"
                                                        wire:model="unitaries.{{ $loop->index }}.operator_id"
                                                        onfocus="this.dataset.prevValue = this.value"
                                                        wire:change="onUnitariesOperatorChange($event.target.value, $event.target.dataset.prevValue, {{ $loop->index }})">
                                                        <option value=""
                                                            aria-placeholder="seleziona un'operatore">Seleziona
                                                            un'operatore</option>
                                                        @foreach ($operators as $operator)
                                                            <option value="{{ $operator->id }}"
                                                                @if ($operator->id == $distribution->getUnitaryOperatorId()) selected @endif>
                                                                Postazione {{ $operator->workstation }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <div class="mb-3 form-group">
                                                <label for="costoAlCliente{{ $loop->index }}">Costo al
                                                    cliente</label>
                                                <input class="form-control  py-3" readonly
                                                    id="quantityAssigned{{ $loop->index }}" disabled
                                                    name="unitaryDistribution[distribution-{{ $loop->index }}][costoAlCliente]"
                                                    value="{{ $distribution->getCostoAlCliente() }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <div class="mb-3 form-group">
                                                <label for="prezzarioUser{{ $loop->index }}">Costo
                                                    all'operatore</label>
                                                <input class="form-control  py-3" readonly
                                                    id="quantityAssigned{{ $loop->index }}" disabled
                                                    name="unitaryDistribution[distribution-{{ $loop->index }}][prezzarioUser]"
                                                    value="{{ $distribution->getCostoAllOperatore() }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="m-3 text-right">
                                                <button type="button" class="btn btn-danger btn-sm shadow mt-3"
                                                    id="deleteUnitaryDistribution{{ $loop->index }}"
                                                    wire:click="$emitSelf('deletedDistributionAndTasks', {{ $distribution }})">Elimina
                                                    Ripartizione <i class="fa-solid fa-trash-can"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                @endforelse

                                @forelse ($partials as $distribution)

                                    <div id="distributionRow999{{ $loop->index }}"
                                        class="row mb-3 pb-3 border-bottom">
                                        <div class="col-12 col-md-8">
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    @forelse ($distribution->tasks as $task)
                                                        <a class="nav-item nav-link @if ($loop->first) active @endif"
                                                            id="nav-home-tab{{ $loop->index }}" data-toggle="tab"
                                                            href="#partialDistribution{{ $loop->parent->index }}Task{{ $loop->index }}"
                                                            role="tab" aria-controls="nav-home"
                                                            aria-selected="true">{{ $task->process ? $task->process->stone->stoneType->code : 'N.D.' }}</a>
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </nav>

                                            <div class="tab-content"
                                                id="partialDistributionTabContent999{{ $loop->index }}">
                                                @forelse ($distribution->tasks as $task)
                                                    <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                        id="partialDistribution{{ $loop->parent->index }}Task{{ $loop->index }}"
                                                        role="tabpanel" aria-labelledby="nav-home-tab">
                                                        <div class="row">
                                                            <div class="col-12 col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="stoneQuantity">Numero di pietre:
                                                                    </label><span>
                                                                        {{ $task->process ? $task->process->quantity : 'N.D.' }}</span>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label
                                                                        for="assignedQuantityDistribution{{ $loop->parent->index }}Task{{ $loop->index }}">Quantità
                                                                        assegnata</label>
                                                                    <input class=" form-control"
                                                                        id="assignedQuantity999Distribution{{ $loop->parent->index }}Task{{ $loop->index }}"
                                                                        type="number" readonly
                                                                        value="{{ $task->distribution->quantity }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <div class="mb-3 form-group">
                                                                    <label for="settingType">Tipo incastonatura:
                                                                    </label><span>
                                                                        {{ $task->process ? $task->process->stone->settingType->code : 'N.D.' }}</span>
                                                                </div>
                                                                <div class="mb-3 form-group">
                                                                    <label for="stoneType">Tipo pietra:</label><span>
                                                                        {{ $task->process ? $task->process->stone->stoneType->code : 'N.D.' }}</span>
                                                                </div>
                                                                <div class="mb-3 form-group">
                                                                    <label for="settingType">Classe
                                                                        pietra:</label><span>
                                                                        {{ $task->process ? $task->process->stone->stoneClass->code : 'N.D.' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12 col-md-6">
                                                                <div class="mb-3 form-group">
                                                                    <label
                                                                        for="costoAlCliente{{ $loop->index }}">Costo
                                                                        al
                                                                        cliente</label>
                                                                    <input class="form-control  py-3" readonly
                                                                        id="quantityAssigned{{ $loop->index }}"
                                                                        disabled
                                                                        name="unitaryDistribution[distribution-{{ $loop->index }}][costoAlCliente]"
                                                                        value="{{ $task->getCostoAlCliente() }}">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 form-group">
                                                                <label for="prezzarioUser{{ $loop->index }}">Costo
                                                                    all'operatore</label>
                                                                <input class="form-control  py-3" readonly
                                                                    id="quantityAssigned{{ $loop->index }}" disabled
                                                                    name="unitaryDistribution[distribution-{{ $loop->index }}][prezzarioUser]"
                                                                    value="{{ $task->getCostoOperatore() }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label for="operatorCode">Alla postazione</label>
                                                                    <select class="custom-select"
                                                                        wire:model="partials.{{ $loop->parent->index }}.tasks.{{ $loop->index }}.operator_id"
                                                                        onfocus="this.dataset.prevValue = this.value"
                                                                        wire:change="onPartialOperatorChange($event.target.value, $event.target.dataset.prevValue, {{ $loop->parent->index }},
                                                                        {{ $loop->index }})">
                                                                        <option value=""
                                                                            aria-placeholder="seleziona un'operatore">
                                                                            Seleziona un'operatore</option>
                                                                        @foreach ($operators as $operator)
                                                                            <option value="{{ $operator->id }}"
                                                                                @if ($task->operator_id == $operator->id) selected @endif>
                                                                                Postazione
                                                                                {{ $operator->workstation }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <div class="m-3 text-right">
                                                <button type="button" class="btn btn-danger btn-sm shadow mt-3"
                                                    id="deletePartialDistribution{{ $loop->index }}Task"
                                                    wire:click="$emitSelf('deletedDistributionAndTasks', {{ $distribution }})">Elimina
                                                    Ripartizione
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
