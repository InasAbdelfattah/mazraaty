@extends('admin.layouts.login')


@section('content')


    <div class="m-t-40 card-box">
        
        <div class="text-center">
            <h4 class="text-uppercase font-bold m-b-0">استعادة ضبط كلمة المرور</h4>


        </div>
        <div class="panel-body">

            @if (session('status'))
                <div class="alert alert-success">
                    <!--{{ session('status') }}-->
                    تم ارسال رابط استعادة كلمة المرور
                </div>
            @endif
            <form class="form-horizontal m-t-20" method="POST" action="{{ route('administrator.password.email') }}">
                {{ csrf_field() }}


                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="col-xs-12">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                               required placeholder="البريد الإلكتروني...">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group text-center m-t-20 m-b-0">
                    <div class="col-xs-12">
                        <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" type="submit">
                            إرسال البريد
                        </button>
                    </div>
                </div>

            </form>

            <div class="form-group m-t-30 m-b-0">
                <div class="col-sm-12">
                    <a href="{{ route('admin.login') }}" class="text-muted"><i
                                class="fa fa-sign-in m-r-5"></i>
                        تسجيل الدخول</a>
                </div>
            </div>

        </div>
    </div>
    <!-- end card-box -->

    {{--<div class="container">--}}
    {{--<div class="row">--}}
    {{--<div class="col-md-8 col-md-offset-2">--}}
    {{--<div class="panel panel-default">--}}
    {{--<div class="panel-heading">Admin Reset Password</div>--}}

    {{--<div class="panel-body">--}}
    {{--@if (session('status'))--}}
    {{--<div class="alert alert-success">--}}
    {{--{{ session('status') }}--}}
    {{--</div>--}}
    {{--@endif--}}

    {{--<form class="form-horizontal" method="POST"--}}
    {{--action="{{ route('administrator.password.email') }}">--}}
    {{--{{ csrf_field() }}--}}

    {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
    {{--<label for="email" class="col-md-4 control-label">E-Mail Address</label>--}}

    {{--<div class="col-md-6">--}}
    {{--<input id="email" type="email" class="form-control" name="email"--}}
    {{--value="{{ old('email') }}" required>--}}

    {{--@if ($errors->has('email'))--}}
    {{--<span class="help-block">--}}
    {{--<strong>{{ $errors->first('email') }}</strong>--}}
    {{--</span>--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="form-group">--}}
    {{--<div class="col-md-6 col-md-offset-4">--}}
    {{--<button type="submit" class="btn btn-primary">--}}
    {{--Send Password Reset Link--}}
    {{--</button>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</form>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
@endsection
