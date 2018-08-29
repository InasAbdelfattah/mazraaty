@extends('admin.layouts.master')
@section('title','اضافة وحدة قياس')
@section('content')

    <div id="messageError"></div>
    <form data-parsley-validate novalidate method="POST" action="{{ route('measurementUnits.store') }}"
          enctype="multipart/form-data">
    {{ csrf_field() }}
    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">
                    <a href="{{ route('measurementUnits.index') }}" type="button" class="btn btn-custom waves-effect waves-light"
                       aria-expanded="false"> مشاهدة وحدات القياس
                        <span class="m-l-5">
                        <i class="fa fa-backward"></i>
                    </span>
                    </a>

                </div>
                <h4 class="page-title">وحدات القياس</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">إضافة وحدة قياس</h4>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="userName">الاسم*</label>
                        <input type="text" name="name" parsley-trigger="change" required
                               placeholder=" ادخل الاسم..." class="form-control title" value="{{ old('name') }}"
                               id="userName">

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="pass1">حالة التفعيل*</label>
                        <select class="form-control select2" name="status">
                            <option value="1">مفعل</option>
                            <option value="0">معطل</option>                                
                        </select>
                    </div>


                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit"> حفظ البيانات
                        </button>
                        <button onclick="window.history.back();return false;"
                                class="btn btn-default waves-effect waves-light m-l-5"> إلغاء
                        </button>
                    </div>

                </div>
                
            </div>
            <!-- <div class="col-lg-4">
            <div class="card-box" style="overflow: hidden;">

                <h4 class="header-title m-t-0 m-b-30">الصورة</h4>

                <div class="form-group">
                    <input type="file" name="image" class="dropify" data-max-file-size="6M"/>
                </div>

            </div>
            </div> -->

        </div>
        <!-- end row -->
    </form>


@endsection
