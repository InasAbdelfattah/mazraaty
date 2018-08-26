@extends('admin.layouts.master')
@section('title','التقارير المالية')
@section('content')

    <!-- Page-Title -->
    


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                       
                        <h4 class="page-title">التقارير المالية</h4>
                    </div>
                </div>
                <div class="dropdown pull-right row">
                    
                    <!-- <a style="float: left; margin-right: 15px;" class="btn btn-danger btn-sm getSelected">
                        <i class="fa fa-trash" style="margin-left: 5px"></i> حذف المحدد
                    </a> -->
                    @if(isset($type) && $type == 'search')
                    <a href="{{route('orders.financial_reports')}}" class="btn btn-primary btn-sm getSelected"><i class="fa fa-eye" style="margin-left: 5px"></i>عرض التقارير
                    </a>
                    @endif
                    <a class="btn btn-primary" href="{{route('orders.getExport')}}">تصدير الى اكسل</a>
                </div>

                <form action="{{route('orders.search_reports')}}" method="get">
                    <!--{{csrf_field()}}-->
                    <div class="row form-group">
                        <div class="col-lg-3">
                            <!--نوع البطاقة : -->
                            <select id="recordNumber" name="card_type" class="form-control">
                                <option value="">نوع البطاقة  {{old('card_type')}}</option>
                                @forelse($categories as $cat)
                                    <option value="{{$cat->id}}" {{old('card_type') == $cat->id ? 'selected' : ''}}>{{$cat->name}}</option>
                                @empty
                                    <option value="">لا يوجد</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <!--المستخدم : -->
                            <select id="recordNumber" name="user" class="form-control">
                                <option value="">المستخدم</option>
                                @forelse($users as $user)
                                    <option value="{{$user->id}}" {{old('user') == $user->id ? 'selected' : ''}}>{{$user->name}}</option>
                                @empty
                                    <option value="">لا يوجد</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">

                        <div class="col-lg-3">
                            وسيلة الدفع : 
                            <select id="recordNumber" name="payment_method" class="form-control">
                                <option value="" disabled selected>وسيلة الدفع</option>
                                <option value="0">تحويل بنكى</option>
                                <option value="1">بطاقة ائتمان</option>
                                <option value="2">الدفع عند الاستلام</option>
                               
                            </select>
                        </div>

                        <div class="col-lg-3">
                            نوع الدفع : 
                            <select id="recordNumber" name="payment_status" class="form-control">
                                <option value="" selected>نوع الدفع</option>
                                <option value="1">سدد</option>
                                <option value="0">لم يسدد</option>
                               
                            </select>
                        </div>
                        
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-lg-3">وقت الطلب : </div>
                            @php 
                                $old = date('Y-m-d', strtotime('-5days'));
                                $new = date("Y-m-d"); 
                            @endphp
                        <div class="col-lg-3">
                            <label>من : </label>
                            <input type="date" name="from" value="{{$old}}" class="form-control" placeholder="من : "/>
                        </div>

                        <div class="col-lg-3">
                            <label>الى :</label>
                            
                            <input type="date" name="to" value="{{$new}}" class="form-control" placeholder="الى"/>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary">بحث</button>
                        </div>
                    </div>
                </form>
                    
                
                
                <h4 class="header-title m-t-0 m-b-30">عرض التقارير</h4>

                <!--<table id="datatable-fixed-header" class="table table-striped table-hover table-condensed"-->
                <!--       style="width:100%">-->
                <table class="table table-striped" style="width:100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>رقم الجوال</th>
                        <th>اسم المستخدم</th>
                        <th>الرمز الترويجى</th>
                        <th>كود الموظف</th>
                        <!--<th>المنطقة</th>-->
                        <th>نوع الدفع</th>
                        <th>تاريخ الدفع</th>
                        <th>حالة الدفع</th>
                        <th>العمليات المتاحة</th>

                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 0 ; @endphp
                    @if(count($orders) > 0)
                    @foreach($orders as $row)
                        <tr>
                            <td>{{$i++}}

                                <!-- <div class="checkbox checkbox-primary checkbox-single">
                                    <input type="checkbox" class="checkboxes-items"
                                           value="{{ $row->id }}"
                                           aria-label="Single checkbox Two">
                                    <label></label>
                                </div> -->
                            </td>

                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->user_name }}</td>
                            <td>{{ $row->promo_code }} </td>
                            <td> {{$row->emp_code}} </td>
                            <!--<td></td>-->

                            <td>
                            @if($row->payment_method == 0)
                            تحويل بنكى
                            @elseif($row->payment_method == 1)
                            بطاقة ائتمان
                            @else
                            دفع عند الاستلام
                            @endif
                            </td>
                            <td>{{ $row->created_at }}</td>
                            <td>{{ $row->payment_status == 0 ? 'لم يسدد' : 'سدد' }}</td>
                            
                            <td>
                                <a href="{{ route('orders.show', $row->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-info m-b-5">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <!-- <a href="javascript:;" id="elementRow{{ $row->id }}"
                                   data-id="{{ $row->id }}"
                                   class="removeElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                    <i class="fa fa-remove"></i>
                                </a> -->

                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>


{{--                {{ $companies->links() }}--}}
        </div>
            </div>
        </div><!-- end col -->

    </div>
    <!-- end row -->
@endsection
