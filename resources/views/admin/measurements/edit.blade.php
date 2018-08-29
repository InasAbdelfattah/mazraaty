@extends('admin.layouts.master')
@section('title','تعديل وحدة قياس')
@section('content')

    <div id="messageError"></div>
    <form data-parsley-validate novalidate method="POST" action="{{ route('measurementUnits.update', $measurement->id) }}"
          enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">
                    <a href="{{ route('measurementUnits.index') }}" type="button" class="btn btn-custom waves-effect waves-light"
                       aria-expanded="false"> مشاهدة جميع وحدات القياس
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
                    <h4 class="header-title m-t-0 m-b-30"> تعديل وحدة القياس : {{ $measurement->name }} </h4>

                    <div class="form-group">
                        <label for="userName">اسم وحدة القياس*</label>
                        <input type="text" name="name" parsley-trigger="change" required
                               placeholder=" ادخل اسم وحدة القياس..." class="form-control title"
                               value="{{ $measurement->name or old('name') }}"
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
                            <option value="1" {{$measurement->status == 1 ? 'selected' : ''}}>
                                مفعل
                            </option>

                            <option value="0" {{$measurement->status == 0 ? 'selected' : ''}}>
                                معطل
                            </option>                                
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
            </div><!-- end col -->

        </div>
        <!-- end row -->
    </form>


@endsection

