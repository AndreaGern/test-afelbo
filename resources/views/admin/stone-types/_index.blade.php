<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Codice tipo</th>
                <th>Descrizione</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stoneTypes as $stoneType)
                <tr data-route="{{ route('stoneType.edit', compact('stoneType')) }}">
                    <td class=" text-uppercase">{{ $stoneType->code }}</td>
                    <td>{{ $stoneType->description }}</td>
                    <td class="text-right d-flex justify-content-end">
                        <a href="{{ route('stoneType.edit', compact('stoneType')) }}" class="btn btn-warning shadow">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
