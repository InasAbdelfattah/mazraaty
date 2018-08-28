@extends('admin.layouts.master')

@section('title','المنتجات')

@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            
            <h4 class="page-title">المنتجات</h4>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <div class="dropdown pull-right">
                   <a href="{{ route('products.create') }}" class="btn btn-custom  waves-effect waves-light">
                    <span class="m-l-5">
                        <i class="fa fa-plus"></i> <span>إضافة</span> </span>
                </a>
                </div>
                
                <h4 class="header-title m-t-0 m-b-30">مشاهدة المنتجات</h4>

                <table class="table m-0  table-striped table-hover table-condensed" id="datatable-fixed-header">
                    <thead>
                    <tr>
                        <th>
                            م
                        </th>
                        <th>القسم الرئيسى</th>
                        <th>القسم الفرعى</th>
                        <th>اسم المنتج</th>
                        <th>صورة المنتج</th>
                        <th>العمليات المتاحة</th>

                    </tr>
                    </thead>
                    <tbody>

                    @php $i = 1; @endphp
                    @foreach($products as $product)
                        <tr>
                            <td>{{$i++}}

                                <!--<div class="checkbox checkbox-primary checkbox-single">-->
                                <!--    <input type="checkbox" class="checkboxes-items"-->
                                <!--           value="{{ $product->id }}"-->
                                <!--           aria-label="Single checkbox Two">-->
                                <!--    <label></label>-->
                                <!--</div>-->

                            </td>
                            <td>{{category($product->category_id)}}</td>
                            <td>{{category($product->subcategory_id)}}</td>
                            <td>{{ $product->name }}</td>
                            <td style="width: 10%;">
                                <a data-fancybox="gallery"
                                   href="{{ getDefaultImage(request()->root().'/files/products/'.$product->image, request()->root().'/assets/admin/custom/images/default.png') }}">
                                    <img style="width: 50%; border-radius: 50%; height: 49px;"
                                         src="{{ getDefaultImage(request()->root().'/files/products/'.$product->image, request()->root().'/assets/admin/custom/images/default.png') }}"/>
                                </a>
                            </td>
                            <td>
                                
                                <a href="{{ route('products.show', $product->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-edit"></i>
                                </a>

                                {{--<a href="javascript:;" id="elementRow{{ $product->id }}" data-id="{{ $product->id }}"--}}
                                   {{--class="removeElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">--}}
                                    {{--<i class="fa fa-remove"></i>--}}

                                {{--</a>--}}
                                <a href="javascript:;" id="elementRow{{ $product->id }}" data-id="{{ $product->id }}" data-status="{{$product->status}}"
                                   class="elementStatus btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                    @if($product->status == 1)
                                        <label class="label label-danger label-xs">تعطيل</label>
                                    @else
                                        <label class="label label-success label-xs">تفعيل</label>
                                    @endif
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


