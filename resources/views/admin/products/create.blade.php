@extends('layout')
@section('content')
@section('style')
    <style>
        #addProcessTab.nav-link:hover{
            color: white !important
        }
    </style>
@endsection
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
                                                <form action="{{route('product.store')}}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="description">Descrizione</label>
                                                            <textarea required class="form-control shadow w-100" name="description" type="text">{{old('description')}}</textarea>
                                                        </div>
                                                    </div>    
                                                    <div class="row">
                                                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="gold_weight">Peso oro</label>
                                                            <input required value="{{old('gold_weight')}}" class="form-control shadow w-100" name="gold_weight" type="number" step="0.00001">
                                                        </div>
                                                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                                                            <label class="mb-0" for="type">Codice tipo di prodotto</label>
                                                            <input required value="{{old('type')}}" class="form-control shadow w-100" name="type" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="row my-3">
                                                        <div id="tabs-section" class="col-12">
                                                            <ul class="nav nav-tabs" id="addProcessTabs" role="tablist">
                                                                <li class="nav-item mr-2" role="presentation"><button class="text-light nav-link btn-info add-tab" id="addProcessTab" type="button">AGGIUNGI LAVORAZIONE <span class="font-weight-bold">+</span></button>
                                                                </li>
                                                            </ul>
                                                        
                                                            <div class="tab-content w-100" id="processContents">         
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-info mt-3">Salva</button>
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
@push('scripts')
{{-- <script>

    //? Aggiungo un listener sulla tab di aggiunta 
    //? Creo la nuova tab con nuovo id 
    //? Se Elimino Distruggo la tab e clicco sulla precedente e decremento l'id

    let addProcessTab = document.querySelector('#addProcessTab')
    let addProcessTabs = document.querySelector('#addProcessTabs')
    let addProcessContents = document.querySelector('#processContents')

    let processes = {!! json_encode($processes, JSON_HEX_TAG) !!}
    let processTabId = processes ? processes.length : 0;

    //? Creo le select da inserire nelle tabs
    let settingTypes = {!! json_encode($settingTypes->toArray(), JSON_HEX_TAG) !!};
    let stoneClasses = {!! json_encode($stoneClasses->toArray(), JSON_HEX_TAG) !!};
    let stoneTypes = {!! json_encode($stoneTypes->toArray(), JSON_HEX_TAG) !!};

    let settingTypesOptions = `<option value=""></option>`;
    let stoneClassesOptions = `<option value=""></option>`;
    let stoneTypesOptions = `<option value=""></option>`;

    settingTypes.forEach(settingType => {
        settingTypesOptions = settingTypesOptions + `<option value="${settingType.id}">${settingType.code}</option>`
    })
    stoneClasses.forEach(stoneClass => {
        stoneClassesOptions = stoneClassesOptions + `<option value="${stoneClass.id}">${stoneClass.description}</option>`
    })
    stoneTypes.forEach(stoneType => {
        stoneTypesOptions = stoneTypesOptions + `<option value="${stoneType.id}">${stoneType.code}</option>`
    })
    // Aggiungo un listener per creare la tab al click sul pulsante
    addProcessTab.addEventListener("click", createProcessTab);
    
    // Creo la tab prima la testa e poi il body e poi aggiungo un listener sul suo specifico pulsante di delete, dopodiché la seleziono per far vedere la tab appena creata
    function createProcessTab() {
        processTabId++ 
        addProcessTabHeader();
        addProcessTabContent();
        addDeleteEventToTabs();
        selectNewTab();
    }

    function selectNewTab() {
        let newTabButton = document.querySelector(`#newProcessTab${processTabId}`).firstChild
        newTabButton.click()
        newTabButton.focus()
    }    

    function addProcessTabHeader() {
        let newTabHeader = document.createElement("li");
        newTabHeader.setAttribute('id',`newProcessTab${processTabId}`)
        newTabHeader.setAttribute('role',`presentation`)
        newTabHeader.className += "nav-item mr-3 d-flex align-items-baseline"
        newTabHeader.innerHTML = getNewLi();
        addProcessTabs.insertBefore(newTabHeader, addProcessTabs.lastChild);
    }

    function addProcessTabContent() {
        let newTabContent = document.createElement("div");
        newTabContent.setAttribute('id',`newProcessContent${processTabId}`)
        newTabContent.setAttribute('role',`tabpanel`)
        newTabContent.setAttribute('tabId',processTabId)
        newTabContent.setAttribute('aria-labelledby',`pills-process-tab`)
        newTabContent.className += "tab-pane fade"
        newTabContent.innerHTML = getNewContent();
        addProcessContents.appendChild(newTabContent);
    }

    // Creo dei nuovi Header e Content per la tab con id sempre aggiornato
    function getNewLi() {
        return `<a class="nav-link text-uppercase" 
                    data-toggle="tab" href="#newProcessContent${processTabId}" role="tab" aria-controls="process-tab" aria-selected="true"><span>LAVORAZIONE N.${processTabId}</span>
                        <div class="p-0 m-0 d-inline" id="deleteProcess${processTabId}">|<i class="btn btn-info btn-sm  p-1 m-1 fas fa-trash trash${processTabId}"></i></div>
                </a>
        `;
    }
    function getNewContent() {
        return  `<div class="container">
                    <div class="row">
                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                            <label class="mb-0" for="stoneTypeCode${processTabId}">Tipo di pietra</label>
                            <select class="form-control" id="stoneTypeCodeSelect${processTabId}" name="stones[${processTabId}][stone_type_id]" required>
                                ${stoneTypesOptions}    
                            </select>
                        </div>
                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                            <label class="mb-0" for="quantity${processTabId}">Numero di pietre</label>
                            <input class="form-control  w-100" id="quantity${processTabId}" type="number" value="" name="stones[${processTabId}][quantity]" required>
                        </div>
                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                            <label class="mb-0" for="stone_weight${processTabId}">Peso pietra</label>
                            <input class="form-control w-100" id="stone_weight${processTabId}" type="number"  step="0.00001" value="" name="stones[${processTabId}][stone_weight]" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                            <label class="mb-0" for="stoneClassCode${processTabId}">Classe di pietra</label>
                            <select class="form-control" id="stoneClassCode${processTabId}" name="stones[${processTabId}][stone_class_id]" required>${stoneClassesOptions}</select>
                        </div>
                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                            <label class="mb-0" for="settingTypesCode${processTabId}">Tipo incastonatura</label>
                            <select class="form-control" id="settingTypesCode${processTabId}" name="stones[${processTabId}][setting_type_id]" required>${settingTypesOptions}</select>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                            <label class="mb-0" for="clientCost${processTabId}">Costo al cliente</label>
                            <input class="form-control w-100" id="clientCost${processTabId}" type="number" step="0.01" value="" name="stones[${processTabId}][client_cost]" required>
                        </div>
                        <div class="col-sm d-flex flex-column mt-3 align-items-start form-group">
                            <label class="mb-0" for="prezzariouser${processTabId}">Prezzario operatore</label>
                            <input class="form-control  w-100" id="prezzariouser${processTabId}" type="number" step="0.01" value="" name="stones[${processTabId}][prezzariouser]" required>
                        </div>
                    </div>
                </div>
        `;
    }
    
    // Questo aggiunge il listener al cestino per eliminare la tab
    function addDeleteEventToTabs() {
        let newTabDeleteButton = document.querySelector(`#deleteProcess${processTabId}`)
        newTabDeleteButton.addEventListener('click', deleteTab.bind(this,processTabId));
    }

    // Questo elimina la tab
    function deleteTab(processTabId) {
        let processTabHeader = document.querySelector(`#newProcessTab${processTabId}`)
        let processTabContent = document.querySelector(`#newProcessContent${processTabId}`)
        
        //? Prima di eliminare clicco sulla tab successiva o precedente, se non ce ne sono rimuovo tutto
        let nextProcessTabHeader = processTabHeader.nextSibling;
        let previousProcessTabHeader = processTabHeader.previousSibling;
        
        //? Poi rimuovo la tab e il content
        processTabHeader.remove()
        processTabContent.remove()
        
        if(nextProcessTabHeader.firstChild){
            nextProcessTabHeader.firstChild.click()
            nextProcessTabHeader.firstChild.focus()
        }else if(previousProcessTabHeader.firstChild && previousProcessTabHeader.firstChild.getAttribute('id') !== 'addProcessTab'){
            previousProcessTabHeader.firstChild.click()
            previousProcessTabHeader.firstChild.focus()
        }
        updateIds()
    }

    // Aggiorno tutti gli id all'eliminazione della tab. Al momento non funzionante
    function updateIds() {
        //? Prendo tutti gli elementi superiori e aggiorno solo la scritta per l'utente il resto resta uguale
        let allHeads = document.querySelectorAll(`[id^=newProcessTab]`)
        if(allHeads.length>0){
            allHeads.forEach((head,index)=>{
                index++
                head.firstChild.firstChild.innerHTML = `LAVORAZIONE N.${index}`
            });
        }
        processTabId = allContents.length++
    }
    
</script> --}}
@endpush
@endsection