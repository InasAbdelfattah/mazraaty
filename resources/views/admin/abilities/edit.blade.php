@extends('admin.layouts.master')

@section('content')
    <h3 class="page-title">تعديل صلاحية جديدة</h3>
    <div class="row">
        <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-30">تعديل صلاحية جديدة</h4>
            <form action="{{ route('abilities.update', $ability->id) }}"  method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        
                <div class="col-xs-12 form-group">
                    <label>الاسم)انجليزى(</label>
                    <input name="name" value="{{ $ability->name }}"  class="form-control" required/>
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-12 form-group">
                    <label>الاسم الظاهر</label>
                    <input name="title" value="{{ $ability->title }}"  class="form-control" required/>
                    <p class="help-block"></p>
                    @if($errors->has('title'))
                        <p class="help-block">
                            {{ $errors->first('title') }}
                        </p>
                    @endif
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
                 
          
            <!-- <button >إضافة</button> -->
            </form>
        </div>
    </div>
</div>


@stop

