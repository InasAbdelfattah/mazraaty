@extends('admin.layouts.master')
@section('title', 'بيانات المنطقة الرئيسية')
@section('content')


    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">

            <h4 class="page-title">بيانات المنطقة الرئيسية</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="btn-group pull-right m-t-15">
                    <a href="{{ route('cities.index') }}" type="button" class="btn btn-custom waves-effect waves-light"
                       aria-expanded="false"> مشاهدة جميع المناطق الرئيسية
                        <span class="m-l-5">
                        <i class="fa fa-backward"></i>
                    </span>
                    </a>

                </div>
                <h4 class="header-title m-t-0 m-b-30">بيانات المنطقة الرئيسية</h4>
                
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">الاسم باللغة العربية</label>
                            <p>{{ $city->name_ar }}</p>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">الاسم باللغة الانجليزية</label>
                            <p>{{ $city->name_en }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userPhone">الوصف باللغة العربية</label>
                            <p>{!! $city->description_ar !!}</p>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userPhone">الوصف باللغة الانجليزية</label>
                            <p>{!! $city->description_en !!}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                    <div class="form-group">
                        <label for="pass1">حالة التفعيل*</label>
                        <p>{{$city->status == 1 ? 'مفعل' : 'معطل'}}</p>

                    </div>
                </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-6">
                    <div class="text-center">
                            @if( $city->image)
                            <a data-fancybox="gallery"
                               href="{{ url('files/areas/' . $city->image) }}">
                                <img class="img-thumbnail" src="{{ url('files/areas/' . $city->image) }}"/>
                            </a>
                            @else
                            <img class="img-thumbnail" src="{{ request()->root().'/assets/admin/custom/images/default.png' }}"/>
                            @endif
                        </div>
                        </div>
                </div>

            </div>

        </div><!-- end col -->

    </div>
    <!-- end row -->

@endsection






