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
                                                <div class="col-12 col-lg-8 mb-3">
                                                    <h5 class="font-weight-bold">SCHEDARIO PRODOTTI</h5>
                                                </div>
                                                <div class="col-12 col-lg-4 d-flex justify-content-lg-end justify-content-center">
                                                    <a class="btn btn-info" href="{{route('product.create')}}">Aggiungi nuovo prodotto</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-block table-border-style">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Codice</th>
                                                        <th>Descrizione</th>
                                                        <th>Peso<br>oro</th>
                                                        <th>Tipo di<br>prodotto</th>
                                                        <th>Tipi di<br>pietre</th>
                                                        <th>Classi di<br>pietre</th>
                                                        <th>Tipi di<br>incastonature</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($products as $product)
                                                    <tr>
                                                        <td class="text-uppercase">{{$product->code}}</td>
                                                        <td class="text-wrap">{{$product->description}}</td>
                                                        <td>{{$product->gold_weight}}g</td>
                                                        <td class=" text-uppercase">{{$product->type}}</td>
                                                        <td>{{$product->stones->count()}}</td>
                                                        <td class=" text-uppercase">{{$product->getStoneClassesCodes()}}</td>
                                                        <td class=" text-uppercase">{{$product->getSettingTypesCodes()}}</td>
                                                        <td class="text-right"><span class="delete-row"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#productDeleteModal{{$product->id}}">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button></span></td>
                                                    </tr>
                                                    
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="productDeleteModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="productDeleteModal{{$product->id}}Title" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Confermi di voler eliminare <strong class="font-weight-bold text-uppercase">{{$product->code}}</strong>?</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body d-flex justify-content-end">
                                                                    <form action="{{route('product.delete', compact('product'))}}" method="post">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-danger shadow">Elimina</button>
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
                        <!-- [ Main Content ] end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection