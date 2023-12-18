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
                                    <div class="d-flex justify-content-between">
                                        <h5 class="font-weight-bold">MODIFICA CLIENTE {{$client->name}}</h5> 
                                    </div>
                                    <div class="d-flex w-100">
                                        <form action="{{route('client.update', compact('client'))}}" method="POST" class="w-100">
                                            @method('PUT')
                                            @csrf
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="code">Codice cliente</label>
                                                            <input required class="form-control w-100" type="text" name="code" value="{{$client->code}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="name">Nome</label>
                                                            <input class="form-control w-100" type="text" name="name" value="{{$client->name}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="phone">Telefono</label>
                                                            <input class="form-control w-100" type="text" name="phone" value="{{$client->phone}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="email">Email</label>
                                                            <input class="form-control w-100" type="text" name="email" value="{{$client->email}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="address">Indirizzo</label>
                                                            <input class="form-control w-100" type="text" name="address" value="{{$client->address}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="cap">CAP</label>
                                                            <input class="form-control w-100" type="text" name="cap" value="{{$client->cap}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="city">Comune</label>
                                                            <input class="form-control w-100" type="text" name="city" value="{{$client->city}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="province">Provincia</label>
                                                            <input class="form-control w-100" type="text" name="province" value="{{$client->province}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="partita_iva">Partita IVA</label>
                                                            <input class="form-control w-100" type="text" name="partita_iva" value="{{$client->partita_iva}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="website">Web URL</label>
                                                            <input class="form-control w-100" type="text" name="website" value="{{$client->website}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="codice_fiscale">Codice Fiscale</label>
                                                            <input class="form-control w-100" type="text" name="codice_fiscale" value="{{$client->codice_fiscale}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary shadow">Salva</button>
                                            </div>                                            
                                            
                                        </form>
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