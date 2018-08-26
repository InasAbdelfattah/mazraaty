@extends('admin.layouts.master')


@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">

            </div>
            <h4 class="page-title">تفاصيل الرسالة</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="bg-picture card-box">
                <div class="profile-info-name">
                    <div class="profile-info-detail">
                    @if($message->user)
                       

                            <a href="{{ route('users.show', $message->user->id) }}"><h3
                                        class="m-t-0 m-b-10">{{($message->user)?($message->user->name)?: $message->user->username :'' }}</h3>
                            </a>

                       
                        @endif
                        @if($message->type == 1)
                            <span class="label label-danger">شكوى</span>
                        @elseif($message->type == 2)
                            <span class="label label-success">إقتراح</span>
                        @else
                            <span class="label label-inverse">آخري</span>

                        @endif
                      
                        <p class="m-t-10">رقم جوال المستخدم: {{$user ? $user->phone : '--'}}</p>
                        
                        <p class="m-t-10">{{ $message->message }}</p>
                        <p><i class="fa fa-calendar"></i> {{ $message->created_at->format('Y/m/d  ||  H:i:s ') }}</p>

                    </div>


                    <div id="supportReplies">


                        @foreach(App\Support::orderBy('created_at', 'desc')->whereParentId($message->id)->get() as $row)
                            <div class="card bg-lightdark p-20 m-t-20">
                                {{ $row->message }}
                            </div>
                            <p class="m-t-10"><i class="fa fa-calendar"></i> {{ $row->created_at->format('Y/m/d  ||  H:i:s ') }}
                            </p>
                        @endforeach

                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
            <!--/ meta -->


        </div>
        <div class="col-md-6 col-xs-12">
            <form method="post" action="{{ route('support.reply',$message->id) }}" class="card-box">
             
                <span class="input-icon icon-right">
                    <textarea rows="5" class="form-control" name="message" required
                                placeholder="الرد على الرسالة الحاليه..."
                                data-parsley-error-message="ادخل نص الرد اولا"></textarea>
                </span>

                <select class="form-control m-t-10" name="reply_type" required
                        data-parsley-error-message="">
                    <option value="">إرسال عبر</option>
                    <option value="0">رسالة نصية (SMS)</option>
                    <!-- <option value="1">البريد الإلكتروني</option> -->
                </select>
                <div class="p-t-10">
                    <button class="btn btn-sm btn-primary waves-effect waves-light">إرسال
                        <i style="display: none;" id="spinnerDiv" class="fa fa-spinner fa-spin"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>



@endsection


@section('scripts')


    <script>


        @if(session()->has('success'))

        setTimeout(function () {
            showMessage('{{ session()->get('success') }}');
        }, 3000);


        @endif
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
                            url: '{{ route('categories.group.delete') }}',
                            data: {ids: sum},
                            dataType: 'json',
                            success: function (data) {
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

                                $('.checkboxes-items').each(function () {
                                    if ($(this).prop('checked') == true) {
                                        $(this).parent().parent().parent().delay(200).fadeOut();
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
                        url: '{{ route('category.delete') }}',
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {
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


    </script>

    <script type="text/javascript">

        $('form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);


            $('#spinnerDiv').show();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    if (data.status == true) {
                        $('#spinnerDiv').hide();
                        $("[name='message']").val('');
                        $("[name='reply_type']").val('');


                        var div = "<div class='card bg-lightdark p-20 m-t-20'>"
                            + data.data.message
                            + "</div>"
                            + "<p class='m-t-10'><i class='fa fa-calendar'></i> " + data.data.created + " </p>"
                        $('#supportReplies').prepend(div);

                        var shortCutFunction = 'success';
                        var msg = data.message;
                        var title = 'نجاح';
                        toastr.options = {
                            positionClass: 'toast-top-left',
                            onclick: null
                        };
                        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                        $toastlast = $toast;

                    }

                    if (data.status == false) {

                        var shortCutFunction = 'error';
                        var msg = data.message;
                        var title = 'نجاح';
                        toastr.options = {
                            positionClass: 'toast-top-left',
                            onclick: null
                        };
                        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
                        $toastlast = $toast;


                        setTimeout(function () {
                            $('#spinnerDiv').hide();
                        }, 1000);
                    }




                    {{--setTimeout(function () {--}}
                    {{--window.location.href = '{{ route('categories.index') }}';--}}
                    {{--}, 3000);--}}
                },
                error: function (data) {
                    $('#spinnerDiv').hide();
                }
            });
        });

    </script>

@endsection


