@extends('layout')
@section('content')
<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <!-- [ breadcrumb ] end -->
                <div class="main-body">
                    <div class="page-wrapper">
                        <div class="row">
                            <div class="col-12 col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 col-lg-8">
                                                    <div class="d-flex justify-content-between mb-4">
                                                        {{-- <h5 class="font-weight-bold">CREA NUOVA COMMESSA</h5> --}}
                                                        <h5 class="font-weight-bold">CREA NUOVO ORDINE</h5>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="p-2 px-4 importi-commesse">
                                                        <h6 class="font-weight-bold d-flex justify-content-between align-items-center text-white">importo totale <span style="font-size: larger;">0,00 <span>€</span></span></h6>
                                                        {{-- <h6 class="font-weight-bold d-flex justify-content-between align-items-center" style="width: 17em; color:white">anticipo <span style="font-size: larger;">0,00 <span>€</span></span></h6> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <form action="{{route('commission.store')}}" method="post">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="client">Codice cliente</label>
                                                            <select required name="client" class="custom-select" id="clientCodeSelect">
                                                                @forelse ($clients as $client)
                                                                    <option value="{{$client->id}}">{{$client->code}}</option>
                                                                @empty
                                                                    <option value="">Nessun cliente presente</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="client">Nome cliente</label>
                                                            <select required name="client" class="custom-select" id="clientNameSelect" style="pointer-events: none;">
                                                                @forelse ($clients as $client)
                                                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                                                @empty
                                                                    <option value="">Nessun cliente presente</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="deadline">Data di consegna</label>
                                                            <input type="date" name="deadline" class="form-control" id="deadline" value="{{old('deadline')}}" required>
                                                        </div>
                                                    </div> 
                                                    <div class="row">
                                                        <div class="col-sm d-flex flex-column align-items-start form-group">
                                                            <label class="mb-0" for="commission_description">Descrizione</label>
                                                            <input type="text" name="commission_description" class="form-control" id="commission_description">
                                                        </div>
                                                    </div>   
                                                    <div class="row">
                                                        <div class="col-12 text-right">
                                                            <button type="submit" class="btn btn-info shadow">Crea ordine</button>
                                                            {{-- <button type="submit" class="btn btn-info shadow">Crea Commessa</button> --}}
                                                        </div>
                                                    </div>
                                                </form>
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
</div>

@push('scripts')

<script>   
// when client code is changed, update the client name
    document.getElementById('clientCodeSelect').addEventListener('change', function() {
        let clientCode = document.getElementById('clientCodeSelect').value;
        let clientName = document.getElementById('clientNameSelect');
        clientName.value = clientCode;
    });
</script>
@endpush

@endsection