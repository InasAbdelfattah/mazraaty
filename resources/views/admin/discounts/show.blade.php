@extends('admin.layouts.master')

@section('content')

    <!-- Page-Title -->

    <div class="row">
        <div class="col-xs-12">
            <div class="bg-picture card-box">
                <div class="row">
                    <div class="col-xs-6 col-md-4 col-sm-4">
                        <h3 class="page-title">بيانات كود الخصم</h3>
                    </div>
            
                    <div class="m-t-15 col-xs-6 col-md-8 col-sm-8 text-right">
                        <button type="button" class="btn btn-custom  waves-effect waves-light" onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i class="fa fa-reply"></i></span>
                        </button>
                    </div>
                   
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-detail">

                        <div class="panel-body">

                            <div class="col-lg-6 col-xs-12">
                                <label>كود الخصم :</label>
                                <p>{{ $discount->code }}</p>
                            </div>

                            <div class="col-lg-6 col-xs-12">
                                <label>نسبة الخصم :</label>
                                <p>{{ $discount->ratio }}</p>
                            </div>
                            
                            <div class="col-lg-12 col-xs-12">
                                <label> تاريخ وقت بداية الخصم :</label>
                                <p>{{ $discount->from }}</p>
                            </div>

                            <div class="col-lg-6 col-xs-12">
                                <label>تاريخ وقت انتهاء الخصم :</label>
                                <p>{{ $discount->to }}</p>
                            </div>

                            <div class="col-lg-6 col-xs-12">
                                <label>عدد المستخدمين للكود :</label>
                                <p>{{ $user_no }}</p>
                            </div>
                            
                            <div class="col-lg-6 col-xs-12">
                                <label>عدد مرات استخدام الخصم :</label>
                                <p>{{ $discount->times }}</p>
                            </div>

                        </div>
                    </div>
                    <!-- end card-box-->


                </div>
            </div>
        </div>

    </div>


@endsection
