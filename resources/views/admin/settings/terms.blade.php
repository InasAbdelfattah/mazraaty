@extends('admin.layouts.master')

@section('content')
    <form action="{{ route('administrator.settings.store') }}" method="post" data-parsley-validate novalidate enctype="multipart/form-data">

    {{ csrf_field() }}

    <!-- Page-Title -->

        <div class="row">
            <div class="col-sm-12">
                <!-- <div class="btn-group pull-right m-t-15">
                    <button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light"
                            data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i
                                    class="fa fa-cog"></i></span></button>

                </div> -->
                <h4 class="page-title">ضبط بنود استخدام</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">


                    <div id="errorsHere"></div>

                    <h4 class="header-title m-t-0 m-b-30">بيانات بنود الإستخدام</h4>

                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('terms') ? 'has-error' : '' }}">
                            <label for="terms">المحتوي</label>
                            <textarea id="editor" name="terms" value="{{setting()->getBody('terms')}}" class="form-control" required>
                                {{ setting()->getBody('terms') }}
                            </textarea>
                        </div>

                    </div>

                    <div class="form-group text-right m-t-20">
                        <button class="btn btn-primary waves-effect waves-light m-t-20" type="submit">
                            حفظ البيانات
                        </button>
                        <button onclick="window.history.back();return false;" type="reset"
                                class="btn btn-default waves-effect waves-light m-l-5 m-t-20">
                            إلغاء
                        </button>
                    </div>

                </div>
            </div><!-- end col -->

            {{--<div class="col-lg-4">--}}
                {{--<div class="card-box" style="overflow: hidden;">--}}
                    {{--<h4 class="header-title m-t-0 m-b-30">الصورة </h4>--}}
                    {{--<div class="form-group">--}}
                        {{--<div class="col-sm-12">--}}

                            {{--<input type="hidden" name="about_app_image_old"--}}
                                   {{--value="{{ setting()->getBody('about_app_image') }}">--}}
                            {{--<input type="file" name="about_app_image" class="dropify" data-max-file-size="6M"--}}
                                   {{--data-default-file="{{ request()->root() . '/' . $setting->getBody('about_app_image') }}"/>--}}

                        {{--</div>--}}
                    {{--</div>--}}

                {{--</div>--}}
            {{--</div><!-- end col -->--}}
        </div>
        <!-- end row -->
    </form>
@endsection

@section('scripts')
    <!--<script src="https://cdn.ckeditor.com/4.7.0/full/ckeditor.js"></script>-->
    <script>
        CKEDITOR.replace( 'terms_en' );
        CKEDITOR.replace( 'terms_ar' );
    </script>
@endsection