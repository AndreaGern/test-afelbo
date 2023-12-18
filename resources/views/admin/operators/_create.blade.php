<button type="button" class="btn btn-info mr-0" data-toggle="modal" data-target="#addModal">
    Aggiungi operatore
</button>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLongTitle">Aggiungi nuovo operatore</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('operator.store')}}" method="POST">
                    @csrf
                    <div class="d-flex flex-column mt-3 align-items-start form-group ">
                        <label  for="name" class="mb-0" >Nome e cognome</label>
                        <input class="form-control w-100" name='name' id='name' type="text" value="{{old('name')}}" required>
                    </div>
                    <div class="d-flex flex-column mt-3 align-items-start form-group ">
                        <label  for="email" class="mb-0"> Email o Username</label>
                        <input class="form-control w-100" name='email' id='email' type="text" value="{{old('email')}}" required>
                    </div>
                    <div class="d-flex flex-column mt-3 align-items-start form-group ">
                        <label for="code" class="mb-0">Codice</label>
                        <input class="form-control w-100" type="number" name='code' id='code' value="{{old('code')}}" required>
                    </div>
                    <div class="d-flex flex-column mt-3 align-items-start form-group ">
                        <label for="workstation" class="mb-0">Postazione di lavoro</label>
                        <input class="form-control w-100" type="number" name='workstation' id='workstation' value="{{old('workstation')}}" required>
                    </div> 
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary shadow">Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>