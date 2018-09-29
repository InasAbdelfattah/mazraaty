<?php $__env->startSection('title','الأقسام الرئيسية'); ?>

<?php $__env->startSection('content'); ?>

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            
            <h4 class="page-title">الأقسام الرئيسية</h4>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <div class="dropdown pull-right">
                   <a href="<?php echo e(route('categories.create','type=cats')); ?>" class="btn btn-custom  waves-effect waves-light">
                    <span class="m-l-5">
                        <i class="fa fa-plus"></i> <span>إضافة</span> </span>
                </a>
                </div>

                <div class="col-sm-4 col-sm-offset-4 pull-left">
                    <?php if(isset($type) && $type == 'search'): ?>
                        <a href="<?php echo e(route('categories.index')); ?>" style="float: left; margin-right: 15px;" class="btn btn-primary btn-sm"><i class="fa fa-eye" style="margin-left: 5px"></i>مشاهدة الأقسام
                        </a>
                    <?php endif; ?>
                </div>

                <?php if(isset($type) && $type != 'search'): ?>
                    <div class="row">
                        <form action="<?php echo e(route('categories.search')); ?>" method="get">
                            <input type="hidden" name="parent_id" value="0">
                            
                            <div class="col-lg-4"> 
                                <label>اسم القسم</label>
                                <select name="id" class="form-control">
                                    <option value="" disabled selected>القسم ...</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($value->id); ?>"><?php echo e($value->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <option value="" disabled>لا توجد أقسام رئيسية</option>
                                    <?php endif; ?>
                                </select>
                                
                            </div>

                            <div class="col-lg-4">
                                <label>حالة القسم</label>
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
                <?php endif; ?>

                <br> <br>
                
                <h4 class="header-title m-t-0 m-b-30">مشاهدة الأقسام الرئيسية</h4>

                <table class="table m-0  table-striped table-hover table-condensed" id="datatable-fixed-header">
                    <thead>
                    <tr>
                        <th>
                            م
                        </th>
                        <th>اسم القسم الرئيسى</th>
                        <th>صورة القسم الرئيسى</th>
                        <th>حالة القسم الرئيسى</th>
                        <th>العمليات المتاحة</th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php $i = 1; ?>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($i++); ?>


                                <!--<div class="checkbox checkbox-primary checkbox-single">-->
                                <!--    <input type="checkbox" class="checkboxes-items"-->
                                <!--           value="<?php echo e($category->id); ?>"-->
                                <!--           aria-label="Single checkbox Two">-->
                                <!--    <label></label>-->
                                <!--</div>-->

                            </td>
                            <td><?php echo e($category->name); ?></td>
                            <td style="width: 10%;">
                                <a data-fancybox="gallery"
                                   href="<?php echo e(getDefaultImage(request()->root().'/files/categories/'.$category->image, request()->root().'/assets/admin/custom/images/default.png')); ?>">
                                    <img style="width: 50%; border-radius: 50%; height: 49px;"
                                         src="<?php echo e(getDefaultImage(request()->root().'/files/categories/'.$category->image, request()->root().'/assets/admin/custom/images/default.png')); ?>"/>
                                </a>

                            </td>
                            <td><?php echo e($category->status == 1 ? 'مفعل' : 'معطل'); ?></td>
                            <td>
                                
                                <!-- <a href="<?php echo e(route('categories.show', $category->id)); ?>"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-eye"></i>
                                </a> -->

                                <a href="<?php echo e(route('categories.edit', $category->id).'?type=cats'); ?>"
                                   class="btn btn-icon btn-xs waves-effect btn-default m-b-5">
                                    <i class="fa fa-edit"></i>
                                </a>

                                
                                   
                                    

                                
                                <a href="javascript:;" id="elementRow<?php echo e($category->id); ?>" data-id="<?php echo e($category->id); ?>" data-status="<?php echo e($category->status); ?>"
                                   class="elementStatus btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                    <?php if($category->status == 1): ?>
                                        <label class="label label-danger label-xs">تعطيل</label>
                                    <?php else: ?>
                                        <label class="label label-success label-xs">تفعيل</label>
                                    <?php endif; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                    </tbody>
                </table>


                

                

                
            </div>
        </div><!-- end col -->

    </div>
    <!-- end row -->
<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>


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
                        url: '<?php echo e(route('category.activateCategory')); ?>',
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
                            url: '<?php echo e(route('categories.group.delete')); ?>',
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
                        url: '<?php echo e(route('categories.destroy','+id+')); ?>',
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

                                $tr.find('td').fadeOut(1000, function () {
                                    $tr.remove();
                                });

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


        // function showMessage(message) {
        //
        //     var shortCutFunction = 'success';
        //     var msg = message;
        //     var title = 'نجاح!';
        //     toastr.options = {
        //         positionClass: 'toast-top-center',
        //         onclick: null,
        //         showMethod: 'slideDown',
        //         hideMethod: "slideUp",
        //     };
        //     var $toast = toastr[shortCutFunction](msg, title);
        //     // Wire up an event handler to a button in the toast, if it exists
        //     $toastlast = $toast;
        //
        //
        // }
    </script>



<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>