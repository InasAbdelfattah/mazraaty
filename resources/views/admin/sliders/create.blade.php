@extends('admin.layouts.master')

@section('title', 'إضافة شاشة من التطبيق')

@section('content')

    <div id="messageError"></div>
    <form data-parsley-validate novalidate method="POST"
          action="{{ route('sliders.store') }}"
          enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="type" value="0"/>
    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">


                    <button type="button" class="btn btn-custom  waves-effect waves-light"
                            onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i
                                    class="fa fa-reply"></i></span>
                    </button>


                </div>
                <h4 class="page-title">شاشات التطبيق</h4>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-4">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">إضافة شاشة</h4>

                    

                            <label>الصورة</label>

                            <div class="form-group">
                                <input type="file" name="image" class="dropify" data-max-file-size="6M"/>
                                @if($errors->has('image'))
                                <p class="help-block">
                                    {{ $errors->first('image') }}
                                </p>
                            @endif
                            </div>

    
                    <div class="form-group text-right m-b-0 ">
                        <button class="btn btn-primary waves-effect waves-light m-t-20" type="submit"> حفظ البيانات
                        </button>
                        <button onclick="window.history.back();return false;"
                                class="btn btn-default waves-effect waves-light m-l-5 m-t-20"> إلغاء
                        </button>
                    </div>

                </div>
            </div><!-- end col -->

            
        </div>
        <!-- end row -->
    </form>


@endsection


@section('scripts')
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
@endsection




