@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-xs-6 col-md-4 col-sm-4">
            <h3 class="page-title">إرسال إشعار</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="bg-picture card-box">
                <div class="profile-info-name">
                    <div class="profile-info-detail">
                        <h3 class="m-t-0 m-b-0">إرسال إشعار</h3>

                        <div class="panel-body">

                            <form action="{{ route('notif-send') }}" method="post">
                                <!-- Highlighting rows and columns -->
                                <div class="panel panel-flat">

                                    <br>
                                    <div style="width: 80%; margin: 20px auto;">

                                         <div class="form-group">
                                             <label>المستخدم</label>
                                             <select class="form-control select" name="device_id" required data-parsley-required-message="هذا الحقل إلزامي">
                                                 <option value="">-- يرجي اختيار المستخدم --</option>
                                                 @if(count($users) > 0)
                                                     <option value="all">الكل</option>
                                                     <!--<option value="users">جميع مستخدمى التطبيق</option>-->
                                                     <!--<option value="providers">جميع مزودى الخدمة</option>-->
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"> {{ $user->name }} </option>
                                        @endforeach
                                        
                                        @endif

                                        
                                             </select>
                                        </div>

                                        <div class="form-group">

                                            <label>عنوان اللإشعار</label>
                                            <input type="text" class="form-control title" name="title" placeholder="عنوان الإشعار" value="{{ old('title') }}"/>

                                        </div>
                                        
                                        <div class="form-group">

                                            <label>نص الإشعار</label>
                                            <textarea class="form-control description" rows="10" cols="9" name="msg" placeholder="نص الرسالة"> {{ old('msg') }} </textarea>

                                        </div>

                                        <input type="hidden" value="إرسال" name="type">

                                        {{ csrf_field() }}

                                        <button type="submit" style="padding: 10px 30px; margin-top: 20px;" class="btn btn-lg btn-primary">
                                            ارسال
                                        </button>

                                    </div>


                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection