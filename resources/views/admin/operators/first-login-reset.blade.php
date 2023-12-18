@extends('layout')
@section('content')

<div class="auth-wrapper">
    <div class="auth-content">
        <div class="auth-bg">
            <span class="r"></span>
            <span class="r s"></span>
            <span class="r s"></span>
            <span class="r"></span>
        </div>
        <div class="card">
            <form action="{{route('operator.saveNewPassword')}}" method="POST">
                @method('PUT')
                @csrf
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="feather icon-unlock auth-icon"></i>
                    </div>
                    <h3 class="mb-4">Reset password operatore</h3>
                    <div class="alert alert-success shadow my-4 fw-bold p-2">
                        <p>Questo è il tuo primo accesso alla piattaforma, dovrai resettare la password. In futuro non vedrai più questo messaggio.</p>
                    </div>
                    <div class="input-group mb-3">
                        <input name="email" type="text" class="form-control" placeholder="Inserisci la tua email o username">
                    </div>
                    <div class="input-group mb-3">
                        <input name="password" type="password" class="form-control" placeholder="Inserisci la nuova password">
                    </div>
                    <div class="input-group mb-4">
                        <input name="password_confirmation" type="password" class="form-control" placeholder="Conferma nuova password">
                    </div>
                    <button type="submit" class="btn btn-primary shadow-2 mb-4 my-2">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection