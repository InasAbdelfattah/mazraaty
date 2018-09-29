@extends('admin.layouts.master')
@section('title','الطلبات')
@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            
            <h4 class="page-title">الطلبات</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="dropdown pull-right">
                    <!-- <a style="float: left; margin-right: 15px;" class="btn btn-danger btn-sm getSelected">
                        <i class="fa fa-trash" style="margin-left: 5px"></i> حذف المحدد
                    </a> -->
                    @if(isset($type) && $type == 'search')
                    <a href="{{route('orders.index')}}" style="float: left; margin-right: 15px;" class="btn btn-primary btn-sm"><i class="fa fa-eye" style="margin-left: 5px"></i>عرض جميع الطلبات
                    </a>
                    @endif

                    @if((isset($_GET['order_type']) && $_GET['order_type'] == 'reports') || (isset($order_type) && $order_type == 'reports'))
                    @php
                        if(isset($_GET['status'])):
                            $status = $_GET['status'];
                        else:
                            $status = '';
                        endif;
                    @endphp

                    <a class="btn btn-primary" href="{{route('orders.getExport').'?status='.$status.'&from='.$_GET['from'].'&to='.$_GET['to']}}">تصدير الى اكسل</a>
                    @endif
                </div>

                    <div class="row">

                        <form action="{{route('orders.search')}}" method="get">
                            <input type="hidden" name="order_type" value="{{$_GET['order_type']}}">
                            @if((isset($_GET['order_type']) && $_GET['order_type'] == 'reports') || (isset($order_type) && $order_type == 'reports'))
                            <div class="col-lg-2"> 
                                <select name="status" class="form-control">
                                    <option value="" disabled selected>حالة الطلب</option>
                                        <option value="0">جديد</option>
                                        <option value="1">جارى التجهيز</option>
                                        <option value="2">مرفوض</option>
                                        <option value="3">جارى التوصيل</option>
                                </select>
                                
                            </div>
                            @endif

                            @php 
                                $old = date('Y-m-d', strtotime('-5days'));
                                $new = date("Y-m-d"); 
                            @endphp
                            <!-- <div class="col-lg-1">
                                <label>من</label>
                            </div> -->
                            <div class="col-lg-2">
                                من : 
                                <input type="date" name="from" value="{{$old}}" class="form-control" placeholder="من : "/>
                            </div>

                            <!-- <div class="col-lg-1">
                                <label>الى</label>
                            </div> -->

                            <div class="col-lg-2">
                                إلى :
                                <input type="date" name="to" value="{{$new}}" class="form-control" placeholder="الى"/>
                            </div>

                            <div class="col-lg-3">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                        </form>
                    </div>

                <table id="datatable-fixed-header" class="table table-striped table-hover table-condensed" style="width:100%">
                    <thead>
                    <tr>
                        <th>م</th>
                        <th>وقت و تاريخ الطلب</th>
                        <th>رقم الطلب</th>
                        <th>اسم المستخدم</th>
                        @if((isset($_GET['order_type']) && $_GET['order_type'] != 'reports') || (isset($order_type) && $order_type != 'reports'))
                        <th>المدينة</th>
                        @endif
                        <th>حالة الطلب</th>
                        <th>العمليات المتاحة</th>

                    </tr>
                    </thead>
                    <tbody>
                    
                        @if(count($orders) > 0)
                        @php $i = 1; @endphp
                        @foreach($orders as $row)
                          
                            <tr>
                                <td>
                                    {{$i++}}
                                    <!-- <div class="checkbox checkbox-primary checkbox-single">
                                        <input type="checkbox" class="checkboxes-items"
                                               value="{{ $row->id }}"
                                               aria-label="Single checkbox Two">
                                        <label></label>
                                    </div> -->
                                </td>
    
                                
                                <td>{{$row->created_at}}</td>
                                <td>{{ $row->id }}</td>
                                <td> {{$row->user_name}} </td>
                                @if((isset($_GET['order_type']) && $_GET['order_type'] != 'reports') || (isset($order_type) && $order_type != 'reports'))
                                <td>{{ $row->user_city }} </td>
                                @endif
                                <!--<td> {{ $row->delivered_time }} </td>-->
                                <td id="order_status{{ $row->id }}"> 
                                    @if($row->status == 0) جديد
                                    @elseif($row->status ==1) جارى التجهيز
                                    @elseif($row->status ==2) مرفوض
                                    @elseif($row->status ==3) جارى التوصيل
                                    @else مكتمل
                                  
                                    @endif
    
                                     </td>
                                <td>
                                    <a href="{{ route('orders.show', $row->id) }}"
                                       class="btn btn-icon btn-xs waves-effect btn-info m-b-5">
                                        <i class="fa fa-eye"></i>
                                    </a>
    
                                    <!-- <a href="javascript:;" id="elementRow{{ $row->id }}"
                                       data-id="{{ $row->id }}"
                                       class="removeElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                        <i class="fa fa-remove"></i>
                                    </a> -->
                                @if((isset($_GET['order_type']) && $_GET['order_type'] != 'reports') || (isset($order_type) && $order_type != 'reports'))
                                    
                                    @if($row->status == 0)
                                    <a id="order_preparing{{ $row->id }}" href="javascript:;" id="preparedRow{{ $row->id }}"
                                       data-id="{{ $row->id }}" data-status="1" data-url="{{ route('orders.confirmOrder') }}"
                                       class="preparedElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-primary m-b-5">
                                        جارى التجهيز
                                    </a>

                                    <a id="order_change{{ $row->id }}" href="#custom-modal{{ $row->id }}"
                                        data-id="{{ $row->id }}" id="currentRow{{ $row->id }}"
                                        class="btn btn-danger btn-xs btn-trans waves-effect waves-light m-r-5 m-b-10"
                                        data-animation="fadein" data-plugin="custommodal"
                                        data-overlaySpeed="100" data-overlayColor="#36404a">رفض
                                    </a>
                                    <div id="custom-modal{{ $row->id }}" class="modal-demo"
                                                  data-backdrop="static">
                                                 <button type="button" class="close" onclick="Custombox.close();">
                                                     <span>&times;</span><span class="sr-only">Close</span>
                                                 </button>
                                                 <h4 class="custom-modal-title">سبب رفض الطلب</h4>
                                                 <div class="custom-modal-text text-right" style="text-align: right !important;">
                                                    <form id="status" action="{{ route('orders.confirmOrder') }}" method="post" data-id="{{ $row->id }}">
             
                                                        {{ csrf_field() }}
                                                 <input type="hidden" name="orderId" value="{{$row->id}}">
                                                 <input type="hidden" name="status" value="2" id="agree">
                                                        <div class="form-group ">
                                                                
                                                                <div>
                                                                    <label for="paid-signup">
                                                                         سبب الرفض 
                                                                    </label>
                                                                    <br>
                                                                    <textarea id="paid-signup" value="{{old('refuse_reason')}}" name="refuse_reason" id="reason" class="form-control" required data-parsley-trigger="keyup" data-parsley-required-message="لا بد من ادخال سبب الرفض"></textarea>
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
                                    @elseif($row->status == 1)
                                        <a id="order_delivered{{ $row->id }}" href="javascript:;" id="deliveredRow{{ $row->id }}"
                                       data-id="{{ $row->id }}" data-status="3" data-url="{{ route('orders.confirmOrder') }}"
                                       class="preparedElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-success m-b-5">
                                        جارى التوصيل
                                        </a>
                                    @endif
                                    
                                @endif
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div><!-- end col -->

   
    <!-- end row -->
@endsection


@section('scripts')



    <script>

         $('form#status').on('submit', function (e) {
            e.preventDefault();


            var id = $(this).attr('data-id');


            // var $tr = $($('#currentRowOn' + id)).closest($('#currentRow' + id).parent().parent());

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
                        $("#order_status" + id).html(data.order_status);
                            $("#order_change" + id).hide();
                            $("#order_preparing" + id).hide();
                            //location.reload();
                        Custombox.close();
                        
                            console.log(data);


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

         //deliveredElement

         $('body').on('click', '.deliveredElement', function () {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var url = $(this).attr('data-url');
            var $tr = $(this).closest($('#deliveredRow' + id).parent().parent());
            var data = {orderId: id , status: status};
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
                        $("#order_status" + id).html(data.order_status);
                        $("#order_delivered" + id).hide();

                            
                            location.reload();  
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
                        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
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

         //preparedElement

         $('body').on('click', '.preparedElement', function () {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var url = $(this).attr('data-url');
            var $tr = $(this).closest($('#preparedRow' + id).parent().parent());
            var data = {orderId: id , status: status};
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
                        $("#order_status" + id).html(data.order_status);
                        $("#order_preparing" + id).hide();
                        $("#order_change" + id).hide();
                        location.reload();  
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

        //remove element
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
                        url: '{{ route('orders.delete') }}',
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {

                            if (data.status == true) {
                                var shortCutFunction = 'success';
                                var msg = 'لقد تمت عملية الحذف بنجاح.';
                                var title = data.title;
                                toastr.options = {
                                    positionClass: 'toast-top-left',
                                    onclick: null
                                };
                                var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                                $toastlast = $toast;

                                $tr.find('td').fadeOut(1000, function () {
                                    $tr.remove();
                                });
                            }

                            // if (data.status == false) {
                            //     var shortCutFunction = 'error';
                            //     var msg = 'عفواً, لا يمكنك حذف العضوية الان نظراً لوجود 3 شركات مسجلين بها.';
                            //     var title = data.title;
                            //     toastr.options = {
                            //         positionClass: 'toast-top-left',
                            //         onclick: null
                            //     };
                            //     var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                            //     $toastlast = $toast;
                            // }


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
                            url: '{{ route('orders.group.delete') }}',
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
                                        $(this).parent().parent().parent().fadeOut();
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

    </script>

@endsection
