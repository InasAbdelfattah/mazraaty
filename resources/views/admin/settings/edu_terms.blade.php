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
                <h4 class="page-title">ضبط بنود استخدام بطاقات الشبكة التعليمية</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">


                    <div id="errorsHere"></div>
                    <!-- <div class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                            <i class="zmdi zmdi-more-vert"></i>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div> -->

                    <h4 class="header-title m-t-0 m-b-30">بيانات بنود الإستخدام</h4>


                    <!--<div class="col-xs-12">-->
                    <!--    <div class="form-group">-->
                    <!--        <label for="userName">العنوان *</label>-->
                    <!--        <input type="text" name="terms_title_ar"-->
                    <!--               value="{{ $setting->getBody('terms_title_ar') }}" class="form-control"-->
                    <!--               required-->
                    <!--               placeholder="العنوان ..."/>-->
                    <!--        <p class="help-block"></p>-->

                    <!--    </div>-->

                    <!--</div>-->

                    <!--<div class="col-xs-12">-->
                    <!--    <div class="form-group">-->
                    <!--        <label for="userName">العنوان - انجليزى*</label>-->
                    <!--        <input type="text" name="terms_title_en"-->
                    <!--               value="{{ $setting->getBody('terms_title_en') }}" class="form-control"-->
                    <!--               required-->
                    <!--               placeholder="العنوان ..."/>-->
                    <!--        <p class="help-block"></p>-->

                    <!--    </div>-->

                    <!--</div>-->


                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('edu_terms_ar') ? 'has-error' : '' }}">
                            <label for="edu_terms_ar">المحتوي</label>
                            <textarea id="editor" name="edu_terms_ar" value="{{$setting->getBody('edu_terms_ar')}}" class="form-control" required>
                                {{ $setting->getBody('edu_terms_ar') }}
                            </textarea>
                        </div>

                    </div>

                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('edu_terms_en') ? 'has-error' : '' }}">
                            <label for="edu_terms_en">المحتوي - انجليزى</label>
                            <textarea name="edu_terms_en" class="form-control" value="{{$setting->getBody('edu_terms_en')}}" required data-parsley-required-message="هذا الحقل الزامى">
                                {{ $setting->getBody('edu_terms_en') }}
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
                                   {{--value="{{ $setting->getBody('about_app_image') }}">--}}
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
        CKEDITOR.replace( 'edu_terms_en' );
        CKEDITOR.replace( 'edu_terms_ar' );
    </script>
@endsection