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
                                            <h5 class="font-weight-bold">MODIFICA TIPO DI PIETRA {{$stoneType->description}}</h5>
                                            
                                        </div>
                                        <div class="d-flex w-100">
                                            <form action="{{route('stoneType.update', compact('stoneType'))}}" method="POST" class="w-100">
                                                @method('PUT')
                                                @csrf
                                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                    <label class="mb-0" for="code">Codice</label>
                                                    <input class="form-control w-100" type="text" name="code" value="{{$stoneType->code}}" required>
                                                </div>
                                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                                    <label class="mb-0" for="description">Descrizione</label>
                                                    <input class="form-control w-100" type="text" name="description" value="{{$stoneType->description}}" required>
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