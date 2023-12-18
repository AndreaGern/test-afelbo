@extends('layout')
@section('content')
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <!-- [ breadcrumb ] end -->
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
                                                    <div class="col-12 col-lg-8">
                                                        <div class="d-flex justify-content-between mb-4">
                                                            <h5 class="font-weight-bold">MODIFICA ORDINE -
                                                                {{ $commission->getCommissionCode() }}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-lg-4">
                                                        <div class="p-2 importi-commesse ">
                                                            <h6
                                                                class="font-weight-bold text-white d-flex justify-content-between align-items-center">
                                                                importo totale <span
                                                                    style="font-size: larger;">{{ $commission->importo_totale }}
                                                                    <span>€</span></span></h6>
                                                            <h6
                                                                class="font-weight-bold text-white d-flex justify-content-between align-items-center">
                                                                già pagato <span
                                                                    style="font-size: larger;">{{ $commission->getCommissionMovements() }}
                                                                    <span>€</span></span></h6>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <form action="{{ route('commission.update', compact('commission')) }}"
                                                        method="post">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="row">
                                                            <div
                                                                class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                <label class="mb-0" for="code">Codice ordine</label>
                                                                <input disabled class="form-control w-100" type="text"
                                                                    value="{{ $commission->getCommissionCode() }}">
                                                            </div>
                                                            <div
                                                                class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                <label class="mb-0" for="description">Codice
                                                                    cliente</label>
                                                                <input disabled class="form-control w-100" type="text"
                                                                    value="{{ $commission->client->code }}">
                                                            </div>
                                                            <div
                                                                class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                <label class="mb-0" for="description">Nome cliente</label>
                                                                <input disabled class="form-control w-100" type="text"
                                                                    value="{{ $commission->client->name }}">
                                                            </div>
                                                            <div
                                                                class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                <label class="mb-0" for="description">Data di
                                                                    creazione</label>
                                                                <input disabled class="form-control w-100" type="text"
                                                                    value="{{ $commission->created_at->format('d/m/Y') }}">
                                                            </div>
                                                            <div
                                                                class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                <label class="mb-0" for="deadline">Data di
                                                                    consegna</label>
                                                                <input type="date" class="form-control shadow"
                                                                    id="deadline"
                                                                    value="{{ $commission->deadline->format('Y-m-d') }}"
                                                                    name="deadline">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div
                                                                class="col-sm d-flex flex-column align-items-start form-group">
                                                                <label class="mb-0"
                                                                    for="commission_description">Descrizione</label>
                                                                <input type="text" class="form-control shadow"
                                                                    id="commission_description"
                                                                    value="{{ $commission->commission_description }}"
                                                                    name="commission_description">
                                                            </div>
                                                        </div>
                                                        <div class="row text-right">
                                                            <div class="col-sm">
                                                                <button
                                                                    class="btn btn-warning shadow"type="submit">Aggiorna</button>

                                                            </div>
                                                        </div>
                                                    </form>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a href="{{ route('order.select-db-product', compact('commission')) }}"
                                                                class="btn btn-primary shadow">Seleziona prodotto dal DB</a>
                                                            <a href="{{ route('order.create', compact('commission')) }}"
                                                                class="btn btn-primary shadow">Aggiungi nuovo prodotto</a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card-block table-border-style">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Codice ordine</th>
                                                                                <th>Codice Prodotto</th>
                                                                                <th>Descrizione</th>
                                                                                <th>Quantità</th>
                                                                                <th>Quantità non ripartita</th>
                                                                                <th>Importo ordine</th>
                                                                                <th>Ultimato</th>
                                                                                <th>Consegnato</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @forelse ($commission->products as $product)
                                                                                <tr>
                                                                                    <td>{{ $product->order->code }}</td>
                                                                                    <td class="text-uppercase">
                                                                                        {{ $product->code }}</td>
                                                                                    <td>{{ $product->description }}</td>
                                                                                    <td>{{ $product->order->quantity }}
                                                                                    </td>
                                                                                    <td>{{ $product->order->quantityToDistribute == 0 ? 'Totalmente ripartito' : $product->order->quantityToDistribute }}
                                                                                    </td>
                                                                                    {{-- <td>
                                                                                {{$product->order->importo_unitario}}€
                                                                            </td> --}}
                                                                                    <td>{{ $product->order->importo_totale }}€
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($product->order->completed)
                                                                                            <i
                                                                                                class="fa-solid fa-check"></i>
                                                                                        @elseif($product->order->completed == false && $product->order->getCompletedProducts() > 0)
                                                                                            <p>{{ $product->order->getCompletedProducts() }}
                                                                                            </p>
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-solid fa-xmark"></i>
                                                                                        @endif

                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($product->order->isDelivered())
                                                                                            <i
                                                                                                class="fa-solid fa-check"></i>
                                                                                        @elseif(!$product->order->isDelivered() && $product->order->getDeliveredProductsQuantity() > 0)
                                                                                            <p>{{ $product->order->getDeliveredProductsQuantity() }}
                                                                                            </p>
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-solid fa-xmark"></i>
                                                                                        @endif
                                                                                    <td
                                                                                        class="d-flex justify-content-lg-end">
                                                                                        @if ($product->order->quantity == 1)
                                                                                            <form id="quantity-one-form"
                                                                                                action="{{ route('order.deliverProducts', ['commission' => $product->order->commission, 'order' => $product->order]) }}"
                                                                                                method="POST" hidden>
                                                                                                @csrf
                                                                                                @method('PUT')
                                                                                                {{-- Inviamo solo il prodotto che é stato già distribuito tra gli operatori --}}
                                                                                                <input type="number"
                                                                                                    name="deliveredProducts"
                                                                                                    hidden id="quantity-one"
                                                                                                    min="{{ $product->order->getDeliveredProductsQuantity() }}"
                                                                                                    max="{{ $product->order->getDistributedProductsQuantity() }}"
                                                                                                    value="{{ $product->order->getDistributedProductsQuantity() }}">

                                                                                            </form>
                                                                                            <a href="#"
                                                                                                class="btn btn-success btn-sm shadow"
                                                                                                onclick="event.preventDefault(); document.getElementById('quantity-one-form').submit();">Consegna
                                                                                            </a>
                                                                                        @else
                                                                                            <div class="btn btn-success btn-sm shadow"
                                                                                                data-toggle="modal"
                                                                                                data-target="#deliverOrderModal{{ $product->order->id }}">
                                                                                                Consegna
                                                                                            </div>
                                                                                        @endif
                                                                                        <a class="btn btn-primary btn-sm shadow"
                                                                                            href="{{ route('distribution.create', ['order' => $product->order]) }}">Ripartizioni</a>
                                                                                        <a class="btn btn-warning btn-sm shadow"
                                                                                            href="{{ route('order.edit', ['commission' => $commission, 'order' => $product->order]) }}">
                                                                                            <i
                                                                                                class="fa-solid fa-pen-to-square"></i>
                                                                                        </a>
                                                                                        <a class="btn btn-info btn-sm shadow"
                                                                                            href="{{ route('order.show', ['commission' => $commission, 'order' => $product->order]) }}">
                                                                                            <i class="fa-solid fa-eye"></i>
                                                                                        </a>
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-danger shadow"
                                                                                            data-toggle="modal"
                                                                                            data-target="#deleteOrder{{ $product->order->id }}">
                                                                                            <i
                                                                                                class="fa-solid fa-trash-can"></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>

                                                                                {{-- Modal per la consegna di un ordine --}}
                                                                                <div class="modal fade"
                                                                                    id="deliverOrderModal{{ $product->order->id }}"
                                                                                    tabindex="-1" role="dialog"
                                                                                    aria-labelledby="deliverOrderModalCenterTitle"
                                                                                    aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered"
                                                                                        role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title"
                                                                                                    id="modifyModalLongTitle">
                                                                                                    Scegli quanti prodotti
                                                                                                    consegnare</h5>
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
                                                                                                    action="{{ route('order.deliverProducts', ['commission' => $product->order->commission, 'order' => $product->order]) }}"
                                                                                                    method="POST">
                                                                                                    @csrf
                                                                                                    @method('PUT')
                                                                                                    <div class="container">
                                                                                                        <div
                                                                                                            class="row">
                                                                                                            {{-- Quantity of products in the order to be delivered. The maximum value is $product->order->getDistributedProductsQuantity. The starting value is the $product->order->deliveredProducts --}}
                                                                                                            <input
                                                                                                                class="form-control my-3 shadow"
                                                                                                                type="number"
                                                                                                                name="deliveredProducts"
                                                                                                                id="quantity"
                                                                                                                min="{{ $product->order->getDeliveredProductsQuantity() }}"
                                                                                                                max="{{ $product->order->getDistributedProductsQuantity() }}"
                                                                                                                value="{{ $product->order->getDeliveredProductsQuantity() }}">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <button type="submit"
                                                                                                        class="btn btn-primary shadow">Salva</button>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Modal per l'eliminazione dell'ordine -->
                                                                                <div class="modal fade"
                                                                                    id="deleteOrder{{ $product->order->id }}"
                                                                                    tabindex="-1" role="dialog"
                                                                                    aria-labelledby="deleteOrder{{ $product->order->id }}Title"
                                                                                    aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered"
                                                                                        role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title">
                                                                                                    Confermi
                                                                                                    di voler eliminare
                                                                                                    <strong
                                                                                                        class="font-weight-bold">l'ordine
                                                                                                        {{ $product->order->code }}</strong>?
                                                                                                    @if ($product->order->hasDistributions())
                                                                                                        <span
                                                                                                            class="text-danger font-weight-bold">ATTENZIONE!
                                                                                                            Quest'ordine è
                                                                                                            già stato
                                                                                                            ripartito! Sei
                                                                                                            davvero
                                                                                                            sicuro di
                                                                                                            volerlo
                                                                                                            eliminare?</span>
                                                                                                    @endif
                                                                                                </h5>
                                                                                                <button type="button"
                                                                                                    class="close"
                                                                                                    data-dismiss="modal"
                                                                                                    aria-label="Close">
                                                                                                    <span
                                                                                                        aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div
                                                                                                class="modal-body d-flex justify-content-end">
                                                                                                <form
                                                                                                    action="{{ route('order.delete', ['commission' => $commission, 'order' => $product->order]) }}"
                                                                                                    method="post">
                                                                                                    @csrf
                                                                                                    @method('DELETE')
                                                                                                    <button
                                                                                                        class="btn btn-danger shadow"
                                                                                                        type="submit">Elimina</button>
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
    <!-- [ Main Content ] end -->
@endsection

<script>
    let checkbox = $('input.checkbox_check').is(':checked');
</script>
