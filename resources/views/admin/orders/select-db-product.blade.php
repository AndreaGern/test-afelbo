@extends('layout')
@section('content')
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
                                        <div class="d-flex justify-content-between">
                                            <h5 class="font-weight-bold">SELEZIONA PRODOTTO DAL DB</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12"> 
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card-block table-border-style">
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Codice Prodotto</th>
                                                                            <th>Descrizione</th>
                                                                            <th>Costo al cliente</th>
                                                                            <th>Costo all'operatore</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse ($products as $product)
                                                                            <tr>
                                                                                <td class=" text-uppercase">{{$product->code}}</td>
                                                                                <td>{{$product->description}}</td>
                                                                                <td>{{$product->stones_sum_client_cost}}</td>
                                                                                <td>{{$product->stones_sum_prezzariouser}}</td>
                                                                                <td class="text-right">
                                                                                        <a class="btn btn-primary" href="{{route('order.create-from-db',compact('commission', 'product'))}}">Aggiungi all'ordine</a>
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <p>Nessun ordine presente</p>
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
@endsection