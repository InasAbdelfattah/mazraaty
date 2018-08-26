@extends('admin.layouts.login')


@section('styles')
    <style>
        .wrapper-page {
            margin: 3% auto;
            position: relative;
            width: 420px;
        }
    </style>

@endsection
@section('content')




    <div class="m-t-40 card-box">
        <div class="text-center">
            <h4 class="text-uppercase font-bold m-b-0">تسجيل الدخول</h4>
        </div>
        <div class="panel-body">

            @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong> {{ session()->get('error') }}</strong>
                </div>
            @endif


            <form class="form-horizontal m-t-20" method="POST" action="{{ route('admin.login') }}">
                {{ csrf_field() }}



                <div class="form-group">
                    <div class="col-xs-12">
                        <input id="email" type="email" class="form-control" name="provider"
                               value="{{ old('provider') }}" required autofocus
                               placeholder="اسم المستخدم او البريد الإلكتروني...">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('provider') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-xs-12">
                        <input id="password" type="password" class="form-control" name="password" required
                               placeholder="كلمة المرور...">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group ">
                    <div class="col-xs-12">
                        <div class="checkbox checkbox-custom">

                            <input id="checkbox-signup" type="checkbox"
                                   name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="checkbox-signup">
                                تذكرنى
                            </label>
                        </div>

                    </div>
                </div>

                <div class="form-group text-center m-t-30">
                    <div class="col-xs-12">
                        <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" type="submit">
                            دخول
                        </button>
                    </div>
                </div>

                <div class="form-group m-t-30 m-b-0">
                    <div class="col-sm-12">
                        <a href="{{ route('administrator.password.request') }}" class="text-muted"><i
                                    class="fa fa-lock m-r-5"></i>
                            هل نسيت كلمة المرور؟</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <!-- end card-box-->




@endsection
