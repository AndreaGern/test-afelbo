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
            <form action="{{route('login')}}" method="POST">
                @csrf
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="feather icon-unlock auth-icon"></i>
                    </div>
                    <h3 class="mb-4">Login</h3>
                    <div class="input-group mb-3">
                        <input name="email" type="text" class="form-control" value="{{old('email')}}" placeholder="Email o Username">
                    </div>
                    <div class="input-group mb-4">
                        <input name="password" type="password" class="form-control" value="{{old('password')}}" placeholder="Password">
                    </div>
                    <div class="form-group text-left">
                        <div class="checkbox checkbox-fill d-inline">
                            <input name="remember" type="checkbox" name="checkbox-fill-1" id="checkbox-fill-a1" checked="">
                            <label for="checkbox-fill-a1" class="cr">Ricordami</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary shadow-2 mb-4 my-2">Login</button>
                    <p class="mb-2 text-muted">Se hai dimenticato la password rivolgiti al tuo amministratore per un reset.</p>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection