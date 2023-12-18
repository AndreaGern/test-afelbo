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
            <form action="/register" method="POST">
                @csrf
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="feather icon-user-plus auth-icon"></i>
                    </div>
                    <h3 class="mb-4">Registrati</h3>
                    <div class="input-group mb-3">
                        <input name="name" type="text" class="form-control" placeholder="Nome e Cognome">
                    </div>
                    <div class="input-group mb-3">
                        <input name="email" type="text" class="form-control" placeholder="Email">
                    </div>
                    <div class="input-group mb-4">
                        <input name="password" type="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="input-group mb-4">
                        <input name="password_confirmation" type="password" class="form-control" placeholder="Conferma password">
                    </div>
                    <div class="form-group text-left">
                        <div class="checkbox checkbox-fill d-inline">
                            <input name="remember" type="checkbox" name="checkbox-fill-1" id="checkbox-fill-1" checked="">
                            <label for="checkbox-fill-1" class="cr">Ricordami</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary shadow-2 mb-4">Registrati</button>
                    <p class="mb-0 text-muted">Hai già un account? <a href="{{route('login')}}"> Log in</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection