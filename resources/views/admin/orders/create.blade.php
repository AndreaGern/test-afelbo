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
                                        <h5 class="font-weight-bold">AGGIUNGI NUOVO PRODOTTO</h5>
                                        <div class="d-flex flex-column align-items-end">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <form action="{{route('order.store',compact('commission'))}}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div
                                                            class="col-12 d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="description">Descrizione</label>
                                                            <input value="{{old('description')}}"
                                                                class="form-control w-100" name="description"
                                                                type="text">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-md-3 form-group">
                                                            <label class="mb-0" for="gold_weight">Peso oro</label>
                                                            <input value="{{old('gold_weight')}}"
                                                                class="form-control w-100" name="gold_weight"
                                                                type="number" step="0.00001" required>
                                                        </div>
                                                        <div class="col-12 col-md-3 form-group">
                                                            <label class="mb-0"
                                                                for="product_quantity_order">Quantità</label>
                                                            <input value="{{old('product_quantity_order')}}"
                                                                class="form-control w-100" name="product_quantity_order"
                                                                type="number" min="1">
                                                        </div>
                                                        <div class="col-12 col-md-6 form-group">
                                                            <label class="mb-0" for="type">Codice tipo di
                                                                prodotto</label>
                                                            <input value="{{old('type')}}" class="form-control w-100 text-uppercase"
                                                                name="type" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="row my-3">
                                                        <div id="tabs-section" class="col-12">
                                                            <ul class="nav nav-tabs" id="addProcessTabs" role="tablist">
                                                                <li class="nav-item mr-2" role="presentation"><button
                                                                        class="text-light nav-link btn-info add-tab"
                                                                        id="addProcessTab" type="button">AGGIUNGI
                                                                        LAVORAZIONE <span
                                                                            class="font-weight-bold">+</span></button>
                                                                </li>
                                                            </ul>

                                                            <div class="tab-content w-100" id="processContents">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-info mt-3">Aggiungi</button>
                                                    </div>
                                                </form>
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
@endsection