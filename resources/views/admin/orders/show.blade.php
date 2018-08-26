@extends('admin.layouts.master')

@section('content')

    <!-- Page-Title -->

    <div class="row">
        <div class="col-xs-12">
            <div class="bg-picture card-box">
                <div class="row">
                    <div class="col-xs-6 col-md-4 col-sm-4">
                        <h3 class="page-title">بيانات الطلب</h3>
                    </div>
            
                    <div class="m-t-15 col-xs-6 col-md-8 col-sm-8 text-right">
                        <button type="button" class="btn btn-custom  waves-effect waves-light" onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i class="fa fa-reply"></i></span>
                        </button>
                    </div>
                   
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-detail">
                        {{--<h3 class="m-t-0 m-b-0">بيانات الطلب</h3>--}}

<!--  `doc_photo`, `username`, `phone`, `phone2`, `delivered_time`, `address`, `card_id`, `user_id`, `payment_method`, `status`, `refuse_reason` -->

                        <div class="panel-body">

                            <div class="col-lg-3 col-xs-12">
                                <label>رقم الطلب :</label>
                                <p>{{ $order->id }}</p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>اسم البطاقة :</label>
                                <p>@if($card) {{ $card->name_ar }} @else -- @endif </p>
                            </div>
                            
                            <div class="col-lg-3 col-xs-12">
                                <label> نوع البطاقة :</label>
                                <p>@if($type) {{ $type->name_ar }} @else -- @endif </p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>اسم المستخدم :</label>
                                <p>{{ $order->username }}</p>
                            </div>
                            
                            @if($order->promo_code != null)
                            <div class="col-lg-3 col-xs-12">
                                <label>الرمز الترويجى:</label>
                                <p>{{ $order->promo_code}}</p>
                            </div>
                            @endif

                            @if($order->emp_code != null)
                            <div class="col-lg-3 col-xs-12">
                                <label>رقم الموظف:</label>
                                <p>{{ $order->emp_code }}</p>
                            </div>
                            @endif

                            <div class="col-lg-3 col-xs-12">
                                <label>مكان التسليم :</label>
                                <p>{{ $order->address }}</p>
                            </div>
                            
                            <!--<div class="col-lg-3 col-xs-12">-->
                            <!--    <label> تاريخ التسليم:</label>-->
                            <!--    <p>{{ $order->delivered_time }}</p>-->
                            <!--</div>-->

                            <div class="col-lg-3 col-xs-12">
                                <label> جوال المستخدم :</label>
                                <p>{{ $order->phone }}</p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>رقم الإثبات :</label>
                                <p>{{ $order->proofnumber }}</p>
                            </div>
                            
                            <div class="col-lg-3 col-xs-12">
                                <label>الجنس :</label>
                                <p>{{ $order->gender }}</p>
                            </div>
                            
                            <div class="col-lg-3 col-xs-12">
                                <label>تاريخ الميلاد :</label>
                                <p>{{ $order->birth_date }}</p>
                            </div>
       
                            <div class="col-lg-3 col-xs-12">
                                <label>تاريخ الطلب :</label>
                                <p> {{ $order->created_at }}</p>
                            </div>
                            
                            <div class="col-lg-3 col-xs-12">
                            <label>حالة الطلب :</label>
                            @if($order->status ==1 )
                                <p>مقبول</p>
                            @elseif($order->status ==3 )
                                <p>تجديد</p>
                            @elseif($order->status == 2)
                                <p>مرفوض</p>
                                <label>سبب الرفض :</label>
                                <p>{{$order->refuse_reason}}</p>
                            @elseif($order->status == 0)
                                <p>جديد</p>
                            @endif
                        </div>
                            
                            <div class="col-lg-6 col-xs-12">
                                <label>عنوان التسليم</label>
                                <p>
                                    <iframe width="100%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q={{$order->lat}},{{$order->lng}}&z=10&output=embed"></iframe>
                                </p>
                            </div>

                            <!--<div class="col-lg-6 col-xs-12">-->
                            <!--    <label> <p>صورة الهوية :</label>-->
                                
                            <!--    @if($order->doc_photo)-->
                            <!--    <a data-fancybox="gallery"-->
                            <!--       href="{{ url('files/docs/' . $order->doc_photo) }}">-->
                            <!--        <img class="img-thumbnail" src="{{ url('files/docs/' . $order->doc_photo) }}"/>-->
                            <!--    </a>-->
                            <!--    @else-->
                            <!--    <img class="img-thumbnail" src="{{ request()->root().'/assets/admin/custom/images/default.png' }}"/>-->
                            <!--    @endif-->
    
                            <!--</div>-->

                        <div class="col-lg-3 col-xs-12">
                            <label>طريقة الدفع :</label>
                            <p>
                                @if($order->payment_method == 0) تحويل بنكى
                                @elseif($order->payment_method == 1) بطاقة ائتمان
                                @else دفع عند الاستلام
                                @endif
                            </p>
                        </div>
                        @if($order->payment_method == 0 )
                        <div class="col-lg-3 col-xs-12">
                            <label>بيانات الدفع</label>
                            @if($order->payment_status == 1 )
                            <p>اسم المستخدم : {{$payment->username}}</p>
                            <p>رقم حساب المستخدم : {{$payment->user_account}}</p>
                            <p>المبلغ المحول :{{$payment->transferred_money}}</p>
                            @php $bank = \App\BankAccount::find($payment->bank_id); @endphp
                            <p>البنك المحول اليه : {{$bank->name_ar}}</p>
                            <p>وصل التحويل :
                                <a data-fancybox="gallery"
                                   href="{{ url('files/banks/' . $payment->pay_photo) }}">
                                    <img class="img-thumbnail" src="{{ url('files/banks/' . $payment->pay_photo) }}"/>
                                </a>
                            </p>
                            @else
                            <p>
                            لم يتم الدفع
                            </p>
                            @endif
                        </div>
                        @endif

                        </div>
                    </div>
                    <!-- end card-box-->


                </div>
            </div>
        </div>

    </div>


@endsection
