@extends('admin.layouts.master')
@section('title','تعديل منطقة رئيسية')
@section('content')

    <div id="messageError"></div>
    <form data-parsley-validate novalidate method="POST" action="{{ route('cities.update', $city->id) }}"
          enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">
                    <a href="{{ route('cities.index') }}" type="button" class="btn btn-custom waves-effect waves-light"
                       aria-expanded="false"> مشاهدة جميع المناطق الرئيسية
                        <span class="m-l-5">
                        <i class="fa fa-backward"></i>
                    </span>
                    </a>

                </div>
                <h4 class="page-title">المناطق الرئيسية</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30"> تعديل المنطقة الرئيسية : {{ $city->name_ar }} </h4>

                    <div class="form-group">
                        <label for="userName">اسم المنطقة عربى*</label>
                        <input type="text" name="name_ar" parsley-trigger="change" required
                               placeholder=" ادخل اسم المنطقة عربى..." class="form-control title"
                               value="{{ $city->name_ar or old('name_ar') }}"
                               id="userName">

                        @if ($errors->has('name_ar'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('name_ar') }}</strong>
                           </span>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="userName"> اسم المنطقة انجليزى*</label>
                        <input type="text" name="name_en" parsley-trigger="change" required
                               placeholder=" ادخل اسم المنطقة انجليزى..." class="form-control title"
                               value="{{ $city->name_en or old('name_en') }}"
                               id="userName">

                        @if ($errors->has('name_en'))
                            <span class="help-block">
                                <strong> {{ $errors->first('name_en') }} </strong>
                           </span>
                        @endif

                    </div>


                    <div class="form-group{{ $errors->has('description_ar') ? ' has-error' : '' }}">
                        <label for="userName"> الوصف باللغة العربية*</label>
                        <textarea type="text" name="description_ar" parsley-trigger="change" required
                               placeholder=" ادخل الوصف باللغة العربية.." class="form-control description" value="{{ $city->description_ar or old('description_ar') }}"
                               id="userName">{{ $city->description_ar or old('description_ar') }}</textarea>

                        @if ($errors->has('description_ar'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description_ar') }}</strong>
                            </span>
                        @endif

                    </div>


                    <div class="form-group{{ $errors->has('description_en') ? ' has-error' : '' }}">
                        <label for="userName"> الوصف باللغة الانجليزية*</label>
                        <textarea name="description_en" parsley-trigger="change" required
                               placeholder=" ادخل الوصف باللغة الانجليزية..." class="form-control description" value="{{ $city->description_en or old('description_en') }}"
                               id="userName">{{ $city->description_en or old('description_en') }}</textarea>

                        @if ($errors->has('description_en'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description_en') }}</strong>
                            </span>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="pass1">حالة التفعيل*</label>
                        <select class="form-control select2" name="status">
                            <option value="1" {{$city->status == 1 ? 'selected' : ''}}>
                                تفعيل
                            </option>

                            <option value="1" {{$city->status == 0 ? 'selected' : ''}}>
                                تعطيل
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
            
            <div class="col-lg-4">
                <div class="card-box" style="overflow: hidden;">

                    <h4 class="header-title m-t-0 m-b-30">الصورة</h4>

                    <div class="form-group">
                        <input type="file" name="image" data-default-file="{{request()->root() . '/files/areas/'. $city->image}}" class="dropify"
                               data-max-file-size="6M"/>
                    </div>

                </div>
            </div>

        </div>
        <!-- end row -->
    </form>


@endsection

@section('scripts')
    <!--<script src="https://cdn.ckeditor.com/4.7.0/full/ckeditor.js"></script>-->
    <!--<script>-->
    <!--    CKEDITOR.replace( 'description_ar' );-->
    <!--    CKEDITOR.replace( 'description_en' );-->
    <!--</script>-->
@endsection


{{--@section('scripts')--}}
{{--<script type="text/javascript">--}}

{{--$('form').on('submit', function (e) {--}}
{{--e.preventDefault();--}}
{{--var formData = new FormData(this);--}}
{{--$.ajax({--}}
{{--type: 'POST',--}}
{{--url: $(this).attr('action'),--}}
{{--data: formData,--}}
{{--cache: false,--}}
{{--contentType: false,--}}
{{--processData: false,--}}
{{--success: function (data) {--}}

{{--//  $('#messageError').html(data.message);--}}

{{--var shortCutFunction = 'success';--}}
{{--var msg = data.message;--}}
{{--var title = 'نجاح';--}}
{{--toastr.options = {--}}
{{--positionClass: 'toast-top-left',--}}
{{--onclick: null--}}
{{--};--}}
{{--var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists--}}
{{--$toastlast = $toast;--}}
{{--setTimeout(function () {--}}
{{--window.location.href = '{{ route('categories.index') }}';--}}
{{--}, 3000);--}}
{{--},--}}
{{--error: function (data) {--}}

{{--}--}}
{{--});--}}
{{--});--}}

{{--</script>--}}
{{--@endsection--}}




