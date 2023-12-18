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
                                                    <div class="col-12 col-lg-8 mb-3">
                                                        <h5 class="font-weight-bold">TIPI DI PIETRE</h5>
                                                    </div>
                                                    <div class="col-12 col-lg-4 text-lg-right text-center">
                                                        @include('admin.stone-types._create')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block table-border-style">
                                            @include('admin.stone-types._index',['stoneTypes'=>$stoneTypes])
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