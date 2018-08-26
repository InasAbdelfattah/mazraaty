@extends('admin.layouts.master')

@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">

                <a href="{{ route('roles.create') }}" type="button" class="btn btn-custom waves-effect waves-light"
                   aria-expanded="false"> إضافة
                    <span class="m-l-5">
                        <i class="fa fa-user"></i>
                    </span>
                </a>

            </div>
            <h4 class="page-title">إدارة الادوار</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">


                <div class="row">
                    <div class="col-sm-4 col-xs-8 m-b-30" style="display: inline-flex">
                        مشاهدة الادوار
                    </div>

                    {{--<div class="col-sm-4 col-sm-offset-4">--}}
                        {{--<a style="float: left; margin-right: 15px;" class="btn btn-danger btn-sm getSelected">--}}
                            {{--<i class="fa fa-trash" style="margin-left: 5px"></i> حذف المحدد--}}
                        {{--</a>--}}

                    {{--</div>--}}
                </div>
                <table class="table  table-striped" id="datatable-fixed-header">
                    <thead>
                    <tr>
                        {{--<th>--}}
                            {{--<div class="checkbox checkbox-primary checkbox-single">--}}
                                {{--<input type="checkbox" name="check" onchange="checkSelect(this)"--}}
                                       {{--value="option2"--}}
                                       {{--aria-label="Single checkbox Two">--}}
                                {{--<label></label>--}}
                            {{--</div>--}}
                        {{--</th>--}}
                        <th>#</th>
                        {{--<th>الصورة</th>--}}
                        {{--<th>الاسم الظاهر</th>--}}
                        <th>الاسم</th>
                        <th>تاريخ الإنشاء</th>
                        <th>عدد المسجلين بالصلاحيات</th>
                        <th>الخيارات</th>

                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1; @endphp
                    @foreach($roles as $role)

                        <tr>
                            {{--<th>--}}
                                {{--<div class="checkbox checkbox-primary checkbox-single">--}}
                                    {{--<input type="checkbox" class="checkboxes-items"--}}
                                           {{--value="{{ $role->id }}"--}}
                                           {{--aria-label="Single checkbox Two">--}}
                                    {{--<label></label>--}}
                                {{--</div>--}}
                            {{--</th>--}}
                            <td>{{$i++}}</td>
                            {{--<td>{{ $role->title }}</td>--}}
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->created_at }}</td>

                            <td> {{ $role->users->count() }}</td>

                            <td>
                                @if($role->id != 1)
                                <a href="{{ route('roles.edit',$role->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <a href="javascript:;" id="elementRow{{ $role->id }}" data-id="{{ $role->id }}"
                                   class="removeElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                    <i class="fa fa-remove"></i>

                                </a>
                                @endif
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>
    <!-- End row -->
@endsection


@section('scripts')


    <script>


        @if(session()->has('success'))
        setTimeout(function () {
            showMessage('{{ session()->get('success') }}');
        }, 3000);
        @endif


        $('body').on('click', '.removeElement', function () {
            var id = $(this).attr('data-id');
            var $tr = $(this).closest($('#elementRow' + id).parent().parent());
            swal({
                title: "هل انت متأكد؟",
                text: "يمكنك استرجاع المحذوفات مرة اخرى لا تقلق.",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "موافق",
                cancelButtonText: "إلغاء",
                confirmButtonClass: 'btn-danger waves-effect waves-light',
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('role.delete') }}',
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {
                            $('#catTrashed').html(data.trashed);
                            if (data) {
                                var shortCutFunction = 'success';
                                var msg = 'لقد تمت عملية الحذف بنجاح.';
                                var title = data.title;
                                toastr.options = {
                                    positionClass: 'toast-top-center',
                                    onclick: null,
                                    showMethod: 'slideDown',
                                    hideMethod: "slideUp",

                                };
                                var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                                $toastlast = $toast;
                            }

                            $tr.find('td').fadeOut(1000, function () {
                                $tr.remove();
                            });
                        }
                    });
                } else {

                    swal({
                        title: "تم الالغاء",
                        text: "انت لغيت عملية الحذف تقدر تحاول فى اى وقت :)",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "موافق",
                        confirmButtonClass: 'btn-info waves-effect waves-light',
                        closeOnConfirm: false,
                        closeOnCancel: false

                    });

                }
            });
        });

        $('.getSelected').on('click', function () {
            // var items = $('.checkboxes-items').val();
            var sum = [];
            $('.checkboxes-items').each(function () {
                if ($(this).prop('checked') == true) {
                    sum.push(Number($(this).val()));
                }

            });

            if (sum.length > 0) {
                //var $tr = $(this).closest($('#elementRow' + id).parent().parent());
                swal({
                    title: "هل انت متأكد؟",
                    text: "يمكنك استرجاع المحذوفات مرة اخرى لا تقلق.",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "موافق",
                    cancelButtonText: "إلغاء",
                    confirmButtonClass: 'btn-danger waves-effect waves-light',
                    closeOnConfirm: true,
                    closeOnCancel: true,
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('roles.group.delete') }}',
                            data: {ids: sum},
                            dataType: 'json',
                            success: function (data) {
                                $('#catTrashed').html(data.trashed);
                                if (data) {
                                    var shortCutFunction = 'success';
                                    var msg = 'لقد تمت عملية الحذف بنجاح.';
                                    var title = data.title;
                                    toastr.options = {
                                        positionClass: 'toast-top-left',
                                        onclick: null
                                    };
                                    var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                                    $toastlast = $toast;
                                }

                                $('.checkboxes-items').each(function () {
                                    if ($(this).prop('checked') == true) {
                                        $(this).parent('tr').remove();
                                    }
                                });
//                        $tr.find('td').fadeOut(1000, function () {
//                            $tr.remove();
//                        });
                            }
                        });
                    } else {
                        swal({
                            title: "تم الالغاء",
                            text: "انت لغيت عملية الحذف تقدر تحاول فى اى وقت :)",
                            type: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "موافق",
                            confirmButtonClass: 'btn-info waves-effect waves-light',
                            closeOnConfirm: false,
                            closeOnCancel: false
                        });
                    }
                });
            } else {
                swal({
                    title: "تحذير",
                    text: "قم بتحديد عنصر على الاقل",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "موافق",
                    confirmButtonClass: 'btn-warning waves-effect waves-light',
                    closeOnConfirm: false,
                    closeOnCancel: false

                });
            }


        });

        function showMessage(message) {

            var shortCutFunction = 'success';
            var msg = message;
            var title = 'نجاح!';
            toastr.options = {
                positionClass: 'toast-top-center',
                onclick: null,
                showMethod: 'slideDown',
                hideMethod: "slideUp",
            };
            var $toast = toastr[shortCutFunction](msg, title);
            // Wire up an event handler to a button in the toast, if it exists
            $toastlast = $toast;


        }
    </script>



@endsection
