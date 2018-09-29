@extends('admin.layouts.master')
@section('title','تفاصيل المستخدم')
@section('content')

    <!-- Page-Title -->

    <div class="row">
        <div class="col-xs-12">
            <div class="bg-picture card-box">
                <div class="row">
                    <div class="col-xs-6 col-md-4 col-sm-4">
                        <h3 class="page-title">بيانات المستخدم</h3>
                    </div>
            
                    <div class="m-t-15 col-xs-6 col-md-8 col-sm-8 text-right">
                        <button type="button" class="btn btn-custom  waves-effect waves-light" onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i class="fa fa-reply"></i></span>
                        </button>
                    </div>
                   
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-detail">
                    
                    @if($user != null)
                        <div class="panel-body">

                            <div class="col-lg-3 col-xs-12">
                                <label>اسم المستخدم :</label>
                                <p>{{ $user->name }}</p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>الجوال :</label>
                                <p>{{ $user->phone }}</p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>البريد الالكترونى :</label>
                                <p>{{ $user->email }}</p>
                            </div>

                            <div class="col-lg-12 col-xs-12">
                                <label>أكواد الخصم :</label>
                                @if($user->coupons)
                                <table class="table table-striped table-hover table-condensed"
                                    style="width:100%">
                                    <tr>
                                        <th>كود الخصم</th>
                                        <th>نسبة الخصم</th>
                                        <th>تاريخ بداية الخصم</th>
                                        <th>تاريخ انتهاء الخصم</th>
                                        <th>عدد مرات استخدام الخصم</th>
                                        <th>عدد مرات الاستفادة من الخصم</th>
                                    </tr>
                                    @forelse($user->coupons as $item)
                                       @if($item->coupon)
                                        <tr>
                                            <th>{{ $item->coupon->code }}</th>
                                            <th>{{ $item->coupon->ratio }}</th>
                                            <th>{{ $item->coupon->from }}</th>
                                            <th>{{ $item->coupon->to }}</th>
                                            <th>{{ $item->coupon->times }}</th>
                                            <th>{{ $item->coupon_times }}</th>
                                        </tr>
                                        @endif
                                        
                                    @empty
                                        <p>لا توجد</p>
                                    @endforelse
                                    
                                </table>
                                @endif

                            </div>

                        </div>
                    @endif
                    </div>
                    <!-- end card-box-->
                </div>
            </div>
        </div>
    </div>
@endsection
