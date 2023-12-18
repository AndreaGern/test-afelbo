<!-- [ Main Content ] start -->
@if (session('message'))
<div class="pcoded-main-container" style="min-height: 0%">
    <div class="pcoded-wrapper">
        <div class="pcoded-content p-0">
            <div class="pcoded-inner-content">
                <div class="alert alert-success shadow mt-4 mx-auto" style="width: 90%">
                    <p class="mx-auto px-auto font-weight-bold">{{session('message')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@if ($errors->any())
<div class="pcoded-main-container" style="min-height: 0%">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div  class="alert alert-danger shadow mt-4 mx-auto" style="width: 90%">
                    @foreach ($errors->all() as $error)
                    <p class="mx-auto px-auto font-weight-bold">{{$error}}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif