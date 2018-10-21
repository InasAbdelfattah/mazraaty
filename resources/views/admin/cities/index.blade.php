@extends('admin.layouts.master')
@section('title','المدن')

@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            
            <h4 class="page-title">المدن</h4>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="dropdown pull-right">
                    <a href="{{ route('cities.create') }}" class="btn btn-custom waves-effect waves-light">إضافة <span class="m-l-5">
                        <i class="fa fa-plus"></i></span>
                    </a>
                </div>

                <div class="col-sm-4 col-sm-offset-4 pull-left">
                    @if(isset($type) && $type == 'search')
                        <a href="{{ route('cities.index') }}" style="float: left; margin-right: 15px;" class="btn btn-primary btn-sm"><i class="fa fa-eye" style="margin-left: 5px"></i>مشاهدة المدن
                        </a>
                    @endif
                </div>

                @if(isset($type) && $type != 'search')
                    <div class="row">
                        <form action="{{route('cities.search')}}" method="get">
                            
                            @php 
                                $old = date('Y-m-d', strtotime('-5days'));
                                $new = date("Y-m-d"); 
                            @endphp
                            <div class="col-lg-4"> 
                                <label>المدينة</label>
                                <select name="city" class="form-control">
                                    <option value="" disabled selected>المدينة ...</option>
                                    @forelse($list as $city)
                                        <option value="{{$city->id}}">{{$city->name}}</option>
                                    @empty
                                    <option value="" disabled>لا توجد مدن</option>
                                    @endforelse
                                </select>
                                
                            </div>

                            <div class="col-lg-4">
                                <label>حالة المدينة</label>
                                <select class="form-control" name="status">
                                    <option value="" disabled selected>الحالة ...</option>
                                    <option value="1">مفعل</option>
                                    <option value="0">معطل</option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                            
                        </form>
                    </div>
                @endif

                <br> <br>

                <h4 class="header-title m-t-0 m-b-30">مشاهدة المدن</h4>

                <table id="datatable-fixed-header" class="table table-striped table-hover table-condensed"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th>
                            م
                            <!-- <div class="checkbox checkbox-primary checkbox-single">
                                <input type="checkbox" name="check" onchange="checkSelect(this)"
                                       value="option2"
                                       aria-label="Single checkbox Two">
                                <label></label>
                            </div> -->
                        </th>

                        <th>اسم المدينة</th>
                        <th>حالة المدينة</th>
                        <th>العمليات المتاحة</th>

                    </tr>
                    </thead>
                    <tbody>

                        @php $i = 1 ; @endphp 
                    @foreach($cities as $row)
                        <tr>

                            <td>{{$i++}}
                                <!-- <div class="checkbox checkbox-primary checkbox-single">
                                    <input type="checkbox" class="checkboxes-items"
                                           value="{{ $row->id }}"
                                           aria-label="Single checkbox Two">
                                    <label></label>
                                </div> -->
                            </td>

                            <td>{{ $row->name }}</td>
                            <td>{{ $row->status == 1 ? 'مفعل' : 'معطل' }}</td>
                            <td>
                                
                                <a href="{{ route('cities.edit', $row->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-edit"></i>
                                </a>
                                {{--<a href="javascript:;" id="elementRow{{ $row->id }}" data-id="{{ $row->id }}"--}}
                                   {{--class="removeElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">--}}
                                    {{--<i class="fa fa-remove"></i>--}}
                                {{--</a>--}}

                                <a href="javascript:;" id="elementRow{{ $row->id }}" data-id="{{ $row->id }}" data-status="{{$row->status}}"
                                   class="elementStatus btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                    @if($row->status == 1)
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
            
            if(status == 1){
                status = 0;
                var type = 'error';
            }else{
                status = 1;
                var type = 'success';
            }
            
            console.log('statusss',status);
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
                        url: '{{ route('city.activateArea') }}',
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
                            url: '{{ route('cities.group.delete') }}',
                            data: {ids: sum},
                            dataType: 'json',
                            success: function (data) {
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
                        url: '{{ route('cities.destroy','+id+') }}',
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
                            if (data.status == false) {
                                var shortCutFunction = 'error';
                                var msg = data.message;
                                var title = data.title;
                                toastr.options = {
                                    positionClass: 'toast-top-left',
                                    onclick: null
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



