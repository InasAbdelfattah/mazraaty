@extends('admin.layouts.master')

@section('content')
    <form action="{{ route('administrator.settings.store') }}" data-parsley-validate="" novalidate="" method="post"
          enctype="multipart/form-data">

    {{ csrf_field() }}

    <!-- Page-Title -->

        <div class="row">
            <div class="col-sm-12">
                <!-- <div class="btn-group pull-right m-t-15">
                    <button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light"
                            data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i
                                    class="fa fa-cog"></i></span></button>

                </div> -->
                <h4 class="page-title">بيانات التواصل الإجتماعى</h4>
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

                    <h4 class="header-title m-t-0 m-b-30">روابط التواصل الإجتماعي</h4>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="userName">فيس بوك </label>
                            <input type="text" name="facebook"
                                   value="{{ $setting->getBody('facebook') }}" class="form-control"
                                   required
                                   placeholder="فيس بوك ..."/>
                            <p class="help-block"></p>

                        </div>

                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="userName">تويتر</label>
                            <input type="text" name="twitter"
                                   value="{{ $setting->getBody('twitter') }}" class="form-control"
                                   required
                                   placeholder="تويتر ..."/>
                            <p class="help-block"></p>

                        </div>

                    </div>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="userName">انستجرام </label>
                            <input type="text" name="insta"
                                   value="{{ $setting->getBody('insta') }}" class="form-control"
                                   required
                                   placeholder="انستجرام ..."/>
                            <p class="help-block"></p>

                        </div>

                    </div>
                    
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="userName">لينكدين </label>
                            <input type="text" name="linkedin"
                                   value="{{ $setting->getBody('linkedin') }}" class="form-control"
                                   required
                                   placeholder="لينكدين ..."/>
                            <p class="help-block"></p>

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
            {{--<h4 class="header-title m-t-0 m-b-30">الصورة الشخصية</h4>--}}
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
