<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Codice classe</th>
                <th>Descrizione</th>
                <th>Peso minimo</th>
                <th>Peso massimo</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stoneClasses as $stoneClass)
                <tr data-route="{{ route('stoneClass.edit', compact('stoneClass')) }}">
                    <td class=" text-uppercase">{{ $stoneClass->code }}</td>
                    <td>{{ $stoneClass->description }}</td>
                    <td>{{ $stoneClass->min_weight }}</td>
                    <td>{{ $stoneClass->max_weight }}</td>
                    <td class="text-right d-flex justify-content-end">
                        <a class="btn btn-warning shadow" href="{{ route('stoneClass.edit', compact('stoneClass')) }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
