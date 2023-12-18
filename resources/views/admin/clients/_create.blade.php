<button type="button" class="btn btn-info mr-0" data-toggle="modal" data-target="#addModalClient">
    Aggiungi nuovo cliente
</button>

<div class="modal fade" id="addModalClient" tabindex="-1" role="dialog" aria-labelledby="addModalClientCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyModalLongTitle">Aggiungi nuovo cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                                                            
                <form action="{{route('client.store')}}" method="POST">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="code">Codice cliente</label>
                                    <input required class="form-control w-100" type="text" name="code" >
                                </div>                                
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="name">Nome</label>
                                    <input class="form-control w-100" type="text" name="name" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="phone">Telefono</label>
                                    <input class="form-control w-100" type="text" name="phone" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="email">Email</label>
                                    <input class="form-control w-100" type="text" name="email" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="address">Indirizzo</label>
                                    <input class="form-control w-100" type="text" name="address" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="cap">CAP</label>
                                    <input class="form-control w-100" type="text" name="cap" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="city">Comune</label>
                                    <input class="form-control w-100" type="text" name="city" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="province">Provincia</label>
                                    <input class="form-control w-100" type="text" name="province" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="partita_iva">Partita IVA</label>
                                    <input class="form-control w-100" type="text" name="partita_iva" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="website">Web URL</label>
                                    <input class="form-control w-100" type="text" name="website" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="d-flex flex-column mt-3 align-items-start form-group">
                                    <label class="mb-0" for="codice_fiscale">Codice Fiscale</label>
                                    <input class="form-control w-100" type="text" name="codice_fiscale" >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary shadow">Salva</button>
                    </div>                    
                </form>
                
            </div>
        </div>
    </div>
</div>