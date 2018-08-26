@extends('admin.layouts.master')

@section('content')

    <div id="messageError"></div>
    <form data-parsley-validate novalidate method="POST" action="{{ route('administrator.settings.store') }}"
          enctype="multipart/form-data">
    {{ csrf_field() }}
    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <!-- <div class="btn-group pull-right m-t-15">
                    <button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light"
                            data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5">
                            <i class="fa fa-cog"></i>
                        </span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                </div> -->
                <h4 class="page-title">أنواع المنشأت</h4>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-9">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">إضافة نوع جديد</h4>

                    <div class="form-group">
                        <label for="userName">الاسم*</label>
                        <input type="text" name="name" parsley-trigger="change" required
                               placeholder="ادخل الاسم لنوع المنشأة..." class="form-control"
                               id="userName"
                               data-parsley-required-message="هذا الحقل إلزامي">
                    </div>


                    <div class="form-group">
                        <label for="pass1">الرئيسي*</label>
                        <select class="form-control select2" name="parent">
                            <option value="">الرئيسيى</option>
                            @foreach($cats as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach

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

            <div class="col-lg-3">
                <div class="card-box" style="overflow: hidden;">

                    <h4 class="header-title m-t-0 m-b-30">الصورة</h4>

                    <div class="form-group">
                        <input type="file" name="image" class="dropify" data-max-file-size="6M"/>
                    </div>

                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </form>


@endsection


@section('scripts')
    <script type="text/javascript">

        $('form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    //  $('#messageError').html(data.message);

                    var shortCutFunction = 'success';
                    var msg = data.message;
                    var title = 'نجاح';
                    toastr.options = {
                        positionClass: 'toast-top-left',
                        onclick: null
                    };
                    var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                    $toastlast = $toast;
                    {{--setTimeout(function () {--}}
                    {{--window.location.href = '{{ route('categories.index') }}';--}}
                    {{--}, 3000);--}}
                },
                error: function (data) {

                }
            });
        });

    </script>
@endsection




