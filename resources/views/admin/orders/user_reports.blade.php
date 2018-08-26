@extends('admin.layouts.master')
@section('title','تقارير المستخدمين')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                       
                        <h4 class="page-title">تقارير المستخدمين</h4>
                    </div>
                </div>
                <div class="dropdown pull-right row">
                    
                    <!-- <a style="float: left; margin-right: 15px;" class="btn btn-danger btn-sm getSelected">
                        <i class="fa fa-trash" style="margin-left: 5px"></i> حذف المحدد
                    </a> -->
                    @if(isset($type) && $type == 'search')
                    <a href="{{route('orders.users_reports')}}" class="btn btn-primary btn-sm getSelected"><i class="fa fa-eye" style="margin-left: 5px"></i>عرض تقارير المستخدمين
                    </a>
                    @endif
                    <a class="btn btn-primary" href="{{route('orders.getExportUsers')}}">تصدير الى اكسل</a>
                </div>

                <form action="{{route('orders.search_users_reports')}}" method="get">
                    <!--{{csrf_field()}}-->
                    <div class="row form-group">
                        <div class="col-lg-3">
                             بحث ب  : 
                            <select name="type" class="form-control">
                                <option value="0">المستخدمين الذين قامو بالشراء</option>
                                <option value="1">المستخدمين الذين لم يقومو بالشراء </option>
                                <option value="2">المستخدمين الذين لم يكتمل طلب الشراء لأى سبب</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary">بحث</button>
                        </div>
                    </div>
                </form>
                    
                
                
                <h4 class="header-title m-t-0 m-b-30">عرض التقارير</h4>

                <table id="datatable-fixed-header" class="table table-striped table-hover table-condensed"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th>م</th>
                        <th>رقم الجوال</th>
                        <th>اسم المستخدم</th>
                        <th>العنوان</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 0 ; @endphp
                    @if(count($users) > 0)
                    @foreach($users as $row)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->address }}</td>
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
