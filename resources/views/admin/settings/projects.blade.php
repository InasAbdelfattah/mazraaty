@extends('admin.layouts.master')

@section('styles')
    <style>
        .radio.radio-inline {
            display: inline-flex;
            margin-bottom: 20px;
            padding: 0px;
        }
    </style>
@endsection
@section('content')

    <div id="messageError"></div>

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
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
            </div>
            <h4 class="page-title">ضبط المراكز</h4>
        </div>
    </div>
    <div class="row">


        <form data-parsley-validate novalidate method="POST" id="commentSettings"
              action="{{ route('administrator.settings.projects.comments') }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-lg-6">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">ضبط التعليقات</h4>


                    <p class="text-muted font-13 m-b-15 m-t-20">إعدادات التعليقات</p>
                    <div class="form-group">
                        <div class="radio radio-info radio-inline">
                            <input type="radio" class="showCompaniesSelectors" data-show="0" id="inlineRadio1"
                                   value="1" name="comment_setting" {{$setting->getBody('comment_setting') == 1 ? 'checked' : ''}}>
                            <label for="inlineRadio1">متاح لكل التطبيق</label>
                        </div>

                        <div class="radio radio-inline">
                            <input type="radio" class="showCompaniesSelectors" data-show="0" id="inlineRadio2"
                                   value="0" name="comment_setting" {{$setting->getBody('comment_setting') == 0 ? 'checked' : ''}}>
                            <label for="inlineRadio2"> إيقاف لكل التطبيق </label>
                        </div>

                        <div class="radio radio-default radio-inline">
                            <input type="radio" class="showCompaniesSelectors" data-show="1" id="inlineRadio3"
                                   value="2" name="comment_setting" {{$setting->getBody('comment_setting') == 2 ? 'checked' : '' }}>
                            <label for="inlineRadio3"> إيقاف على مركز او اكثر </label>
                        </div>
                    </div>


                    <p class="text-muted font-13 m-b-15 m-t-20">إعتماد التعليقات</p>
                    <div class="form-group">
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="inlineRadio4" value="1"
                                   @if($setting->getBody('comment_agree') == 1 ) checked @endif name="comment_agree"
                                   checked>
                            <label for="inlineRadio4">تظهر فى التطبيق بدون إعتماد</label>
                        </div>

                        <div class="radio radio-inline">
                            <input type="radio" id="inlineRadio5" value="0"
                                   @if($setting->getBody('comment_agree') == 0 ) checked @endif name="comment_agree">
                            <label for="inlineRadio5"> تحتاج إلى اعتماد قبل الظهور فى التطبيق </label>
                        </div>


                    </div>


                    {{--                    {{ $abilities }}--}}
                    <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }} b-t-20"
                         id="selectorsCompanies"
                         @if($setting->getBody('comment_setting') != 2 ) style="display: none;" @endif>
                        <label for="passWord2">الصلاحيات *</label>
                        <select multiple="multiple" class="multi-select" id="my_multi_select1" name="companies[]"
                                data-plugin="multiselect">

                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                        @if($company->is_comment == 1) selected @endif>{{ $company->name }}</option>
                            @endforeach

                        </select>

                        @if($errors->has('companies'))
                            <p class="help-block"> {{ $errors->first('companies') }}</p>
                        @endif

                    </div>


                    <div class="form-group text-right m-b-0 m-t-30">
                        <button class="btn btn-primary waves-effect waves-light m-t-30" type="submit"> حفظ البيانات
                        </button>
                        <button onclick="window.history.back();return false;"
                                class="btn btn-default waves-effect waves-light m-l-5 m-t-30"> إلغاء
                        </button>
                    </div>

                </div>
            </div><!-- end col -->
        </form>


        <form data-parsley-validate novalidate method="POST"
              action="{{ route('administrator.settings.projects.ratings') }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="col-lg-6">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">ضبط التقييمات</h4>

                    <p class="text-muted font-13 m-b-15 m-t-20">إعدادات التقييمات</p>
                    <div class="form-group">
                        <div class="radio radio-info radio-inline">
                            <input type="radio" class="showRatesSelectors" data-show="0"
                                   value="1" @if($setting->getBody('rate_setting') == 1 ) checked
                                   @endif id="inlineRadio11"
                                   name="rate_setting" checked>
                            <label for="inlineRadio11">متاح لكل التطبيق</label>
                        </div>

                        <div class="radio radio-inline">
                            <input type="radio" class="showRatesSelectors" data-show="0"
                                   value="0" @if($setting->getBody('rate_setting') == 0 ) checked
                                   @endif id="inlineRadio22"
                                   name="rate_setting">
                            <label for="inlineRadio22"> إيقاف لكل التطبيق </label>
                        </div>

                        <div class="radio radio-default radio-inline">
                            <input type="radio" class="showRatesSelectors" data-show="1"
                                   value="2" @if($setting->getBody('rate_setting') == 2 ) checked
                                   @endif id="inlineRadio33"
                                   name="rate_setting">
                            <label for="inlineRadio33"> إيقاف على مركز او اكثر </label>
                        </div>
                    </div>

                    <p class="text-muted font-13 m-b-15 m-t-20">إعتماد التقييمات</p>
                    <div class="form-group">
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="inlineRadio44" value="1"
                                   @if($setting->getBody('rate_agree') == 1 ) checked @endif name="rate_agree">
                            <label for="inlineRadio44">تظهر فى التطبيق بدون إعتماد</label>
                        </div>

                        <div class="radio radio-inline">
                            <input type="radio" id="inlineRadio55" value="0" name="rate_agree"
                                   @if($setting->getBody('rate_agree') == 0 ) checked @endif>
                            <label for="inlineRadio55"> تحتاج إلى اعتماد قبل الظهور فى التطبيق </label>
                        </div>
                    </div>


                    {{--                    {{ $abilities }}--}}
                    <div class="form-group{{ $errors->has('companies') ? ' has-error' : '' }} b-t-20"
                         id="selectorsRates"
                         @if($setting->getBody('rate_setting') != 2 ) style="display: none;" @endif>
                        <label for="passWord2">الصلاحيات *</label>
                        <select multiple="multiple" class="multi-select" id="my_multi_select1" name="companies[]"
                                data-plugin="multiselect">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                        @if($company->is_rate == 1) selected @endif>{{ $company->name }}</option>
                            @endforeach
                        </select>

                        @if($errors->has('companies'))
                            <p class="help-block"> {{ $errors->first('companies') }}</p>
                        @endif

                    </div>


                    <div class="form-group text-right m-b-0 m-t-30">
                        <button class="btn btn-primary waves-effect waves-light m-t-30" type="submit"> حفظ البيانات
                        </button>
                        <button onclick="window.history.back();return false;"
                                class="btn btn-default waves-effect waves-light m-l-5 m-t-30"> إلغاء
                        </button>
                    </div>

                </div>
            </div><!-- end col -->
        </form>
    </div>
    <!-- end row -->



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
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    //  $('#messageError').html(data.message);


                    if (data.status == true) {
                        var shortCutFunction = 'success';
                        var msg = data.message;
                        var title = 'نجاح';
                    } else {

                        var shortCutFunction = 'error';
                        var msg = data.message;
                        var title = 'خطأ';

                    }

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


        $('.showCompaniesSelectors').on('change', function () {
            var show = $(this).attr('data-show');

            if (show == 1) {
                $('#selectorsCompanies').fadeIn(1000);
            } else {
                $('#selectorsCompanies').fadeOut(1000);
            }

        });


        $('.showRatesSelectors').on('change', function () {
            var show = $(this).attr('data-show');

            if (show == 1) {
                $('#selectorsRates').fadeIn(1000);
            } else {
                $('#selectorsRates').fadeOut(1000);
            }

        });
    </script>
@endsection




