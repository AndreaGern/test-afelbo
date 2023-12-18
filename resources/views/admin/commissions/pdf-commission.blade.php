<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Ordine {{ $commission->getCommissionCode() ?? 'N.D.' }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous" />
    <style>
        p {
            font-size: larger;
        }

        .fs-small {
            font-size: 12px;
        }

        .fw-bold {
            font-weight: 700;
        }

        .row {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            width: 100%;
        }

        .mb {
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .mb2 {
            margin-bottom: 5px;
            padding-bottom: 5px;
        }

        .mt {
            margin-top: 10px;
        }

        .mt-sm {
            margin-top: 5px;
        }

        .mt-0 {
            margin-top: 0px;
        }

        .col {
            display: flex;
            flex-direction: column;
            flex-basis: 100%;
            flex: 1;
        }

        .round3 {
            border: 2px solid darkblue;
            border-radius: 12px;
            padding: 10px 20px 10px 20px;
        }

        .block {
            display: block;
        }

        .lh-1 {
            letter-spacing: 0px;
            line-height: 8px;
        }
    </style>
</head>

<body>
    <div class="container mt-sm">
        <div class="row">
            <div class="col-xs-6">
                <h1 class="fw-bold mt-0">Dettaglio Ordine {{ $commission->getCommissionCode() ?? 'N.D.' }}</h1>
                <h2 class="fw-bold mt-0">Cliente {{ $commission->client->code ?? 'N.D.' }}</h2>
            </div>
            <div class="col-xs-6 text-right">
                <h6>Data di emissione: {{ $commission->created_at->format('d-m-Y') ?? 'N.D.' }}</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="row mt-0">
                    <div class="col-xs-3">
                        <h3 class="fw-bold">Informazioni cliente</h3>
                    </div>
                </div>
                <div class="row mt-0">
                    <div class="col-xs-6">
                        <p>
                            <span class="fw-bold"> Codice Cliente:</span> {{ $commission->client->code ?? 'N.D.' }}
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <p>
                            <span class="fw-bold"> Nome:</span> {{ $commission->client->name ?? 'N.D.' }}
                        </p>
                    </div>
                </div>
                <hr class="mt-0" />
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="fw-bold">Contabilità cliente</h3>
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">
                                <div class="row mt-0">
                                    <div class="col-xs-4">
                                        <p>
                                            <span class="fw-bold"> Pagamenti:</span> €
                                            {{ $commission->client->getRevenue() }}
                                        </p>
                                    </div>
                                    <div class="col-xs-4">
                                        <p><span class="fw-bold"> Lavori:</span> €
                                            {{ $commission->client->getTotalDue() }}</p>
                                    </div>
                                    <div class="col-xs-4">
                                        <p><span class="fw-bold"> Diff.Entrate - Lavorato:</span> €
                                            {{ $commission->client->getUnpaidSum() }}</p>
                                    </div>
                                </div>
                                <hr class="mt-0" />
                            </div>
                        </div>
                        <h3 class="fw-bold">Ordini - Numero ordini: {{ $commission->products()->count() }}</h3>
                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>Codice ordine</th>
                                    <th>Codice prodotto</th>
                                    <th>Descrizione</th>
                                    <th>Quantità</th>
                                    <th>Costo unitario</th>
                                    <th>Totale</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commission->products as $product)
                                    <tr>
                                        <td>{{ $product->order->code }}</td>
                                        <td class="text-uppercase">{{ $product->code }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>{{ $product->order->quantity }}</td>
                                        <td>{{ $product->order->importo_unitario }} €</td>
                                        <td>{{ $product->order->importo_totale }} €</td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="row lh-1 fs-small">
            <div class="col-xs-12">
                <h3 class="fw-bold">Totale ordine: {{ $commission->importo_totale }} €</h3>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous">
    </script>
</body>

</html>
