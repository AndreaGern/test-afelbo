<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gestionale Afelbo</title>

    <!-- Favicon icon -->
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/date-1.1.2/sp-2.0.2/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/sc-2.0.7/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles
    @yield('style')
</head>

<body class="antialiased">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    @auth
        @include('_nav')
    @endauth


    @include('message')
    @yield('content')
    <!-- Required Js -->

    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/date-1.1.2/sp-2.0.2/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/sc-2.0.7/datatables.min.js">
    </script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.24/sorting/datetime-moment.js"></script>
    @livewireScripts

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let tables = document.querySelectorAll("table");
            let minInput = document.querySelector('#min')
            let maxInput = document.querySelector('#max')
            let today = new Date()
            let deadlineWarning = new Date()
            deadlineWarning.setDate(deadlineWarning.getDate() + 2);

            tables.forEach((table, i) => {
                table.setAttribute('id', 'datatable' + i)
                table.classList.add('display', 'compact')
                let datatable = new DataTable('#' + table.id, {
                    language: {
                        url: '/dt-italian.json'
                    },
                    "createdRow": function(row, data, dataIndex) {
                        const [day, month, year] = data[3].split('/');
                        const statoLavorazione = data[4];
                        let commissionDate = new Date(+('20' + year), +month - 1, +day)

                        if (statoLavorazione == 'completato' || statoLavorazione ==
                            'consegnato') {
                            $(row.children[3]).removeClass('yellow-background');
                            $(row.children[3]).removeClass('red-background');
                        } else if (commissionDate > today && commissionDate <=
                            deadlineWarning) {
                            $(row.children[3]).removeClass('yellow-background');
                            $(row.children[3]).removeClass('red-background');
                            $(row.children[3]).addClass('yellow-background');
                        } else if (today > commissionDate) {
                            $(row.children[3]).removeClass('yellow-background');
                            $(row.children[3]).removeClass('red-background');
                            $(row.children[3]).addClass('red-background');
                        }
                    },
                    rowCallback: function(row, data, index) {
                        // Aggiungi la classe "clickable" alla riga
                        $(row).addClass('clickable');

                        // Aggiungi l'evento click alla riga
                        $(row).on('click', function(e) {
                            if (!$(e.target).is('button, a')) {
                                const route = $(this).data('route');
                                if (route) {
                                    window.location.href = route;
                                }
                            }
                        });
                    },
                });



                if (minInput) {
                    var minDate, maxDate;
                    $.fn.dataTable.ext.search.push(
                        function(settings, data, dataIndex) {
                            var min = minDate.val();
                            var max = maxDate.val();
                            var dateString = data[2];
                            // se dateString non é una data valida allora prendo data 3 che é la data di consegna
                            if (!moment(dateString, "DD/MM/YY").isValid()) {
                                dateString = data[3];
                            }
                            var dateMomentObject = moment(dateString,
                                "DD/MM/YY"); // 1st argument - string, 2nd argument - format
                            var date = dateMomentObject.toDate();
                            if (
                                (min === null && max === null) ||
                                (min === null && date <= max) ||
                                (min <= date && max === null) ||
                                (min <= date && date <= max)
                            ) {
                                return true;
                            }
                            return false;
                        }
                    );
                    // Create date inputs
                    minDate = new DateTime($('#min'), {
                        format: 'DD/MM/YY',
                        buttons: {
                            today: true,
                            clear: true
                        },
                        i18n: {
                            clear: "Annulla selezione",
                            today: "Oggi",
                            previous: 'Precedente',
                            next: 'Successivo',
                            months: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
                                'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre',
                                'Dicembre'
                            ],
                            weekdays: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab']
                        }
                    });
                    maxDate = new DateTime($('#max'), {
                        format: 'DD/MM/YY',
                        buttons: {
                            today: true,
                            clear: true
                        },
                        i18n: {
                            clear: "Annulla selezione",
                            today: "Oggi",
                            previous: 'Precedente',
                            next: 'Successivo',
                            months: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
                                'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre',
                                'Dicembre'
                            ],
                            weekdays: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab']
                        }
                    });


                }
                // Refilter the table
                $('#min, #max').on('change', function() {
                    datatable.draw();
                });
            });



            //? NAVS JS 


            //? Aggiungo un listener sulla tab di aggiunta 
            //? Creo la nuova tab con nuovo id 
            //? Se Elimino Distruggo la tab e clicco sulla precedente e decremento l'id

            if (document.querySelector('#addProcessTab')) {
                let addProcessTab = document.querySelector('#addProcessTab')
                let addProcessTabs = document.querySelector('#addProcessTabs')
                let addProcessContents = document.querySelector('#processContents')

                let processes = {!! json_encode($processes, JSON_HEX_TAG) !!}
                let processTabId = processes ? processes.length : 0;


                //? Creo le select da inserire nelle tabs
                let settingTypes = {!! json_encode($settingTypes ? $settingTypes->toArray() : '', JSON_HEX_TAG) !!};
                let stoneClasses = {!! json_encode($stoneClasses ? $stoneClasses->toArray() : '', JSON_HEX_TAG) !!};
                let stoneTypes = {!! json_encode($stoneTypes ? $stoneTypes->toArray() : '', JSON_HEX_TAG) !!};

                let settingTypesOptions = `<option value=""></option>`;
                let stoneClassesOptions = `<option value=""></option>`;
                let stoneTypesOptions = `<option value=""></option>`;

                settingTypes.forEach(settingType => {
                    settingTypesOptions = settingTypesOptions +
                        `<option value="${settingType.id}">${settingType.code}</option>`
                })
                stoneClasses.forEach(stoneClass => {
                    stoneClassesOptions = stoneClassesOptions +
                        `<option value="${stoneClass.id}">${stoneClass.description}</option>`
                })
                stoneTypes.forEach(stoneType => {
                    stoneTypesOptions = stoneTypesOptions +
                        `<option value="${stoneType.id}">${stoneType.code}</option>`
                })
                // Aggiungo un listener per creare la tab al click sul pulsante
                addProcessTab.addEventListener("click", createProcessTab);

                if (processes.length > 0) {
                    // Aggiungo un listener sul tasto delete delle card già presenti
                    processes.forEach((process, i) => {
                        let newTabDeleteButton = document.querySelector(`#deleteProcess${i}`)
                        newTabDeleteButton.addEventListener('click', deleteTab.bind(this, i))
                    })
                }

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
                    newTabHeader.setAttribute('id', `newProcessTab${processTabId}`)
                    newTabHeader.setAttribute('role', `presentation`)
                    newTabHeader.className += "nav-item mr-3 d-flex align-items-baseline"
                    newTabHeader.innerHTML = getNewLi();
                    addProcessTabs.insertBefore(newTabHeader, addProcessTabs.lastChild);
                }

                function addProcessTabContent() {
                    let newTabContent = document.createElement("div");
                    newTabContent.setAttribute('id', `newProcessContent${processTabId}`)
                    newTabContent.setAttribute('role', `tabpanel`)
                    newTabContent.setAttribute('tabId', processTabId)
                    newTabContent.setAttribute('aria-labelledby', `pills-process-tab`)
                    newTabContent.className += "tab-pane fade"
                    newTabContent.innerHTML = getNewContent();
                    addProcessContents.appendChild(newTabContent);
                }

                // Creo dei nuovi Header e Content per la tab con id sempre aggiornato
                function getNewLi() {
                    return `<a class="nav-link text-uppercase" 
                                data-toggle="tab" href="#newProcessContent${processTabId}" role="tab" aria-controls="process-tab" aria-selected="true"><span>LAVORAZIONE N.${processTabId}</span>
                                    <div class="p-0 m-0 d-inline" id="deleteProcess${processTabId}"><i class="btn btn-info btn-sm  p-1 m-1 fa-solid fa-trash-can trash${processTabId}"></i></div>
                            </a>
                    `;
                }

                function getNewContent() {
                    return `<div class="container">
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
                    newTabDeleteButton.addEventListener('click', deleteTab.bind(this, processTabId));
                }

                // Questo elimina la tab
                function deleteTab(processTabId) {
                    let processTabHeader = document.querySelector(`#newProcessTab${processTabId}`)
                    let processTabContent = document.querySelector(`#newProcessContent${processTabId}`)

                    //? Prima di eliminare clicco sulla tab successiva o precedente, se non ce ne sono rimuovo tutto
                    let nextProcessTabHeader = processTabHeader?.nextSibling;
                    let previousProcessTabHeader = processTabHeader?.previousSibling;
                    //? Poi rimuovo la tab e il content
                    processTabHeader.remove()
                    processTabContent.remove()

                    if (nextProcessTabHeader.firstChild) {
                        nextProcessTabHeader.firstChild.click()
                        nextProcessTabHeader.firstChild.focus()
                    } else if (previousProcessTabHeader.querySelector('a[id]') && previousProcessTabHeader
                        .querySelector('a[id]').getAttribute('id') !== 'addProcessTab') {
                        // se c'è un elemento precedente con un attributo "id", cliccalo, a meno che non sia l'ultimo elemento
                        previousProcessTabHeader.querySelector('a[id]').click();
                        previousProcessTabHeader.querySelector('a[id]').focus();
                    }
                    processTabId--;
                    updateIds()
                }

                // Aggiorno tutti gli id all'eliminazione della tab. Al momento non funzionante
                function updateIds() {
                    //? Prendo tutti gli elementi superiori e aggiorno solo la scritta per l'utente il resto resta uguale
                    let allHeads = document.querySelectorAll(`[id^=newProcessTab]`)
                    if (allHeads.length > 0) {
                        allHeads.forEach((head, index) => {
                            index++
                            if (head.firstChild.firstChild) {
                                head.firstChild.firstChild.innerHTML = `LAVORAZIONE N.${index}`
                            } else {
                                head.firstChild.innerHTML = `LAVORAZIONE N.${index}`
                            }
                        });
                    }
                    processTabId = allHeads.length++
                }
            }

        });
    </script>
</body>

</html>
