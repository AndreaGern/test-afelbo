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
            @foreach ($settingTypes as $settingType)
                <tr data-route="{{ route('settingType.edit', compact('settingType')) }}">
                    <td class=" text-uppercase">{{ $settingType->code }}</td>
                    <td>{{ $settingType->description }}</td>
                    <td class="text-right d-flex justify-content-end">
                        <a class="mr-0 btn btn-warning shadow"
                            href="{{ route('settingType.edit', compact('settingType')) }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
