@extends('admin.layouts.master')
@section('title', 'تفاصيل الاشعار')
@section('content')

    
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="btn-group pull-right m-t-15">
                    <a href="{{ route('notifs') }}" type="button" class="btn btn-custom waves-effect waves-light"
                       aria-expanded="false"> مشاهدة الاشعارات
                        <span class="m-l-5">
                        <i class="fa fa-backward"></i>
                    </span>
                    </a>

                </div>
                <h4 class="header-title m-t-0 m-b-30">بيانات الاشعار</h4>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">تاريخ الاشعار</label>
                            <p>{{ $data->created_at }}</p>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">فئة الاشعار</label>
                            <p>{{ $data->push_type =='global' ? 'جماعى' : 'بناء على مدينة'}}</p>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">نص الاشعار</label>
                            <p>{{ $data->body }}</p>
                        </div>
                    </div>
                </div>
              
            </div>

        </div><!-- end col -->

    </div>
    <!-- end row -->

@endsection






