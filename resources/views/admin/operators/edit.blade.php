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
                                            <h5 class="font-weight-bold">MODIFICA OPERATORE: {{$operator->user->name}}</h5>
                                            <hr>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-4 align-items-start form-group ">
                                                <form action="{{route('operator.update', compact('operator'))}}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class=" mt-3  form-group ">
                                                        <label class="mb-0" for="name">Nome e cognome</label>
                                                        <input class="form-control w-100 shadow" type="text" required name="name" value="{{$operator->user->name}}">
                                                    </div>
                                                    <div class=" mt-3  form-group ">
                                                        <label class="mb-0" for="email">Email o Username</label>
                                                        <input class="form-control w-100 shadow" type="text" required name="email" value="{{$operator->user->email}}">
                                                    </div>
                                                    <div class=" mt-3  form-group ">
                                                        <label class="mb-0" for="code">Codice</label>
                                                        <input class="form-control w-100 shadow" type="text" required name="code" value="{{$operator->code}}">
                                                    </div>
                                                    <div class=" mt-3  form-group ">
                                                        <label class="mb-0" for="workstation">Postazione di lavoro</label>
                                                        <input class="form-control w-100 shadow" type="text" required name="workstation" value="{{$operator->workstation}}">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary shadow">Salva</button>
                                                </form>
                                                <hr>
                                                <form action="{{route('operator.resetPassword', compact('operator'))}}" method="post">
                                                @method('PUT')
                                                @csrf
                                                    <button type="submit" class="btn btn-warning shadow">Reset Password</button>
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
<!-- [ Main Content ] end -->
@endsection