@extends('admin.layouts.master')
@section('title','تعديل القسم الرئيسى')
@section('content')

    <div id="messageError"></div>
    <form data-parsley-validate novalidate method="POST" action="{{ route('categories.update', $category->id) }}"
          enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">
                    <a href="{{ route('categories.index') }}" class="btn btn-custom  waves-effect waves-light">
                        <span class="m-l-5">
                            <i class="fa fa-eye"></i> <span>عرض الأقسام الرئيسية</span> </span>
                    </a>
                </div>
                <h4 class="page-title">الأقسام الرئيسية</h4>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-8">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">تعديل القسم الرئيسى</h4>

                    <div class="form-group">
                        <label for="userName"> الاسم*</label>
                        <input type="text" name="name" value="{{ $category->name or old('name') }}" parsley-trigger="change" required
                               placeholder="ادخل الاسم..." class="form-control title"
                               id="userName">
                    </div>
                  

                    <div class="form-group">
                        <label for="pass1">الحالة *</label>
                        <select class="form-control select2" name="status">
                            <option value="1" {{$category->status == 1 ? 'selected' : ''}}>مفعل</option>
                            <option value="0" {{$category->status == 0 ? 'selected' : ''}}> معطل</option>
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

            <div class="col-lg-4">
                <div class="card-box" style="overflow: hidden;">

                    <h4 class="header-title m-t-0 m-b-30">الصورة</h4>

                    <div class="form-group">
                        <input type="file" name="image" data-default-file="{{request()->root() . '/files/categories/'. $category->image}}" class="dropify"
                               data-max-file-size="6M"/>
                    </div>

                </div>
            </div>
        </div>
        <!-- end row -->
    </form>


@endsection






