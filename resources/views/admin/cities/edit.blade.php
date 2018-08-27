@extends('admin.layouts.master')
@section('title','تعديل المدينة')
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
                       aria-expanded="false"> مشاهدة جميع المدن
                        <span class="m-l-5">
                        <i class="fa fa-backward"></i>
                    </span>
                    </a>

                </div>
                <h4 class="page-title">المدن</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30"> تعديل المدينة : {{ $city->name }} </h4>

                    <div class="form-group">
                        <label for="userName">اسم المدينة*</label>
                        <input type="text" name="name" parsley-trigger="change" required
                               placeholder=" ادخل اسم المدينة..." class="form-control title"
                               value="{{ $city->name or old('name') }}"
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




