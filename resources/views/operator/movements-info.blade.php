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
                                        <div class="d-flex justify-content-between">
                                            <h5 class="font-weight-bold">{{$operator->user->name}}</h5>
                                            <div class="d-flex">
                                                <div class="d-flex align-items-baseline mr-5">
                                                    <p>Codice</p>
                                                    <h6 class="pl-2 font-weight-bold">{{$operator->code}}</h6>
                                                </div>
                                                <div class="d-flex align-items-baseline mr-5">
                                                    <p>Banco</p>
                                                    <h6 class="pl-2 font-weight-bold">{{$operator->workstation}}</h6>
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
                                                                    {{$operator->accountability->payments_sum ?? 0}}
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
                                                                    {{$operator->accountability->piecework ?? 0}}</h3>
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
                                                                    {{$operator->accountability->unpaid ?? 0}}</h3>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block table-border-style">
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
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($operator->movements as $movement)
                                                        <tr>
                                                            <td>{{$movement->created_at->format('d/m/y')}}</td>
                                                            <td>{{$movement->causal}}</td>
                                                            <td>{{$movement->amount}} €</td>
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

<script>
    let periodo = document.getElementById('periodo');
        let totale = document.getElementById('totale');
        let filterdate = document.getElementById('filterdate');
        periodo.addEventListener('click', function () {
            filterdate.classList.add("showfilter")
            filterdate.classList.remove("hidefilter")
        })
        totale.addEventListener('click', function () {
            filterdate.classList.remove("showfilter")
            filterdate.classList.add("hidefilter")
        })
</script>
<style>
    .hidefilter {
        opacity: 0;
        z-index: -999;
        transition: all 0.5s ease-in-out;

    }

    .showfilter {
        opacity: 1;
        z-index: 100;
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection