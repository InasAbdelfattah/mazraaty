@extends('admin.master')

@section('title')
الإشعارات

@endsection

@section('sectionName')

إرسال إشعار
@endsection

@section('pageName')

ارسال جديد

@endsection


@section('content')

<form action="{{ route('notif-send') }}" method="post">
    <!-- Highlighting rows and columns -->
    <div class="panel panel-flat">

        <br>
        <div style="width: 80%; margin: 20px auto;">

             <div class="form-group">
                 <label>المستخدم</label>
                 <select class="form-control select" name="device_id">
                     <option value="">-- يرجي اختيار المستخدم --</option>
                     <option value="all">جميع المستخدمين</option>
                     @foreach($data as $user)
                     <option value="{{ $user->device_id }}"> {{ $user->name }} </option>
                     @endforeach
                 </select>
            </div>
            
            <div class="form-group">

                <label>نص الرسالة</label>
                <textarea class="form-control" rows="10" cols="9" name="msg" placeholder="نص الرسالة"> {{ old('msg') }} </textarea>

            </div>

            <input type="hidden" value="إرسال" name="type">

            {{ csrf_field() }}

            <button type="submit" style="padding: 10px 30px; margin-top: 20px;" class="btn btn-lg btn-primary">
                ارسال
            </button>

        </div>


    </div>

</form>



@endsection