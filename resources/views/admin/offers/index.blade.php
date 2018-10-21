@extends('admin.layouts.master')

@section('title','العروض')

@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            
            <h4 class="page-title">العروض</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <div class="dropdown pull-right">
                   <a href="{{ route('offers.create') }}" class="btn btn-custom  waves-effect waves-light">
                    <span class="m-l-5">
                        <i class="fa fa-plus"></i> <span>إضافة</span> </span>
                </a>
                </div>
                
                <div class="pull-right" style="margin-left: 10px;">
                    @if(isset($type) && $type == 'search')
                        <a href="{{ route('offers.index') }}" style="float: left; margin-right: 15px;" class="btn btn-primary btn-sm"><i class="fa fa-eye" style="margin-left: 5px"></i>مشاهدة العروض
                        </a>
                    @endif
                </div>

                @if(isset($type) && $type != 'search')
                    <div class="row">
                        <form action="{{route('offers.search')}}" method="get">
                            
                            <div class="col-lg-2"> 
                                <label>القسم الرئيسلى</label>
                                <select name="cat_id" class="form-control" id="cat">
                                    <option value="" disabled selected>القسم الرئيسى...</option>
                                    @forelse($cat as $value)
                                        <option value="{{$value->id}}" id="subcate{{$value->id}}" data-subcats="{{$value->subcats}}">{{$value->name}}</option>
                                    @empty
                                    <option value="" disabled>لا توجد أقسام رئيسية</option>
                                    @endforelse
                                </select>
                                
                            </div>

                            <div class="col-lg-2"> 
                                <label>القسم الفرعى</label>
                                <select name="subcat_id" class="form-control" id="sub_cat">
                                    <option value="" disabled selected>القسم الفرعى...</option>
                                    
                                </select>
                                
                            </div>

                            <div class="col-lg-2">
                                <label>اسم المنتج</label>
                                <!-- <input type="text" class="form-control" name="name"> -->

                                <select name="id" class="form-control">
                                    <option value="" disabled selected>اسم المنتج...</option>
                                    @forelse($list as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @empty
                                    <option value="" disabled>لا توجد منتجات</option>
                                    @endforelse
                                </select>

                            </div>

                            <div class="col-lg-2">
                                <label>حالة العرض</label>
                                <!-- <input type="text" class="form-control" name="name"> -->

                                <select name="status" class="form-control">
                                    <option value="" disabled selected>حالة العرض...</option>
                                    <option value="1">متاح</option>
                                    <option value="0">غير متاح</option>
                                    
                                </select>

                            </div>

                            <div class="col-lg-3">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                            
                        </form>
                    </div>
                @endif

                <br> <br>

                <h4 class="header-title m-t-0 m-b-30">مشاهدة العروض</h4>

                <table class="table m-0  table-striped table-hover table-condensed" id="datatable-fixed-header">
                    <thead>
                    <tr>
                        <th>
                            م
                        </th>
                        <th>القسم الرئيسى</th>
                        <th>القسم الفرعى</th>
                        <th>اسم المنتج</th>
                        <th>عدد المنتجات</th>
                        <th>سعر المنتج فى العرض</th>
                        <th>حالة المنتج</th>
                        <th>العمليات المتاحة</th>

                    </tr>
                    </thead>
                    <tbody>

                    @php $i = 1; @endphp
                    @foreach($offers as $row)
                        <tr>
                            <td>{{$i++}}

                                <!--<div class="checkbox checkbox-primary checkbox-single">-->
                                <!--    <input type="checkbox" class="checkboxes-items"-->
                                <!--           value="{{ $row->id }}"-->
                                <!--           aria-label="Single checkbox Two">-->
                                <!--    <label></label>-->
                                <!--</div>-->

                            </td>
                            <td>{{category($row->category_id)}}</td>
                            <td>{{category($row->subcategory_id)}}</td>
                            <td>{{ $row->product_name }}</td>
                            <td>{{ $row->amount }}</td>
                            <td>{{ $row->price }}</td>
                            <td>{{ $row->is_available == 1 ? 'متاح':'غير متاح' }}</td>
                            <td>
                                
                                <!-- <a href="{{ route('offers.show', $row->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="{{ route('offers.edit', $row->id) }}" class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-edit"></i>
                                </a> -->

                                <!-- <a href="javascript:;" id="elementRow{{ $row->id }}" data-id="{{ $row->id }}"
                                   class="removeElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                    <i class="fa fa-remove"></i>

                                </a>
 -->
                                <a href="javascript:;" id="elementRow{{ $row->id }}" data-id="{{ $row->id }}" data-status="{{$row->is_available}}"
                                   class="elementStatus btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                    @if($row->is_available == 1)
                                        <label class="label label-danger label-xs">غير متاح</label>
                                    @else
                                        <label class="label label-success label-xs">متاح</label>
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
                        url: '{{ route('offers.activateOffer') }}',
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
            console.log('id',id);
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
                console.log('ee',id);
                if (isConfirm) {
                    $.ajax({
                        type: 'delete',
                        url: '{{ route('offers.destroy','+id+' ) }}',
                        //url: $(this).attr('action'),
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

        $("#cat").change(function(){
            $("#sub_cat").html('');
            var id = $(this).val();
            var data = $("#subcate"+id).data("subcats");
            console.log(id);
            console.log('subcat',data);
            var subCats = $(this).data("subcats");
            var sub_cats = $(this).attr("data-subcats");
            console.log(sub_cats);
            //var json = JSON.parse(sub_cats)
            $("#sub_cat").append('<option value="">برجاء الاختيار</option');
            $.each(data, function(key, val) {
                var a = val.products;
                console.log('a:',a);
                $("#sub_cat").append('<option value="' + val.id + '" id="prod'+val.id+'" data-products='+a+'>' + val.name + '</option>');
            });
        });


    </script>



@endsection


