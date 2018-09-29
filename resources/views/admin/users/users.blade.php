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
                        @if(isset($type) && $type == 'search')
                            <a href="{{route('users.app_users')}}" style="float: left; margin-right: 15px;" class="btn btn-primary btn-sm"><i class="fa fa-eye" style="margin-left: 5px"></i>عرض جميع العملاء
                            </a>
                        @endif
                    </div>
                </div>

                @if(isset($type) && $type != 'search')
                <div class="row">

                        <form action="{{route('users.search')}}" method="get">
                            <input type="hidden" name="is_admin" value="0">
                            <div class="col-lg-4">
                                <input type="text" name="phone" placeholder="رقم الجوال" class="form-control phone"/>
                            </div>
                            <div class="col-lg-4"> 
                                <select name="city" class="form-control">
                                    <option value="" disabled selected>المدينة</option>
                                    @forelse($cities as $city)
                                        <option value="{{$city->id}}">{{$city->name}}</option>
                                    @empty
                                    <option value="" disabled>لا توجد مدن</option>
                                    @endforelse
                                </select>
                                
                            </div>

                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                        </form>
                </div>
                @endif

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
                        @php $i = 1 ; @endphp 
                    @foreach($users as $user)
                    <input type="hidden" name="suspend_status" value="{{$user->is_suspend}}" id="suspend_status"/>
                    <input type="hidden" name="user_id" value="{{$user->id}}" id="user_id"/>
                        <!--{{$user->userCards}}-->
                        <tr id="currentRowOn{{$user->id}}">
                            <td>
                                {{$i++}}
                                <!-- <div class="checkbox checkbox-primary checkbox-single">
                                    <input type="checkbox" style="margin-bottom: 0px;" class="checkboxes-items"
                                           value="{{ $user->id }}"
                                           aria-label="Single checkbox Two">
                                    <label></label>
                                </div> -->
                            </td>
                            

                            <td>{{ $user->name }}</td>
                            <!--<td>{{ $user->username  }}</td>-->
                            
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->city ? $user->city->name : '' }}</td>
                            
                            <td id="is_active{{$user->id}}">
                            
                                @if($user->is_suspend == 0)
                                    <label class="label label-success label-xs">مفعل</label>
                                @else
                                    <label class="label label-danger label-xs">محظور</label>
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
                                    <a id="activateUser{{ $user->id }}" href="javascript:;"
                                           data-id="{{ $user->id }}" data-status="0" data-url="{{ route('user.suspend') }}"
                                           class="activateElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-primary m-b-5">
                                           تفعيل
                                        </a>
                                    @else
                                        <a href="#custom-modal{{ $user->id }}"
                                        data-id="{{ $user->id }}" id="currentRow{{ $user->id }}"
                                        class="btn btn-success btn-xs btn-trans waves-effect waves-light m-r-5 m-b-10"
                                        data-animation="fadein" data-plugin="custommodal"
                                        data-overlaySpeed="100" data-overlayColor="#36404a">حظر
                                        </a>
                                <div id="custom-modal{{ $user->id }}" class="modal-demo"
                                              data-backdrop="static">
                                             <button type="button" class="close" onclick="Custombox.close();">
                                                 <span>&times;</span><span class="sr-only">Close</span>
                                             </button>
                                             <h4 class="custom-modal-title">سبب تعطيل المستخدم</h4>
                                             <div class="custom-modal-text text-right" style="text-align: right !important;">
                                                <form id="activeForm" action="{{ route('user.suspend') }}" method="post" data-id="{{ $user->id }}">
         
                                                    {{ csrf_field() }}
                                             <input type="hidden" name="userId" value="{{$user->id}}">
                                             <input type="hidden" name="is_active" value="0">
                                                    <div class="form-group ">
                                                            
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
<script src="http://malsup.github.com/jquery.form.js"></script>

    <script>

        @if(session()->has('success'))
        setTimeout(function () {
            //showMessage('{{ session()->get('success') }}');
            showMessage('{{ session('success') }}');
        }, 3000);
        @endif


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
                        // $tr.fadeOut(1000, function () {
                        //     $tr.remove();
                        // });

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
                        

                        // $tr.find('td').fadeOut(1000, function () {
                        //         $tr.remove();
                        //     });
                        location.reload();


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