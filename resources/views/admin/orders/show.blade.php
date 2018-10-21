@extends('admin.layouts.master')
@section('title','تفاصيل الطلب')
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
                    
                    @if($order != null)
                        <div class="panel-body">

                            <div class="col-lg-3 col-xs-12">
                                <label>رقم الطلب :</label>
                                <p>{{ $order->id }}</p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>اسم المستخدم :</label>
                                <p>{{ $order->user_name }}</p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>المدينة :</label>
                                <p>{{ $order->city }}</p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>مكان التسليم :</label>
                                <p>{{ $order->user_address }}</p>
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
                            
                            <!-- <div class="col-lg-6 col-xs-12">
                                <label>عنوان التسليم</label>
                                <p>
                                    <iframe width="100%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q={{$order->lat}},{{$order->lng}}&z=10&output=embed"></iframe>
                                </p>
                            </div> -->

                            <div class="col-lg-12 col-xs-12">
                                <label>اتفاصيل الطلبية :</label>
                                @if($order->items)
                                <table class="table table-striped table-hover table-condensed"
                                    style="width:100%">
                                    <tr style="border: 1px solid #797979;">
                                        <th >تصنيف المنتج</th>
                                        <th>اسم المنتج</th>
                                        <th>النوع</th>
                                        <th>عدد الوحدات</th>
                                        <th>سعر الوحدة</th>
                                        <th>الاجمالى</th>
                                    </tr>
                                    @forelse($order->items as $item)
                                       
                                        <tr>
                                            <th>{{category($item->category_id)}}</th>
                                            <th>{{ $item->product_name }}</th>
                                            <th>{{ $item->type == 'offer' ? 'عرض' : 'بدون عرض'}}</th>
                                            <th>{{$item->amount}}</th>
                                            <!-- <th>{{ $item->type == 'product' ? $item->product_price : $item->offer_price}}</th>

                                            <th>{{ $item->type == 'product' ? $item->product_price* $item->amount : $item->offer_price * $item->amount}}</th> -->
                                            <th>{{ $item->item_price }}</th>
                                            <th>{{ $item->item_price * $item->amount}}</th>
                                        </tr>
                                        
                                        
                                    @empty
                                        <p>لا توجد</p>
                                    @endforelse
                                    <tr style="background: #797979; ">
                                        <th colspan="6"></th>
                                    </tr>
                                    <tr style="border: 1px solid #797979;">
                                        <th>تكلفة الطلب الاجمالية</th>
                                        <th>القيمة المضافة</th>
                                        <th>تكلفة التوصيل</th>
                                        <th>الخصم</th>
                                        <th colspan="3">تكلفة الطلب بعد الخصم</th>
                                    </tr>
                                    <tr>
                                        <th>{{$order->price}}</th>
                                        <th>{{$order->tax}}</th>
                                        <th>{{$order->delivery}}</th>
                                        <!-- <th>{{setting()->getBody('delivery')}}</th> -->
                                        <th>{{$order->discount}}</th>
                                        <!-- <th colspan="3">{{$order->price + setting()->getBody('delivery') + setting()->getBody('tax') - $order->discount}}</th> -->
                                        <th colspan="3">{{$order->total_price }}</th>
                                    </tr>
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
