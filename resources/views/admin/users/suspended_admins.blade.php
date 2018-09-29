@extends('admin.layouts.master')
@section('title', 'إدارة المستخدمين')
@section('content')

    <!-- Page-Title -->
    <div class="row zoomIn">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">

                <!--<a href="{{ route('users.create') }}" type="button" class="btn btn-custom waves-effect waves-light"-->
                <!--   aria-expanded="false"> إضافة-->
                <!--    <span class="m-l-5">-->
                <!--        <i class="fa fa-user"></i>-->
                <!--    </span>-->
                <!--</a>-->
                
                <button type="button" class="btn btn-custom  waves-effect waves-light" onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i class="fa fa-reply"></i></span>
                </button> 

            </div>
            <h4 class="page-title">@lang('global.users_management')</h4>
        </div>
    </div>

    <div class="row zoomIn">
        <div class="col-sm-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-sm-4 col-xs-8 m-b-30" style="display: inline-flex">
                        مشاهدة المستخدمين
                    </div>

                    <div class="col-sm-4 col-sm-offset-4 pull-left">
                        <!--<a style="float: left; margin-right: 15px;    margin-bottom: 20px;"-->
                        <!--   class="btn btn-danger btn-sm getSelected">-->
                        <!--    <i class="fa fa-trash" style="margin-left: 5px"></i> حذف المحدد-->
                        <!--</a>-->

                        <!--<a style="float: left;" class="btn btn-info btn-sm getSelectedAndSuspend">-->
                        <!--    <i class="fa fa-users" style="margin-left: 5px"></i> حظر المستخدمين-->
                        <!--</a>-->
                    </div>
                </div>


                <table id="datatable-fixed-header" class="table  table-striped">
                    <thead>
                    <tr>
                        <th>م
                            <!-- <div class="checkbox checkbox-primary checkbox-single">
                                <input type="checkbox" style="margin-bottom: 0px;" name="check"
                                       onchange="checkSelect(this)"
                                       value="option2"
                                       aria-label="Single checkbox Two">
                                <label></label>
                            </div> -->
                        </th>
                        <!-- <th>الصورة</th> -->
                        <th>الاسم</th>
                        <!--<th>اسم المستخدم</th>-->
                        <th>البريد الإلكتروني</th>
                        <th>رقم الجوال</th>
                        <!-- <th>الصلاحيات</th> -->
                        <th>الحالة</th>
                        <th>تاريخ التسجيل</th>
                        <th>الخيارات</th>

                    </tr>
                    </thead>
                    <tbody>
                        @php $i = 1 ; @endphp 
                    @foreach($users as $user)

                        <tr id="currentRowOn{{$user->id}}">
                            <td>
                                {{$i++}}
                                <!-- @if($user->id != 1)-->
                                <!--<div class="checkbox checkbox-primary checkbox-single">-->
                                <!--    <input type="checkbox" style="margin-bottom: 0px;" class="checkboxes-items"-->
                                <!--           value="{{ $user->id }}"-->
                                <!--           aria-label="Single checkbox Two">-->
                                <!--    <label></label>-->
                                <!--</div>-->
                                <!--@else-->
                                <!--#-->
                                <!--@endif-->
                            </td>
                            <!-- <td style="width: 10%;">
                                <a data-fancybox="gallery"
                                   href="{{ getDefaultImage(request()->root().'/files/users/'.$user->image, request()->root().'/assets/admin/custom/images/default.png') }}">
                                    <img style="width: 50%; border-radius: 50%; height: 49px;"
                                         src="{{ getDefaultImage(request()->root().'/files/users/'.$user->image, request()->root().'/assets/admin/custom/images/default.png') }}"/>
                                </a>

                            </td> -->

                            <td>{{ $user->name }}</td>
                            <!--<td>{{ $user->username  }}</td>-->
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>

                            <td id="is_active{{$user->id}}">
                                @if($user->is_suspend == 0)
                                    <label class="label label-success label-xs">مفعل</label>
                                @else
                                    <label class="label label-danger label-xs">محظور</label>
                                @endif
                            </td>

                            <td>{{ $user->created_at }}</td>
                            <td>

                                <a href="{{ route('users.show',$user->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="{{ route('users.edit',$user->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-edit"></i>
                                </a>

                                @if($user->id != 1)
                                    @if($user->is_suspend == 1)

                                        <a id="activateUser{{ $user->id }}" href="javascript:;"
                                           data-id="{{ $user->id }}" data-status="0" data-url="{{ route('user.suspend') }}"
                                           class="activateElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-primary m-b-5">
                                           تفعيل
                                        </a>       
                                     @endif
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

        //activateUser

        $('body').on('click', '.activateElement', function () {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var url = $(this).attr('data-url');
            console.log(url);
            //var $tr = $(this).closest($('#preparedRow' + id).parent().parent());
            var $tr = $($('#currentRowOn' + id));
            var tr = $($('#currentRowOn' + id)).closest($('#currentRow' + id).parent().parent());

            var data = {userId: id , status: status};
            console.log(data);
            swal({
                title: "هل انت متأكد؟",
                text: "",
                type: "success",
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
                url: url,
                data: data,
                dataType: 'json',
                success: function (data) {
                    console.log('in',data);
                    if (data.status == true) {
                        var shortCutFunction = 'success';
                        var msg = data.message;
                        var title = 'نجاح';
                        toastr.options = {
                            positionClass: 'toast-top-center',
                            onclick: null,
                            showMethod: 'slideDown',
                            hideMethod: "slideUp",

                        };
                        var $toast = toastr[shortCutFunction](msg, title); 
                        $toastlast = $toast;
                        console.log(data);
                        $tr.fadeOut(1000, function () {
                            $tr.remove();
                        });

                        // $tr.find('td').fadeOut(1000, function () {
                        //     $tr.remove();
                        // });

                        //$($('#currentRowOn' + id)).remove();
                        //location.reload();  
                        Custombox.close();
                        
                    }

                    if (data.status == false) {
                        var shortCutFunction = 'error';
                        var msg = data.message;
                        var title = 'خطأ';
                        toastr.options = {
                            positionClass: 'toast-top-center',
                            onclick: null,
                            showMethod: 'slideDown',
                            hideMethod: "slideUp",

                        };
                        var $toast = toastr[shortCutFunction](msg, title); 
                        $toastlast = $toast;
                    }

                },
                error: function (data) {

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

        $('form#activeForm').on('submit', function (e) {
            e.preventDefault();

            var id = $(this).attr('data-id');

            var $tr = $($('#currentRowOn' + id)).closest($('#currentRow' + id).parent().parent());

            // console.log($tr);

            var formData = new FormData(this);
            for (var value of formData.values()) {
                console.log(value); 
            }
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    if (data.status == true) {
                        var shortCutFunction = 'success';
                        var msg = data.message;
                        var title = 'نجاح';
                        toastr.options = {
                            positionClass: 'toast-top-center',
                            onclick: null,
                            showMethod: 'slideDown',
                            hideMethod: "slideUp",

                        };
                        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                        $toastlast = $toast;
                        Custombox.close();
                        // if(data.order_status == 1){
                        //     $("#order_status" + data.id).html('سارى');
                        // }elseif(data.order_status == 2){
                        //     $("#order_status" + data.id).html('مرفوض');
                        // }

                        $tr.find('td').fadeOut(1000, function () {
                                $tr.remove();
                            });
                        //location.reload();


                    }

                    if (data.status == false) {
                        var shortCutFunction = 'error';
                        var msg = data.message;
                        var title = 'خطأ';
                        toastr.options = {
                            positionClass: 'toast-top-center',
                            onclick: null,
                            showMethod: 'slideDown',
                            hideMethod: "slideUp",

                        };
                        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                        $toastlast = $toast;
                    }

                },
                error: function (data) {

                }
            });
        });

        $('form#deleteForm').on('submit', function (e) {
            e.preventDefault();

            var id = $(this).attr('data-id');

            var $tr = $($('#currentRowOn' + id)).closest($('#currentRow' + id).parent().parent());

            // console.log($tr);

            var formData = new FormData(this);
            for (var value of formData.values()) {
                console.log(value); 
            }
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    if (data.status == true) {
                        var shortCutFunction = 'success';
                        var msg = data.message;
                        var title = 'نجاح';
                        toastr.options = {
                            positionClass: 'toast-top-center',
                            onclick: null,
                            showMethod: 'slideDown',
                            hideMethod: "slideUp",

                        };
                        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                        $toastlast = $toast;
                        Custombox.close();
                        // if(data.order_status == 1){
                        //     $("#order_status" + data.id).html('سارى');
                        // }elseif(data.order_status == 2){
                        //     $("#order_status" + data.id).html('مرفوض');
                        // }

                        $tr.find('td').fadeOut(1000, function () {
                                $tr.remove();
                            });
                        //location.reload();


                    }

                    if (data.status == false) {
                        var shortCutFunction = 'error';
                        var msg = data.message;
                        var title = 'خطأ';
                        toastr.options = {
                            positionClass: 'toast-top-center',
                            onclick: null,
                            showMethod: 'slideDown',
                            hideMethod: "slideUp",

                        };
                        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                        $toastlast = $toast;
                    }

                },
                error: function (data) {

                }
            });
        });
    </script>

@endsection