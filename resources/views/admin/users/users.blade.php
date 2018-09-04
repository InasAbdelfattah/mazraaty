@extends('admin.layouts.master')
@section('title', 'إدارة العملاء بالتطبيق')
@section('content')

    <!-- Page-Title -->
    <div class="row zoomIn">
        <div class="col-sm-12">
           
            <h4 class="page-title">@lang('global.users_management')</h4>
        </div>
    </div>

    <div class="row zoomIn">
        <div class="col-sm-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-sm-4 col-xs-8 m-b-30" style="display: inline-flex">
                        مشاهدة العملاء
                    </div>

                    <div class="col-sm-4 col-sm-offset-4 pull-left">
                        
                    </div>
                </div>

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
                        
                        <th>اسم المستخدم</th>
                        <th>رقم الجوال</th>
                        <th>المدينة</th>
                        <th>الحالة</th>
                        <th>تاريخ الاشتراك</th>
                        <!--<th>حالة الحذر</th>-->
                        <th>الخيارات</th>

                    </tr>
                    </thead>
                    <tbody>
                        @php $i = 0 ; @endphp 
                    @foreach($users as $user)
                    <input type="hidden" name="suspend_status" value="{{$user->is_suspend}}" id="suspend_status"/>
                    <input type="hidden" name="user_id" value="{{$user->id}}" id="user_id"/>
                        <!--{{$user->userCards}}-->
                        <tr id="currentRowOn{{$user->id}}">
                            <td>
                                <!--{{$i++}}-->
                                <div class="checkbox checkbox-primary checkbox-single">
                                    <input type="checkbox" style="margin-bottom: 0px;" class="checkboxes-items"
                                           value="{{ $user->id }}"
                                           aria-label="Single checkbox Two">
                                    <label></label>
                                </div>
                            </td>
                            

                            <td>{{ $user->name }}</td>
                            <!--<td>{{ $user->username  }}</td>-->
                            
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->city_id }}</td>
                            
                            <td>
                                @if($user->is_active == 1)
                                    <label class="label label-success label-xs">مفعل</label>
                                @else
                                    <label class="label label-danger label-xs">غير مفعل</label>
                                @endif
                            </td>
                            <td>{{$user->created_at }}</td>

                            <td>
                                
                                <a href="{{ route('users.show',$user->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <!-- <a href="{{ route('users.edit',$user->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-edit"></i>
                                </a> -->
                                
                                @if($user->id != 1)
                                    
                                     @if($user->is_suspend == 1)
                                    <a id="unsuspendForm{{$user->id}}" onclick="event.preventDefault();" class="btn btn-success btn-xs btn-trans waves-effect waves-light m-r-5 m-b-10">الغاء الحظر
                                    </a>

                                    <form id="activeForm2" data-id="{{ $user->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="userId" value="{{$user->id}}">
                                    </form>
                                    @else
                                    <a href="#custom-modal{{ $user->id }}"
                                        data-id="{{ $user->id }}"
                                        class="btn btn-danger btn-xs btn-trans waves-effect waves-light m-r-5 m-b-10" id="suspendForm{{$user->id}}" 
                                        data-animation="fadein" data-plugin="custommodal"
                                        data-overlaySpeed="100" data-overlayColor="#36404a">حظر
                                    </a>
                                    @endif
                                    <div id="custom-modal{{ $user->id }}" class="modal-demo"
                                                  data-backdrop="static">
                                                 <button type="button" class="close" onclick="Custombox.close();">
                                                     <span>&times;</span><span class="sr-only">Close</span>
                                                 </button>
                                                 <h4 class="custom-modal-title">سبب حظر المستخدم</h4>
                                                 <div class="custom-modal-text text-right" style="text-align: right !important;">
                                                    <form id="activeForm" action="{{ route('user.suspend') }}" method="post" data-id="{{ $user->id }}">
             
                                                        {{ csrf_field() }}
                                                         <input type="hidden" name="userId" value="{{$user->id}}">
                                                         
                                                        <div class="form-groذup ">
                                                                
                                                                <div>
                                                                    <label for="paid-signup">
                                                                         سبب التعطيل 
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
                                                                      
                                                 </div>
                                             </div>
                                

                                <!-- <a href="javascript:;" id="elementRow{{ $user->id }}" data-id="{{ $user->id }}" data-url="{{ route('users.destroy',$user->id) }}"
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
                                                    <form id="deleteForm" action="{{ route('users.destroy',$user->id) }}" method="post" data-id="{{ $user->id }}">
             
                                                        {{ csrf_field() }}
                                                         <input type="hidden" name="id" value="{{$user->id}}">

                                                         <input type="hidden" name="_method" value="delete">
                                                  
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
<script src="http://malsup.github.com/jquery.form.js"></script>

    <script>

        @if(session()->has('success'))
        setTimeout(function () {
            //showMessage('{{ session()->get('success') }}');
            showMessage('{{ session('success') }}');
        }, 3000);
        @endif

        // $(function() {
        //     var user_id = $("#user_id").val();
        //     var suspend_status = $("#suspend_status").val();
        //     console.log(suspend_status);

        //     if(suspend_status == 1){
        //         $("#suspendForm" + user_id).hide();
        //         $("#unsuspendForm" + user_id).show();
        //     }else{
        //         //$("#currentRow" + data.id).html('حظر');
        //         $("#unsuspendForm" + user_id).hide();
        //         $("#suspendForm" + user_id).show();
        //     }
        // });

        $('body').on('click', '.removeElement', function () {
            var id = $(this).attr('data-id');
            var url = $(this).attr('action');
            console.log('iddd:',id);
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
                        url: url,
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
                            location.reload();
                            // $tr.find('td').fadeOut(1000, function () {
                            //     $tr.remove();
                            // });
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
                //url: '{{ route('user.suspend') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                        console.log(data.is_suspend);
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
                       console.log(data.is_suspend);
                        //if(data.is_suspend == 0){
                            //$("#currentRow" + data.id).html('الغاء الحظر');
                            $("#unsuspendForm"+ data.id).show();
                            location.reload();
                            //$("#suspendForm"+ data.id).hide();
                        // }else if (data.is_suspend == 1){
                        //     $("#suspendForm"+ data.id).show();
                        //     $("#unsuspendForm"+ data.id).hide();
                        // }
                        // $tr.find('td').fadeOut(1000, function () {
                        //         $tr.remove();
                        //     });
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
user_id = $("#user_id").val();
   $("#unsuspendForm").on('click', function (e) {

    //$('form#activeForm2').on('submit', function (e) {
    //document.getElementById('activeForm2').on('submit', function (e) {
            e.preventDefault();

            $("#activeForm2").ajaxSubmit({url: '{{ route('user.suspend') }}', type: 'post' ,
             data: {userId : user_id , _token : '{{csrf_token()}}' },
             cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                        console.log(data.is_suspend);
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
                       console.log(data.is_suspend);
                        //if(data.is_suspend == 0){
                            //$("#currentRow" + data.id).html('الغاء الحظر');
                        //     $("#unsuspendForm"+ data.id).show();
                        //     $("#suspendForm"+ data.id).hide();
                        // }else if (data.is_suspend == 1){
                            $("#suspendForm"+ data.id).show();
                            $("#unsuspendForm"+ data.id).hide();
                        //}
                        
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

        })



            // var id = $(this).attr('data-id');
            // var $tr = $($('#currentRowOn' + id)).closest($('#currentRow' + id).parent().parent());

            // console.log($tr);

            // var formData = new FormData(this);
            // for (var value of formData.values()) {
            //     console.log(value); 
            // }
            // $.ajax({
            //     type: 'POST',
            //     //url: $(this).attr('action'),
            //     url: '{{ route('user.suspend') }}',
            //     data: formData,
            //     //data : { foo : 'bar', bar : 'foo' },
            //     cache: false,
            //     contentType: false,
            //     processData: false,
            //     success: function (data) {
            //             console.log(data.is_suspend);
            //         if (data.status == true) {
            //             var shortCutFunction = 'success';
            //             var msg = data.message;
            //             var title = 'نجاح';
            //             toastr.options = {
            //                 positionClass: 'toast-top-center',
            //                 onclick: null,
            //                 showMethod: 'slideDown',
            //                 hideMethod: "slideUp",

            //             };
            //             var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
            //             $toastlast = $toast;
            //             Custombox.close();
            //            console.log(data.is_suspend);
            //             //if(data.is_suspend == 0){
            //                 //$("#currentRow" + data.id).html('الغاء الحظر');
            //             //     $("#unsuspendForm"+ data.id).show();
            //             //     $("#suspendForm"+ data.id).hide();
            //             // }else if (data.is_suspend == 1){
            //                 $("#suspendForm"+ data.id).show();
            //                 $("#unsuspendForm"+ data.id).hide();
            //             //}
                        
            //         }

            //         if (data.status == false) {
            //             var shortCutFunction = 'error';
            //             var msg = data.message;
            //             var title = 'خطأ';
            //             toastr.options = {
            //                 positionClass: 'toast-top-center',
            //                 onclick: null,
            //                 showMethod: 'slideDown',
            //                 hideMethod: "slideUp",

            //             };
            //             var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
            //             $toastlast = $toast;
            //         }

            //     },
            //     error: function (data) {

            //     }
            // });
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