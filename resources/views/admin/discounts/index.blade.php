@extends('admin.layouts.master')

@section('title','أكواد الخصم')

@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            
            <h4 class="page-title">أكواد الخصم</h4>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <div class="dropdown pull-right">
                   <a href="{{ route('discounts.create') }}" class="btn btn-custom  waves-effect waves-light">
                    <span class="m-l-5">
                        <i class="fa fa-plus"></i> <span>إضافة</span> </span>
                </a>
                </div>

                <div class="row">
                    <p>البحث خلال الفترة الزمنية : </p>
                </div>
                
                <div class="row">
                    <form action="{{route('discounts.search')}}" method="get">
                        {{csrf_field()}}
                        
                        @php 
                            $old = date('Y-m-d', strtotime('-5days'));
                            $new = date("Y-m-d"); 
                        @endphp
                        <div class="col-lg-5">
                            <label>من</label>
                            <input type="date" name="from_date" value="{{$old}}" class="form-control" placeholder="من : "/>
                        </div>

                        <div class="col-lg-5">
                            <label>الى</label>
                            <input type="date" name="to_date" value="{{$new}}" class="form-control" placeholder="الى"/>
                        </div>
                        
                        
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-primary">بحث</button>
                        </div>
                        
                    </form>
                </div>
                <br> <br>

                <h4 class="header-title m-t-0 m-b-30">مشاهدة أكواد الخصم</h4>

                <table class="table m-0  table-striped table-hover table-condensed" id="datatable-fixed-header">
                    <thead>
                    <tr>
                        <th>
                            م
                        </th>
                        <th>كود الخصم</th>
                        <th>نسبة الخصم</th>
                        <th>تاريخ بداية الخصم</th>
                        <th>تاريخ انتهاء الخصم</th>
                        <th>عدد مرات استخدام كود الخصم</th>
                        <th>العمليات المتاحة</th>

                    </tr>
                    </thead>
                    <tbody>

                    @php $i = 1; @endphp
                    @foreach($discounts as $discount)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{ $discount->code }}</td>
                            <td>{{ $discount->ratio }}</td>
                            <td>{{ $discount->from }}</td>
                            <td>{{ $discount->to }}</td>
                            <td>{{ $discount->times }}</td>
                            <td>
                                
                                <a href="{{ route('discounts.show', $discount->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach


                    </tbody>
                </table>

            </div>
        </div><!-- end col -->

    </div>
    <!-- end row -->
@endsection


@section('scripts')


    <script>

        $('body').on('click', '.elementStatus', function () {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            
            if(status == 0){
                status = 1;
                var type = 'success';
            }else{
                status = 0;
                var type = 'error';
            }
            
            console.log(status);
            var $tr = $(this).closest($('#elementRow' + id).parent().parent());
            swal({
                title: "هل انت متأكد؟",
                text: "",
                type: type,
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
                        url: '{{ route('product.activateProduct') }}',
                        data: {id: id , status: status},
                        dataType: 'json',
                        success: function (data) {
                            console.log(data);
                            if (data.status == true) {
                                var shortCutFunction = 'success';
                                var msg = data.message;
                                var title = data.title;
                                toastr.options = {
                                    positionClass: 'toast-top-center',
                                    onclick: null,
                                    showMethod: 'slideDown',
                                    hideMethod: "slideUp",
                                };
                                var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                                $toastlast = $toast;
                                location.reload();

                                // $tr.find('td').fadeOut(1000, function () {
                                //     $tr.remove();
                                // });

                            } else {
                                var shortCutFunction = 'error';
                                var msg = data.message;
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
                        type: 'delete',
                        url: '{{ route('products.destroy','+id+') }}',
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == true) {
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
                                location.reload();
                                // $tr.find('td').fadeOut(1000, function () {
                                //     $tr.remove();
                               // });

                            } else {
                                var shortCutFunction = 'error';
                                var msg = data.message;
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

    </script>



@endsection


