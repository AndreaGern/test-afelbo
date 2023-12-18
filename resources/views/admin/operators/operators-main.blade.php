@extends('layout')
@section('content')
<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <!-- [ breadcrumb ] start -->
                
                <!-- [ breadcrumb ] end -->
                <div class="main-body">
                    <div class="page-wrapper">
                        <!-- [ Main Content ] start -->
                        <div class="row">
                            <!-- [ Hover-table ] start -->
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 col-lg-8 mb-4">
                                                    <h5 class="font-weight-bold">OPERATORI</h5>
                                                </div>
                                                <div class="col-12 col-lg-4">
                                                    <div class="d-flex flex-column align-items-lg-end">
                                                        @include('admin.operators._create')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-block table-border-style">
                                        @include('admin.operators._index',['operators' => $operators])
                                    </div>
                                </div>
                            </div>
                                    <!-- [ Hover-table ] end -->
                        </div>
                                <!-- [ Main Content ] end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection