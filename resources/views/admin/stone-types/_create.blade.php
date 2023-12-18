<button type="button" class="btn btn-info mr-0" data-toggle="modal" data-target="#addModalCenter">
    Aggiungi nuovo tipo
</button>
<div class="modal fade" id="addModalCenter" tabindex="-1" role="dialog" aria-labelledby="addModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLongTitle">Nuovo tipo di pietra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('stoneType.store')}}" method="POST" class="text-left">
                    @csrf
                    <div class="d-flex flex-column mt-3 form-group">
                        <label class="mb-0" for="code">Codice</label>
                        <input class="form-control" type="text" name="code" value="{{old('code')}}" required>
                    </div>
                    <div class="d-flex flex-column mt-3 form-group">
                        <label class="mb-0" for="description">Descrizione</label>
                        <input class="form-control" type="text" name="description" value="{{old('description')}}" required>
                    </div>
                    
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary shadow">Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>