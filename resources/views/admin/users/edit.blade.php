@extends('admin.layouts.master')
@section('title', 'إدارة المستخدمين')
@section('content')


    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data"
          data-parsley-validate novalidate>
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <input type="hidden" name="user_id" value="{{$user->id}}">

    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">


                    <button type="button" class="btn btn-custom  waves-effect waves-light"
                            onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i
                                    class="fa fa-reply"></i></span>
                    </button>


                </div>
                
                <h4 class="page-title">تعديل المستخدم</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card-box">

                    <h4 class="header-title m-t-0 m-b-30">تعديل بيانات المستخدم</h4>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">الاسم الكامل*</label>
                            <input type="text" name="name" value="{{ $user->name or old('name') }}" class="form-control" placeholder="اسم المستخدم بالكامل..."/>
                            <p class="help-block" id="error_userName"></p>
                            @if($errors->has('name'))
                                <p class="help-block">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="usernames">اسم المستخدم*</label>
                            <input type="text" name="username" value="{{ $user->username or old('username') }}"
                                   class="form-control" placeholder="اسم المستخدم..."/>
                            @if($errors->has('username'))
                                <p class="help-block">
                                    {{ $errors->first('username') }}
                                </p>
                            @endif
                        </div>
                    </div> -->

                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="userPhone">رقم الجوال*</label>
                            <input type="text" name="phone" value="{{ $user->phone or old('phone') }}"
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

                            <input type="email" name="email" parsley-trigger="change"
                                   value="{{ $user->email or old('email') }}"
                                   class="form-control" placeholder="البريد الإلكتروني..." />
                            @if($errors->has('email'))
                                <p class="help-block">{{ $errors->first('email') }}</p>
                            @endif

                        </div>

                    </div>

                    @if($user->is_user !=1)
                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="pass1">كلمة المرور*</label>

                            <input type="password" name="password" id="pass1"
                                   class="form-control"
                                   placeholder="كلمة المرور..."
                            />

                            @if($errors->has('password'))
                                <p class="help-block">{{ $errors->first('password') }}</p>
                            @endif

                        </div>
                    </div>


                    <div class="col-xs-6">
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="passWord2">تأكيد كلمة المرور*</label>
                            <input data-parsley-equalto="#pass1" name="password_confirmation" type="password"
                                   placeholder="تأكيد كلمة المرور..." class="form-control" id="passWord2">
                            @if($errors->has('password_confirmation'))
                                <p class="help-block">{{ $errors->first('password_confirmation') }}</p>
                            @endif


                        </div>
                    </div>
                    <br/>

                    @endif
<!-- 
                    <div class="col-xs-6">
                    <div class="form-group">
                        <label for="passWord2">العنوان*</label>
                        <input name="address" value="{{ $user->address or old('address') }}" type="text" required
                               placeholder="العنوان..." class="form-control">

                    </div>
                    </div>
 -->
                    @if($user->is_user !=1)
                    <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                        <label for="passWord2">الصلاحيات *</label>
                        <select multiple="multiple" class="multi-select" id="my_multi_select1" name="roles[]" 
                                data-plugin="multiselect">
                            @foreach($roles as  $value)
                                
                                <!--<option value="{{ $value->name }}">{{$value->name}}</option>-->
                                <option value="{{ $value->name }}"
                                        @if(count($user->roles)>0) @foreach($user->roles->pluck('name', 'name') as $roleUser) @if($roleUser == $value->name) selected @else ''@endif @endforeach @endif >{{ $value->name }}</option>
                            

                            @endforeach

                        </select>

                        @if($errors->has('roles'))
                            <p class="help-block"> {{ $errors->first('roles') }}</p>
                        @endif

                    </div>
                
                    @endif

                    <!-- @if($user->id != 1) -->
                    <!-- <div class="form-group">
                        <label for="pass1">الحالة *</label>
                        <select class="form-control select2" name="is_active">
                            <option value="1" {{$user->is_active == 1 ? 'selected' : ''}}>مفعل</option>
                            <option value="0" {{$user->is_active == 0 ? 'selected' : ''}}>غير مفعل</option>
                        </select>
                    </div>
 -->
                    <!--<div class="form-group">-->
                    <!--    <label for="pass1">حالة الحذر *</label>-->
                    <!--    <select class="form-control select2" name="is_suspend">-->
                    <!--        <option value="1" {{$user->is_suspend == 1 ? 'selected' : ''}}>محذور</option>-->
                    <!--        <option value="0" {{$user->is_suspend == 0 ? 'selected' : ''}}>غير محذور </option>-->
                    <!--    </select>-->
                    <!--</div>-->
                    <!-- @endif -->

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


                    <h4 class="header-title m-t-0 m-b-30">الصورة الشخصية</h4>

                    <div class="form-group">
                        <div class="col-sm-12">

                            <input type="hidden" value="{{ $user->image }}" name="oldImage"/>
                            <input type="file" name="image" class="dropify" data-max-file-size="6M" accept="image/*"
                                   data-default-file="{{ request()->root().'/files/users/'.$user->image }}"/>

                        </div>
                    </div>

                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </form>

@endsection

