<button type="button" class="btn btn-info mr-0" data-toggle="modal" data-target="#addModal">
    Aggiungi nuova classe
</button>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLongTitle">Aggiungi nuova classe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('stoneClass.store')}}" method="POST">
                    @csrf
                    <div class="d-flex flex-column mt-3 align-items-start form-group">
                        <label class="mb-0" for="code">Codice</label>
                        <input class="form-control w-100" type="text" name="code" value="{{old('code')}}" required>
                    </div>
                    <div class="d-flex flex-column mt-3 align-items-start form-group">
                        <label class="mb-0" for="description">Descrizione</label>
                        <input class="form-control w-100" type="text" name="description" value="{{old('description')}}" required>
                    </div>
                    <div class="d-flex flex-column mt-3 align-items-start form-group">
                        <label class="mb-0" for="min_weight">Peso minimo</label>
                        <input class="form-control w-100" type="text" name="min_weight" value="{{old('min_weight')}}" required>
                    </div>
                    <div class="d-flex flex-column mt-3 align-items-start form-group">
                        <label class="mb-0" for="max_weight">Peso massimo</label>
                        <input class="form-control w-100" type="text" name="max_weight" value="{{old('max_weight')}}" required>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary shadow">Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>