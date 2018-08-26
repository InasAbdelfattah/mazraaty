@extends('admin.layouts.master')

@section('content')

    <div id="messageError"></div>
    <form data-parsley-validate novalidate method="POST"
          action="{{ route('sliders.update', $slider->id)}}"
          enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">

                    <a href="{{ route('sliders.create') }}" class="btn btn-custom  waves-effect waves-light">
                    <span class="m-l-5">
                        <i class="fa fa-plus"></i> <span>إضافة</span> </span>
                    </a>

                </div>
                <h4 class="page-title">أنواع المنشأت</h4>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-4">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">تعديل شاشة</h4>

                    <label>الصورة</label>

                    <div class="form-group">
                        <input type="file" name="image" data-default-file="{{ request()->root().'/files/sliders/'.$slider->image }}" class="dropify" data-max-file-size="6M"/>
                        @if($errors->has('image'))
                        <p class="help-block">
                            {{ $errors->first('image') }}
                        </p>
                    @endif
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






