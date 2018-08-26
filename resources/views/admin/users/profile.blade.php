@extends('admin.layouts.master')
@section('title', ' الصفحة الشخصية')
@section('content')


    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                
                <h4 class="page-title">الصفحة الشخصية</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card-box">

                    <h4 class="header-title m-t-0 m-b-30">الصفحة الشخصية</h4>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">الاسم*</label>
                            <input type="text" name="name" readonly value="{{ $user->name or old('name') }}" class="form-control" placeholder="اسم المستخدم بالكامل..."/>
                            <p class="help-block" id="error_userName"></p>
                            @if($errors->has('name'))
                                <p class="help-block">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="userPhone">رقم الجوال*</label>
                            <input type="text" name="phone" readonly value="{{ $user->phone or old('phone') }}"
                                   class="form-control" 
                                   placeholder="رقم الجوال..."/>
                            @if($errors->has('phone'))
                                <p class="help-block">
                                    {{ $errors->first('phone') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    
                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="emailAddress">البريد الإلكتروني*</label>

                            <input type="email" name="email" readonly parsley-trigger="change"
                                   value="{{ $user->email or old('email') }}"
                                   class="form-control" placeholder="البريد الإلكتروني..." />
                            @if($errors->has('email'))
                                <p class="help-block">{{ $errors->first('email') }}</p>
                            @endif

                        </div>

                    </div>
                    
                    <div class="form-group text-right m-t-20">
                        <a href="{{ route('users.editProfile',['id'=>$user->id]) }}" class="btn btn-primary waves-effect waves-light m-t-20">تعديل البيانات</a>
                        <!--<button class="btn btn-primary waves-effect waves-light m-t-20" type="submit">-->
                        <!--    حفظ البيانات-->
                        <!--</button>-->
                        <!--<button onclick="window.history.back();return false;" type="reset" class="btn btn-default waves-effect waves-light m-l-5 m-t-20">-->
                        <!--    إلغاء-->
                        <!--</button>-->
                    </div>

                    

                </div>
            </div><!-- end col -->

            <div class="col-lg-4">
                <div class="card-box" style="overflow: hidden;">

                    <h4 class="header-title m-t-0 m-b-30">الصورة الشخصية</h4>

                    <div class="form-group">
                        <div class="col-sm-12">
                        
                            <image src="{{ request()->root().'/files/users/'.$user->image }}" style="height:100%; width:100%;"/>

                            <!--<input type="file" name="image" class="dropify" data-max-file-size="6M"-->
                            <!--       data-default-file="{{ request()->root().'/files/users/'.$user->image }}"/>-->
                        </div>
                    </div>

                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->

@endsection

