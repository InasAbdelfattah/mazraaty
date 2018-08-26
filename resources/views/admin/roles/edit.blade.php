{{--@extends('layouts.app')--}}

{{--@section('content')--}}
{{--<h3 class="page-title">@lang('global.roles.title')</h3>--}}


{{--<form action="{{ route('roles.update', $role->id) }}" method="post">--}}
{{--{{ csrf_field() }}--}}
{{--{{ method_field('PUT') }}--}}


{{--<div class="panel panel-default">--}}
{{--<div class="panel-heading">--}}
{{--@lang('global.app_edit')--}}
{{--</div>--}}

{{--<div class="panel-body">--}}
{{--<div class="row">--}}
{{--<div class="col-xs-12 form-group">--}}

{{--<label for="name" >Name*</label>--}}
{{--<input type="text" name="title" value="{{ $role->title  }}" class="form-control" required/>--}}
{{--<p class="help-block"></p>--}}
{{--@if($errors->has('name'))--}}
{{--<p class="help-block">--}}
{{--{{ $errors->first('name') }}--}}
{{--</p>--}}
{{--@endif--}}
{{--</div>--}}

{{--<div class="col-xs-12 form-group">--}}

{{--<label for="name" >Name*</label>--}}
{{--<input type="text" name="name" value="{{ $role->name  }}" class="form-control" required/>--}}
{{--<p class="help-block"></p>--}}
{{--@if($errors->has('title'))--}}
{{--<p class="help-block">--}}
{{--{{ $errors->first('title') }}--}}
{{--</p>--}}
{{--@endif--}}
{{--</div>--}}

{{--</div>--}}
{{--<div class="row">--}}
{{--<div class="col-xs-12 form-group">--}}

{{--<label for="abilities" >Abilities</label>--}}
{{--                    {!! Form::select('abilities[]', $abilities, old('abilities') ? old('abilities') : $role->getAbilities()->pluck('name', 'name'), ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}--}}

{{--<select class="form-control" name="abilities[]" required multiple>--}}
{{--@foreach($abilities as $key => $ability)--}}
{{--<option value="{{ $ability }}">{{ $ability }}</option>--}}
{{--@endforeach--}}
{{--</select>--}}
{{--<p class="help-block"></p>--}}
{{--@if($errors->has('abilities'))--}}
{{--<p class="help-block">--}}
{{--{{ $errors->first('abilities') }}--}}
{{--</p>--}}
{{--@endif--}}
{{--</div>--}}
{{--</div>--}}

{{--</div>--}}
{{--</div>--}}


{{--<button type="submit">update</button>--}}

{{--</form>--}}
{{--@endsection--}}


@extends('admin.layouts.master')

@section('content')
    <form method="POST" action="{{ route('roles.update', $role->id) }}" enctype="multipart/form-data"
          data-parsley-validate novalidate>

    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">
                    <a href="{{ route('roles.index') }}" type="button" class="btn btn-custom waves-effect waves-light"
                       aria-expanded="false"> مشاهدة جميع الادوار
                        <span class="m-l-5">
                        <i class="fa fa-backward"></i>
                    </span>
                    </a>
                </div>
                <h4 class="page-title">إدارة الادوار</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">


                    <div id="errorsHere"></div>
                    <div class="dropdown pull-right">


                    </div>

                    <h4 class="header-title m-t-0 m-b-30">تعديل الدور</h4>


                    {{--<div class="col-xs-12">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="userName"> الاسم الظاهر*</label>--}}
                            {{--<input type="text" name="title" value="{{ $role->title or old('title') }}"--}}
                                   {{--class="form-control" required--}}
                                   {{--placeholder="الاسم الظاهر..."/>--}}
                            {{--<p class="help-block" id="error_userName"></p>--}}
                            {{--@if($errors->has('title'))--}}
                                {{--<p class="help-block">--}}
                                    {{--{{ $errors->first('title') }}--}}
                                {{--</p>--}}
                            {{--@endif--}}
                        {{--</div>--}}

                    {{--</div>--}}

                    <div class="col-xs-12">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="usernames">الاسم *</label>
                            <input type="text" name="name" value="{{ $role->name or old('name') }}" class="form-control"
                                   required placeholder="الاسم..."/>
                            @if($errors->has('name'))
                                <p class="help-block">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                        </div>
                    </div>


                    {{-- {{ $abilities }}--}}
                    @if($role->id != 1)
                    <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                        <label for="passWord2">الصلاحيات *</label>
                        <select multiple="multiple" class="multi-select" id="my_multi_select1" name="abilities[]"
                                required
                                data-plugin="multiselect">
                            @foreach($abilities as  $ability)
                                <option value="{{ $ability->name }}"
                                        @if($role->abilities->pluck('name', 'name')) @foreach($role->abilities->pluck('name', 'name') as $bilityVal) @if($bilityVal == $ability->name) selected @endif @endforeach @endif
                                        {{-- {{ (collect(old('roles'))->contains($ability)) ? 'selected':'' }}--}}
                                >

                                    {{ $ability->title }}

                                </option>
                            @endforeach

                        </select>

                        @if($errors->has('abilities'))
                            <p class="help-block"> {{ $errors->first('abilities') }}</p>
                        @endif

                    </div>
                    @endif

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

            {{--<div class="col-lg-4">
                <div class="card-box" style="overflow: hidden;">
                    <h4 class="header-title m-t-0 m-b-30">الصورة الشخصية</h4>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="file" name="image" class="dropify" data-max-file-size="6M"/>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
        <!-- end row -->
    </form>
@endsection

