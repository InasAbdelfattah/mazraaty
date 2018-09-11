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
                            data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                </div> -->
                <h4 class="page-title">روابط التواصل الاجتماعى</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">

                    <div id="errorsHere"></div>

                    <h4 class="header-title m-t-0 m-b-30">روابط التواصل الاجتماعى</h4>
                    
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>البريد الإلكترونى </label>
                            <input type="email" name="email"
                                   value="{{ setting()->getBody('email') }}" class="form-control" required placeholder="البريد الإلكترونى ..."/>
                            <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="userName">رقم الجوال الخاص بالتطبيق للدعم الفنى</label>
                            <input type="text" name="support_phone"
                                   value="{{ setting()->getBody('support_phone') }}" class="form-control" required placeholder="رقم الجوال الخاص بالتطبيق للدعم الفنى ..."/>
                            <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>فيس بوك</label>
                            <input type="url" name="fb"
                                   value="{{ setting()->getBody('fb') }}" class="form-control" required placeholder=" فيس بوك ..."/>
                            <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>تويتر</label>
                            <input type="url" name="twitter"
                                   value="{{ setting()->getBody('twitter') }}" class="form-control" required placeholder=" تويتر ..."/>
                            <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>جوجل بلاس</label>
                            <input type="url" name="google"
                                   value="{{ setting()->getBody('google') }}" class="form-control" required placeholder=" جوجل بلاس..."/>
                            <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>انستاجرام</label>
                            <input type="url" name="instagram"
                                   value="{{ setting()->getBody('instagram') }}" class="form-control" required placeholder=" انستاجرام..."/>
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

        </div>
        <!-- end row -->
    </form>
@endsection
