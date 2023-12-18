@extends('layout')
@section('content')
<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        @livewire('manage-distribution', ['order' => $order, 'operators'=>$operators])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
