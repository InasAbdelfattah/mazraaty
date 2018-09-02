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
                </div>

                <div class="row">
                <form action="{{route('orders.search')}}" method="get">
                    {{csrf_field()}}

                    <div class="col-lg-3">
                       
                        <!--<input type="number" name="order_id" class="form-control" placeholder="رقم الطلب"/>-->
                        <select name="card_id" class="form-control">
                            <option value="">اسم البطاقة</option>
                            @forelse($cards as $card)
                                <option value="{{$card->id}}">{{$card->name}}</option>
                            @empty
                                <option value="">لا يوجد</option>
                            @endforelse
                        </select>
                        
                    </div>
                    
                    <div class="col-lg-3">
                       
                       
                        <select name="status" class="form-control">
                            <option value="" disabled selected>حالة الطلب</option>
                                <option value="0">جديد</option>
                                <option value="3">تجديد</option>
                        </select>
                        
                    </div>
                    <div class="row">
                        @php 
                            $old = date('Y-m-d', strtotime('-5days'));
                            $new = date("Y-m-d"); 
                        @endphp
                        <div class="col-lg-3">
                            <label>من</label>
                            <input type="date" name="from" value="{{$old}}" class="form-control" placeholder="من : "/>
                        </div>

                        <div class="col-lg-3">
                            <label>الى</label>
                            <input type="date" name="to" value="{{$new}}" class="form-control" placeholder="الى"/>
                        </div>
                    </div>
                    <!--<div class="col-lg-3">-->
                       
                        <!-- <input type="text" name="card_type"/> -->
                    <!--    <select name="card_type" class="form-control">-->
                    <!--        <option value="">نوع البطاقة</option>-->
                    <!--        @forelse($categories as $cat)-->
                    <!--            <option value="{{$cat->id}}">{{$cat->name}}</option>-->
                    <!--        @empty-->
                    <!--            <option value="">لا يوجد</option>-->
                    <!--        @endforelse-->
                    <!--    </select>-->
                    <!--</div>-->
                    
                    <div class="col-lg-3">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                    
                </form>
                </div>
                
                <table id="datatable-fixed-header" class="table table-striped table-hover table-condensed" style="width:100%">
                    <thead>
                    <tr>
                        <th>م</th>
                        <th>رقم الطلب</th>
                        <th>تاريخ الطلب</th>
                        <th>اسم البطاقة</th>
                        <th>اسم المستخدم</th>
                        <th>الرمز الترويجى</th>
                        <th>كود الموظف</th>
                        <!--<th>الوقت المناسب للتسليم</th>-->
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
    
                                <td>{{ $row->id }}</td>
                                <td>{{$row->created_at}}</td>
                                <td>{{ $row->card_name }}</td>
                                <td> {{$row->user_name}} </td>
                                <td>{{ $row->promo_code }} </td>
                                <td> {{$row->emp_code}} </td>
                                <!--<td> {{ $row->delivered_time }} </td>-->
                                <td id="order_status{{ $row->id }}"> 
                                    @if($row->status == 0) جديد
                                    @elseif($row->status ==1) سارى
                                    @elseif($row->status ==3) تجديد
                                    @else مرفوض
                                    @endif
    
                                     </td>
                                <td>
                                    <a href="{{ route('orders.show', $row->id) }}"
                                       class="btn btn-icon btn-xs waves-effect btn-info m-b-5">
                                        <i class="fa fa-eye"></i>
                                    </a>
    
                                    <a href="javascript:;" id="elementRow{{ $row->id }}"
                                       data-id="{{ $row->id }}"
                                       class="removeElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                    @if($row->status == 0 || $row->status == 3)
                                    <a id="order_change{{ $row->id }}" href="#custom-modal{{ $row->id }}"
                                        data-id="{{ $row->id }}" id="currentRow{{ $row->id }}"
                                        class="btn btn-success btn-xs btn-trans waves-effect waves-light m-r-5 m-b-10"
                                        data-animation="fadein" data-plugin="custommodal"
                                        data-overlaySpeed="100" data-overlayColor="#36404a">قبول أو رفض
                                    </a>
                                    <div id="custom-modal{{ $row->id }}" class="modal-demo"
                                                  data-backdrop="static">
                                                 <button type="button" class="close" onclick="Custombox.close();">
                                                     <span>&times;</span><span class="sr-only">Close</span>
                                                 </button>
                                                 <h4 class="custom-modal-title">قبول أو رفض الطلب</h4>
                                                 <div class="custom-modal-text text-right" style="text-align: right !important;">
                                                    <form id="status" action="{{ route('orders.confirmOrder') }}" method="post" data-id="{{ $row->id }}">
             
                                                        {{ csrf_field() }}
                                                 <input type="hidden" name="orderId" value="{{$row->id}}">
                                                        <div class="form-group ">
                                                                <div>
                                                                    <input id="checkbox-signup" type="radio" value="1" name="status" id="agree" {{ old('status') ? 'checked' : '' }} required data-parsley-trigger="keyup" data-parsley-required-message="لا بد من اختيار حالة الطلب">
                                                                    <label for="checkbox-signup">
                                                                         قبول
                                                                    </label>
                                                                </div>
                
                                                                <div>
                                                                    <input id="checkbox-signup" type="radio" value="2" required data-parsley-trigger="keyup" data-parsley-required-message="لا بد من اختيار حالة الطلب"
                                            name="status" id="agree" {{ old('status') ? 'checked' : '' }}>
                                                                    <label for="checkbox-signup">
                                                                        رفض
                                                                    </label>
                                                                </div>
                                                                <br>
                                                                <div>
                                                                    <label for="paid-signup">
                                                                         سبب الرفض 
                                                                    </label>
                                                                    <br>
                                                                    <textarea id="paid-signup" value="{{old('refuse_reason')}}" name="refuse_reason" id="reason" class="form-control"></textarea>
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
    
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                </div>

            </div>
        </div><!-- end col -->

    </div>
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
                        Custombox.close();
                        
                            console.log(data);
                            $("#order_status" + id).html(data.order_status);
                            $("#order_change" + id).hide();
                        
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
