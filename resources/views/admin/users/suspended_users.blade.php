@extends('admin.layouts.master')
@section('title', 'إدارة المستخدمين')
@section('content')

    <!-- Page-Title -->
    <div class="row zoomIn">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
           
           <button type="button" class="btn btn-custom  waves-effect waves-light"
                            onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i
                                    class="fa fa-reply"></i></span>
                    </button>

             <!--   <a href="{{ route('users.create') }}" type="button" class="btn btn-custom waves-effect waves-light"
                   aria-expanded="false"> إضافة
                    <span class="m-l-5">
                        <i class="fa fa-user"></i>
                    </span>
                </a>

                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </div> -->
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
                        <!-- <a style="float: left; margin-right: 15px;    margin-bottom: 20px;"
                           class="btn btn-danger btn-sm getSelected">
                            <i class="fa fa-trash" style="margin-left: 5px"></i> حذف المحدد
                        </a> -->

                        <!--<a style="float: left;" class="btn btn-info btn-sm getSelectedAndSuspend">-->
                        <!--    <i class="fa fa-users" style="margin-left: 5px"></i> حظر المستخدمين-->
                        <!--</a>-->
                    </div>
                </div>

                {{--<div class="col-sm-4 col-xs-8 m-b-30" style="display: inline-flex">--}}
                {{--<input type="text" name="filter" class="filteriTems form-control" placeholder="بحث..."--}}
                {{--id="filterItems"--}}
                {{--data-url="{{ route('users.filter') }}"/>--}}

                {{--<select id="recordNumber" class="filteriTems form-control"--}}
                {{--data-url="{{ route('users.filter') }}" style="width: 80px;margin-right: 10px">--}}

                {{--<option value="10">10</option>--}}
                {{--<option value="15">15</option>--}}
                {{--<option value="20">20</option>--}}
                {{--<option value="25">25</option>--}}
                {{--<option value="50">50</option>--}}
                {{--<option value="100">100</option>--}}

                {{--</select>--}}
                {{--</div>--}}



                {{--<div class="table-rep-plugin">--}}
                {{--<div class="articles">--}}
                {{--@include('admin.users.load')--}}
                {{--</div>--}}
                {{--</div>--}}


                <table id="datatable-fixed-header" class="table  table-striped">
                    <thead>
                    <tr>
                        <th>
                            <div class="checkbox checkbox-primary checkbox-single">
                                <input type="checkbox" style="margin-bottom: 0px;" name="check"
                                       onchange="checkSelect(this)"
                                       value="option2"
                                       aria-label="Single checkbox Two">
                                <label></label>
                            </div>
                        </th>
                        <th>الصورة</th>
                        <th>الاسم بالكامل</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الجوال</th>
                        <th>الحالة</th>
                        <!--<th>حالة الحذر</th>-->
                        <th>الخيارات</th>

                    </tr>
                    </thead>
                    <tbody>
                        @php $i = 0 ; @endphp 
                    @foreach($users as $user)

                        <tr id="currentRowOn{{$user->id}}">
                            <td>{{$i++}}
                                <!-- <div class="checkbox checkbox-primary checkbox-single">
                                    <input type="checkbox" style="margin-bottom: 0px;" class="checkboxes-items"
                                           value="{{ $user->id }}"
                                           aria-label="Single checkbox Two">
                                    <label></label>
                                </div> -->
                            </td>
                            <td style="width: 10%;">
                                <a data-fancybox="gallery"
                                   href="{{ $helper->getDefaultImage($user->image, request()->root().'/assets/admin/custom/images/default.png') }}">
                                    <img style="width: 50%; border-radius: 50%; height: 49px;"
                                         src="{{ $helper->getDefaultImage($user->image, request()->root().'/assets/admin/custom/images/default.png') }}"/>
                                </a>

                            </td>

                            <td>{{ $user->name }}</td>
                            <!--<td>{{ $user->username  }}</td>-->
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            
                            <td>
                                @if($user->is_active == 1)
                                    <label class="label label-success label-xs">مفعل</label>
                                @else
                                    <label class="label label-danger label-xs">غير مفعل</label>
                                @endif
                            </td>

                            <!--<td>-->
                            <!--    @if($user->is_suspend == 0)-->
                            <!--        <label class="label label-success label-xs">غير محذور</label>-->
                            <!--    @else-->
                            <!--        <label class="label label-danger label-xs"> محذور</label>-->
                            <!--    @endif-->
                            <!--</td>-->


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
                                <!-- <a href="javascript:;" id="elementRow{{ $user->id }}" data-id="{{ $user->id }}"
                                   class="removeElement btn-xs btn-icon btn-trans btn-sm waves-effect waves-light btn-danger m-b-5">
                                    <i class="fa fa-remove"></i>

                                </a> -->

                                <a href="#custom-modal2{{ $user->id }}"
                                        data-id="{{ $user->id }}" id="currentRow{{ $user->id }}"
                                        class="btn-xs btn-icon btn-trans btn-sm waves-effect waves-light btn-danger m-b-5"
                                        data-animation="fadein" data-plugin="custommodal"
                                        data-overlaySpeed="100" data-overlayColor="#36404a"><i class="fa fa-remove"></i>
                                    </a>
                                    <div id="custom-modal2{{ $user->id }}" class="modal-demo"
                                                  data-backdrop="static">
                                                 <button type="button" class="close" onclick="Custombox.close();">
                                                     <span>&times;</span><span class="sr-only">Close</span>
                                                 </button>
                                                 <h4 class="custom-modal-title">سبب حذف المستخدم</h4>
                                                 <div class="custom-modal-text text-right" style="text-align: right !important;">
                                                    <form id="deleteForm" action="{{ route('user.delete') }}" method="post" data-id="{{ $user->id }}">
             
                                                        {{ csrf_field() }}
                                                         <input type="hidden" name="id" value="{{$user->id}}">
                                                  
                                                        <div class="form-group ">
                                                                
                                                                <div>
                                                                    <label for="paid-signup">
                                                                         سبب الحذف 
                                                                    </label>
                                                                    <br>
                                                                    <textarea id="paid-signup" value="{{old('delete_reason')}}" name="delete_reason" id="reason" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                
                
                                                            <div class="form-group text-right m-t-20">
                                                                <button class="btn btn-primary waves-effect waves-light m-t-0"
                                                                        type="submit">
                                                                    حفظ البيانات
                                                                </button>
                                                                <button onclick="Custombox.close();" type="reset"
                                                                        class="btn btn-default waves-effect waves-light m-l-5 m-t-0">
                                                                    إلغاء
                                                                </button>
                                                            </div>
                
                                                        </form>
                                                                      
                                                 </div>
                                             </div>


                                @if($user->is_active == 0)
                                <a href="#custom-modal{{ $user->id }}"
                                    data-id="{{ $user->id }}" id="currentRow{{ $user->id }}"
                                    class="btn btn-success btn-xs btn-trans waves-effect waves-light m-r-5 m-b-10"
                                    data-animation="fadein" data-plugin="custommodal"
                                    data-overlaySpeed="100" data-overlayColor="#36404a">تفعيل
                                </a>
                                <div id="custom-modal{{ $user->id }}" class="modal-demo"
                                              data-backdrop="static">
                                             <button type="button" class="close" onclick="Custombox.close();">
                                                 <span>&times;</span><span class="sr-only">Close</span>
                                             </button>
                                             <h4 class="custom-modal-title">سبب التفعيل</h4>
                                             <div class="custom-modal-text text-right" style="text-align: right !important;">
                                                <form id="activeForm" action="{{ route('user.suspend') }}" method="post" data-id="{{ $user->id }}">
         
                                                    {{ csrf_field() }}
                                             <input type="hidden" name="userId" value="{{$user->id}}">
                                             <input type="hidden" name="is_active" value="1">
                                                    <div class="form-group ">
                                                            
                                                            <div>
                                                                <label for="paid-signup">
                                                                     سبب التفعيل 
                                                                </label>
                                                                <br>
                                                                <textarea id="paid-signup" value="{{old('reason')}}" name="reason" id="reason" class="form-control"></textarea>
                                                            </div>
                                                        </div>
            
            
                                                        <div class="form-group text-right m-t-20">
                                                            <button class="btn btn-primary waves-effect waves-light m-t-0"
                                                                    type="submit">
                                                                حفظ البيانات
                                                            </button>
                                                            <button onclick="Custombox.close();" type="reset"
                                                                    class="btn btn-default waves-effect waves-light m-l-5 m-t-0">
                                                                إلغاء
                                                            </button>
                                                        </div>
            
                                                    </form>
                                                     
                                                </form>
         
                                             </div>
                                         </div>
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

        @if(session()->has('success'))
        setTimeout(function () {
            //showMessage('{{ session()->get('success') }}');
            showMessage('{{ session('success') }}');
        }, 3000);
        @endif

        $('body').on('click', '.removeElement', function () {
            var id = $(this).attr('data-id');
            var $tr = $(this).closest($('#elementRow' + id).parent().parent());
            swal({
                title: "هل انت متأكد؟",
                text: "",
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
                        url: '{{ route('user.delete') }}',
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
                            url: '{{ route('users.group.delete') }}',
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

        $('.getSelectedAndSuspend').on('click', function () {

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
                            url: '{{ route('users.group.suspend') }}',
                            data: {ids: sum},
                            dataType: 'json',
                            success: function (data) {
                                $('#catTrashed').html(data.trashed);
                                if (data) {

                                    var shortCutFunction = 'success';
                                    var msg = 'لقد تمت عملية الحظر بنجاح.';
                                    var title = data.title;
                                    toastr.options = {
                                        positionClass: 'toast-top-left',
                                        onclick: null
                                    };
                                    var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                                    $toastlast = $toast;

                                    location.reload();
                                }

                                $('.checkboxes-items').each(function () {
                                    if ($(this).prop('checked') == true) {
                                        //$(this).parent().parent().parent().remove();
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