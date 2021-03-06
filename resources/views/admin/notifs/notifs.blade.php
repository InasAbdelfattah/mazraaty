@extends('admin.layouts.master')
@section('title','الإشعارات')

@section('styles')
<style>
    #cities{
        display:none;
    }
</style>
@endsection

@section('content')

    <!-- Page-Title -->
    <!--<div class="row">-->
    <!--    <div class="col-sm-12">-->
            
    <!--        <h4 class="page-title">الطلبات</h4>-->
    <!--    </div>-->
    <!--</div>-->


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="dropdown pull-right">
                    <a href="{{ route('new-notif') }}" style="float: left; margin-right: 15px;" class="btn btn-primary btn-sm">ارسال اشعار</a>
                </div>

                <div class="col-sm-4 col-sm-offset-4 pull-left">
                    @if(isset($type) && $type == 'search')
                        <a href="{{ route('notifs') }}" style="float: left; margin-right: 15px;" class="btn btn-primary btn-sm"><i class="fa fa-eye" style="margin-left: 5px"></i>عرض الاشعارات
                        </a>
                    @endif
                </div>

                @if(isset($type) && $type != 'search')
                    <div class="row">
                        <form action="{{route('notifs.search')}}" method="get">
                            
                            @php 
                                $old = date('Y-m-d', strtotime('-5days'));
                                $new = date("Y-m-d"); 
                            @endphp
                            <div class="col-lg-4">
                                <label>تاريخ الإشعار</label>
                                <input type="date" name="notif_date" value="{{old
                                ('notif_date')}}" class="form-control"/>
                            </div>

                            <!-- <div class="col-lg-3">
                                <label>فئة الإشعار</label>
                                <select class="form-control" name="push_type" id="push_type" required data-parsley-required-message="هذا الحقل إلزامي">
                                    <option value="global">المستخدمين</option>
                                    <option value="cities">بناء على المدينة</option>
                                </select>
                            </div>

                            <div class="col-lg-3" id="cities">
                                <label>المدينة</label>
                                <select class="form-control" name="city" required data-parsley-required-message="هذا الحقل إلزامي">
                                    @forelse($cities as $city)
                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                    @empty
                                    <option value="">لا توجد مدن</option>
                                    @endforelse
                                </select>
                            </div> -->
                            
                            
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                            
                        </form>
                    </div>
                @endif

                <br> <br>
                <h4 class="header-title m-t-0 m-b-30">الاشعارات</h4>

                <table id="datatable-fixed-header" class="table table-striped table-hover table-condensed"
                       style="width:100%">
                    <thead>
                    <tr>
                        <th>م</th>
                        <!-- <th>عنوان الاشعار</th> -->
                        <th>تاريخ الاشعار</th>
                        <!--<th>صورة الاشعار</th>-->
                        <th>فئة الاشعار</th>
                        <!-- <th>رقم جوال المستخدم</th> -->
                        <th>العمليات المتاحة</th>

                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 1; @endphp
                    @if(count($notifs) > 0)
                    @foreach($notifs as $row)
                        <tr>
                            <td>
                                {{$i++}}
                                
                            </td>
                            
                            <!-- <td>{{ $row->title }}</td> -->
                            <td>{{$row->created_at}}</td>
                            
                            
                            <td> {{ $row->push_type =='global' ? 'المستخدمين' : 'بناء على مدينة'}} </td>                           
                            <!-- <td>
                                @if($row->notif_type =='single')
                                @php 
                                    $user = \App\User::find($row->to_user)
                                @endphp
                                    {{$user ? $user->phone : '--'}}
                                @else ---
                                @endif
                            </td> -->
                            <td>
                                <a href="{{ route('notifs.show', $row->id) }}"
                                   class="btn btn-icon btn-xs waves-effect btn-info m-b-5">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="javascript:;" id="elementRow{{ $row->id }}" data-id="{{ $row->id }}" data-url="{{route('notifs.delete',$row->id)}}"
                                   class="removeElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                    <i class="fa fa-remove"></i>

                                </a>
                                
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
        $('#push_type').on('change', function(e){

            var type = e.target.value;

            if(type == 'cities'){
                $("#cities").show();
            }else{
                $("#cities").hide();
            }

        });

        $('body').on('click', '.removeElement', function () {
            var id = $(this).attr('data-id');
            var url = $(this).attr('data-url');
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
                        type: 'post',
                        url: url ,
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {
                            if (data) {
                                console.log('id',data.data);
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

                            // $tr.find('td').fadeOut(1000, function () {
                            //     $tr.remove();
                            // });
                            location.reload();

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
