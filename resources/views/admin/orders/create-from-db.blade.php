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
                                    <div class="card-header">
                                        <h5 class="font-weight-bold">SELEZIONA PRODOTTO {{$product->code}}</h5>
                                        <div class="d-flex flex-column align-items-end">
                                        </div>
                                    </div>
                                    <div class="card-body" id="create-from-db">
                                        <div class="row">
                                            <div class="col-12">
                                                <form action="{{route('order.store-from-db',compact('commission', 'product'))}}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="code">Codice</label>
                                                            <input class="form-control w-100 text-uppercase" type="text" value="{{$product->code}}" name="code" disabled>
                                                        </div>
                                                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="description">Descrizione</label>
                                                            <input required class="form-control w-100" type="text" value="{{$product->description}}" name="description">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-md-3 form-group">
                                                            <label class="mb-0" for="gold_weight">Peso oro</label>
                                                            <input required class="form-control w-100" name="gold_weight" type="number" step="0.00001" value="{{$product->gold_weight}}">
                                                        </div>
                                                        <div class="col-12 col-md-3 form-group">
                                                            <label class="mb-0" for="product_quantity_order">Quantità</label>
                                                            <input required class="form-control w-100" name="product_quantity_order" value="{{old('product_quantity_order')}}" required min="1">
                                                        </div>
                                                        <div class="col-12 col-md-6 form-group">
                                                            <label class="mb-0" for="type">Codice tipo di prodotto</label>
                                                            <input class="form-control w-100 text-uppercase" type="text" value="{{$product->type}}" name="type" readonly style="pointer-events: none;">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ul class="nav nav-tabs" id="addProcessTabs" role="tablist">
                                                                <li class="nav-item mr-2" role="presentation"><button class="text-light nav-link btn-info add-tab" id="addProcessTab" type="button">AGGIUNGI LAVORAZIONE <span class="font-weight-bold">+</span></button>
                                                                </li>
                                                                @foreach ($product->stones as $stone)
                                                                <li class="nav-item mr-3 d-flex align-items-baseline" role="presentation" id="newProcessTab{{$loop->index}}">
                                                                    <a  @if($loop->first)
                                                                            class="nav-link active text-uppercase"
                                                                        @else
                                                                            class="nav-link text-uppercase"
                                                                        @endif
                                                                        id="pietra{{$loop->index}}-tab" data-toggle="tab" href="#newProcessContent{{$loop->index}}" role="tab" aria-controls="newProcessContent{{$loop->index}}" aria-selected="false">
                                                                        <span>LAVORAZIONE N.{{$loop->index + 1}}</span>
                                                                        <div class="p-0 m-0 d-inline" id="deleteProcess{{$loop->index}}"><i class="btn btn-info btn-sm  p-1 m-1 fa-solid fa-trash-can trash{{$loop->index}}"></i></div>
                                                                    </a>
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                            
                                                            <div class="tab-content" id="processContents">
                                                                @foreach ($product->stones as $stone)
                                                                    <div class="tab-pane fade @if($loop->first) show active @endif"
                                                                        id="newProcessContent{{$loop->index}}" tabId="{{$loop->index}}" role="tabpanel" aria-labelledby="pietra{{$stone->process->id}}-tab">
                                                                        <div class="container">
                                                                            <div class="row">
                                                                                <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                    <label class="mb-0">Tipo pietra</label>
                                                                                    <select class="form-control" id="stoneTypeCodeSelect{{$loop->index}}" name="stones[{{$loop->index}}][stone_type_id]" required>
                                                                                        <option value=""></option>
                                                                                        @foreach ($stoneTypes as $stoneType)
                                                                                        <option @if ($stoneType->id == $stone->stoneType->id) selected @endif value="{{$stoneType->id}}">{{$stoneType->code}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                    <label class="mb-0" for="quantity{{$loop->index}}">Numero di pietre</label>
                                                                                    <input required id="quantity{{$loop->index}}" class="form-control w-100" type="number" value="{{$stone->process->quantity}}" name="stones[{{$loop->index}}][quantity]">
                                                                                </div>
                                                                                <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                    <label class="mb-0" for="stone_weight{{$loop->index}}">Peso pietra</label>
                                                                                    <input required class="form-control w-100" type="number" step="0.0001" value="{{$stone->stone_weight}}" name="stones[{{$loop->index}}][stone_weight]">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                    <label class="mb-0" for="stoneClassCode{{$loop->index}}">Classe di pietra</label>
                                                                                    <select class="form-control" id="stoneClassCode{{$loop->index}}" name="stones[{{$loop->index}}][stone_class_id]">
                                                                                        <option value=""></option>
                                                                                        @foreach ($stoneClasses as $stoneClass)
                                                                                        <option @if ($stoneClass->id == $stone->stoneClass->id) selected @endif value="{{$stoneClass->id}}">{{$stoneClass->description}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                    <label class="mb-0" for="settingTypesCode{{$loop->index}}">Tipo incastonatura</label>
                                                                                    <select required class="form-control" id="settingTypesCode{{$loop->index}}" name="stones[{{$loop->index}}][setting_type_id]">
                                                                                        <option value=""></option>
                                                                                        @foreach ($settingTypes as $settingType)
                                                                                        <option @if ($settingType->id == $stone->settingType->id) selected @endif value="{{$settingType->id}}">{{$settingType->code}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <hr>
                                                                            <div class="row">
                                                                                <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                    <label class="mb-0" for="clientCost{{$loop->index}}">Costo al cliente</label>
                                                                                    <input required id="clientCost{{$loop->index}}" class="form-control w-100" type="number" value="{{$stone->client_cost}}" step="0.01" name="stones[{{$loop->index}}][client_cost]">
                                                                                </div>
                                                                                <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                                                    <label class="mb-0" for="prezzariouser{{$loop->index}}">Prezzario operatore</label>
                                                                                    <input required id="prezzariouser{{$loop->index}}" class="form-control w-100" type="number" value="{{$stone->prezzariouser}}" step="0.01" name="stones[{{$loop->index}}][prezzariouser]">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-info mt-3 shadow">Salva</button>
                                                    </div>
                                                </form>
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
@push('scripts')
{{-- <script>
    let stones = {!! json_encode($stones->toArray(), JSON_HEX_TAG) !!};
    let processes = {!! json_encode($product->stones->count(), JSON_HEX_TAG) !!};
    let settingTypes = {!! json_encode($settingTypes->toArray(), JSON_HEX_TAG) !!};
    let stoneClasses = {!! json_encode($stoneClasses->toArray(), JSON_HEX_TAG) !!};
    let stoneTypes = {!! json_encode($stoneTypes->toArray(), JSON_HEX_TAG) !!};
    
    let optionSettingTypes = `<option value=""></option>`;
    let optionStoneClasses = `<option value=""></option>`;
    let optionStoneTypes = `<option value=""></option>`;
    
    settingTypes.forEach(element => {
        optionSettingTypes = optionSettingTypes + `<option value="${element.id}">${element.code}</option>`
    })
    stoneClasses.forEach(element => {
        optionStoneClasses = optionStoneClasses + `<option value="${element.id}">${element.description}</option>`
    })
    stoneTypes.forEach(element => {
        optionStoneTypes = optionStoneTypes + `<option value="${element.id}">${element.code}</option>`
    })
    
    let selectSettingTypes = "<select>"+ optionSettingTypes +"</select>";
    let selectStoneClasses = "<select>"+ optionStoneClasses +"</select>";
    let selectStoneTypes = "<select>"+ optionStoneTypes +"</select>";
    $(".nav-tabs").on("click", "a", function (e) {
        e.preventDefault();
        if (!$(this).hasClass('add-tab')) {
            $(this).tab('show');
        }
    })
    .on("click", "span", function () {
        var anchor = $(this).siblings('a');
        $(anchor.attr('href')).remove();
        $(this).parent().remove();
        $(".nav-tabs li").children('a').first().click();
    });
    
    let id = processes;
    $('.add-tab').click(function (e) {
        e.preventDefault();
        id++;
        // var id = $(".nav-tabs").children().length; //think about it ;)
        var tabId = 'newpietra' + id;
        $(this).closest('li').before('<li class="nav-item mr-3 d-flex align-items-baseline" id="li-new'+tabId+'"><a href="#newpietra' + id + '" class="nav-link text-uppercase">LAVORAZIONE N'+id+'<i class="pl-3 fas fa-trash trash'+tabId+'"></i></a> </li>');
        $('.tab-content').append('<div class="tab-pane '+tabId+'" id="' + tabId + '"><div class="container"><div class="row"><div class="col-sm d-flex flex-column mt-3 align-items-start form-group"><label class="mb-0" for="codicetipo'+id+'">Tipo pietra</label><select class="form-control" id="exampleFormControlSelect2" name="stones['+id+'][stone_type_id]">'+ optionStoneTypes +'</select></div><div class="col-sm d-flex flex-column mt-3 align-items-start form-group"><label class="mb-0" for="quantita">Numero di pietre</label><input class="form-control w-100" type="number" value="" name="stones['+id+'][quantity]"></div><div class="col-sm d-flex flex-column mt-3 align-items-start form-group"><label class="mb-0" for="stone_weight'+id+'">Peso pietra</label><input class="form-control w-100" type="number" step="0.0001" value="" name="stones['+id+'][stone_weight]"></div></div><div class="row"><div class="col-sm d-flex flex-column mt-3 align-items-start form-group"><label class="mb-0" for="codiceclasse'+id+'">Codice classe</label><select class="form-control" id="exampleFormControlSelect2" name="stones['+id+'][stone_class_id]">'+ optionStoneClasses +'</select></div><div class="col-sm d-flex flex-column mt-3 align-items-start form-group"><label class="mb-0" for="codiceincastonatura'+id+'">Codice incastonatura</label><select class="form-control" id="exampleFormControlSelect2" name="stones['+id+'][setting_type_id]">'+ optionSettingTypes +'</select></div></div><hr><div class="row"><div class="col-sm d-flex flex-column mt-3 align-items-start form-group"><label class="mb-0" for="quantita">Costo al cliente</label><input class="form-control w-100" type="number" value="" step="0.01" name="stones['+id+'][client_cost]"></div><div class="col-sm d-flex flex-column mt-3 align-items-start form-group"><label class="mb-0" for="quantita">Prezzario operatore</label><input class="form-control w-100" type="number" value="" step="0.01" name="stones['+id+'][prezzariouser]"></div></div></div></div>');
        
        
        $('.nav-tabs li:nth-child(' + id + ') a').click();
        
        $('.fa-trash.trash'+tabId).click(function (){
            console.log('hello world')
            $(this).parent().parent().remove();
            $('#new'+tabId).remove();
        })
        
        
    });
    $('.oldpietra').click(function (){
        var classList = $(this).attr('class')
        var lastClass = classList.split(' ').pop();
        console.log(lastClass)
        
        $(this).parent().parent().remove();
        $('#'+lastClass).remove();
    })
    
    
    
</script> --}}
@endpush
@endsection

