@extends('admin.layouts.master')

@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">

                <a href="{{ route('abilities.create') }}" type="button" class="btn btn-custom waves-effect waves-light"
                   aria-expanded="false"> إضافة
                    <span class="m-l-5">
                        <i class="fa fa-user"></i>
                    </span>
                </a>

            </div>
            <h4 class="page-title">إدارة الصلاحيات</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-sm-4 col-xs-8 m-b-30" style="display: inline-flex">
                        مشاهدة الصلاحيات
                    </div>

                    <!-- <div class="col-sm-4 col-sm-offset-4">
                        <a style="float: left; margin-right: 15px;" class="btn btn-danger btn-sm getSelected">
                            <i class="fa fa-trash" style="margin-left: 5px"></i> حذف المحدد
                        </a>

                    </div> -->
                </div>
                <table class="table  table-striped" id="datatable-fixed-header">
                    <thead>
                    <tr>
                        <th>
                            <div class="checkbox checkbox-primary checkbox-single">
                                <input type="checkbox" name="check" onchange="checkSelect(this)"
                                       value="option2"
                                       aria-label="Single checkbox Two">
                                <label></label>
                            </div>
                        </th>
                        <th>الاسم</th>
                        <th>الاسم الظاهر</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($abilities as $ability)
                            <tr data-entry-id="{{ $ability->id }}">
                                <td> #
                                    <!-- <div class="checkbox checkbox-primary checkbox-single">
                                        <input type="checkbox" class="checkboxes-items"
                                               value="{{ $ability->id }}"
                                               aria-label="Single checkbox Two">
                                        <label></label>
                                    </div> -->
                                </td>
                                <td>{{ $ability->name }}</td>
                                <td>{{ $ability->title }}</td>
                                <td>
                                    <a href="{{ route('abilities.edit',[$ability->id]) }}" class="btn btn-xs btn-info">تعديل</a>
                                    @if($ability->id != 1)
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['abilities.destroy', $ability->id])) !!}
                                    {!! Form::submit('حذف', array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endif
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="4">لا توجدبيانات</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>


            </div>
        </div>
    </div>
    <!-- End row -->
@endsection
@section('javascript') 
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('abilities.mass_destroy') }}';
    </script>
@endsection