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
                <h4 class="page-title">إدارة محتوى الموقع</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
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

                    <h4 class="header-title m-t-0 m-b-30">إدارة محتوى الموقع</h4>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="userName">العنوان *</label>
                            <input type="text" name="site_title"
                                   value="{{ $setting->getBody('site_title') }}" class="form-control"
                                   required placeholder="العنوان ..."/>
                            <p class="help-block"></p>

                        </div>

                    </div>


                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('site_content') ? 'has-error' : '' }}">
                            <label for="site_content">المحتوى</label>
                            <textarea id="editor" name="site_content">
                                {{ $setting->getBody('site_content') }}
                            </textarea>
                        </div>

                    </div>
                    
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="playstore">بلاى ستور</label>
                            <input type="text" name="playstore"
                                   value="{{ $setting->getBody('playstore') }}" class="form-control"
                                   required placeholder="بلاى ستور ..."
                                   data-parsley-maxlength="200"
                                   data-parsley-maxlength-message=" أقصى عدد الحروف المسموح بها هى (200) حرف"
                            />
                            <p class="help-block"></p>

                        </div>

                    </div>
                    
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="appstore">اب ستور</label>
                            <input type="text" name="appstore"
                                   value="{{ $setting->getBody('appstore') }}" class="form-control"
                                   required placeholder="اب ستور ..."
                                   data-parsley-maxlength="200"
                                   data-parsley-maxlength-message=" أقصى عدد الحروف المسموح بها هى (200) حرف"
                            />
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

            <div class="col-lg-4">
                <div class="card-box" style="overflow: hidden;">
                    <h4 class="header-title m-t-0 m-b-30">الصورة </h4>
                    <div class="form-group">
                        <div class="col-sm-12">

                            <input type="hidden" name="site_image_old"
                                   value="{{ $setting->getBody('site_image') }}">
                            <input type="file" name="site_image" class="dropify" data-max-file-size="6M"
                                   data-default-file="{{ request()->root() . '/' . $setting->getBody('site_image') }}"/>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end row -->
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.7.0/full/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'about_app_desc_en' );
    </script>
@endsection