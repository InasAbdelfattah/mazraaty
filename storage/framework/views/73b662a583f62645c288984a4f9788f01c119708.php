<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('administrator.settings.store')); ?>" data-parsley-validate="" novalidate="" method="post"
          enctype="multipart/form-data">

    <?php echo e(csrf_field()); ?>

    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">بيانات التواصل</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">

                    <div id="errorsHere"></div>

                    <h4 class="header-title m-t-0 m-b-30">روابط التواصل</h4>
                    <br/> <br>
                    <!-- hot numbers -->
                    <div class="form-group" id="hotNo">
                        <label>الارقام الموحدة</label><br/><br/>
                        <?php $i = 1 ; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $j= $i++; ?>
                        <div id="row<?php echo e($row->id); ?>">
                            <div class="row">
                                <div class="col-lg-1"><?php echo e($j); ?> - </div>
                                <div class="col-lg-8"><input type="text" name="<?php echo e($row->name); ?>"
                                       value="<?php echo e($row->body); ?>" class="form-control" required placeholder="الرقم الموحد ..."/></div>
                                <div class="col-lg-3 removeElement" data-id="<?php echo e($row->id); ?>" data-type="model"><i class="fa fa-remove"></i></div>
                            </div>
                            <br/>
                        </div>
                        
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div id="row1">
                            <div class="row">
                                <div class="col-lg-2">1</div>
                                <div class="col-lg-8"><input type="text" name="hot_no1"
                                       value="<?php echo e($row->body); ?>" class="form-control" required placeholder="الرقم الموحد ..."/></div>
                                <div class="col-lg-2 removeElement" data-id="0" data-type="flight"><i class="fa fa-remove"></i></div>
                            </div>
                            <br/>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if(count($settings) > 0): ?>
                        <div class="form-group text-right m-b-0 ">
                            <button id="mydiv" data-myval="<?php echo e(count($settings)); ?>" class="btn btn-primary waves-effect waves-light m-l-5 m-t-20">
                                إضافة رقم</button>
                        </div>
                    <?php else: ?>
                        <div class="form-group text-right m-b-0 ">
                        <button id="mydiv" data-myval="0" class="btn btn-primary waves-effect waves-light m-l-5 m-t-20">
                            إضافة رقم</button>
                    </div>
                    <?php endif; ?>

                    <!-- workdays -->
                    <div class="form-group" id="workDays">
                        <label>مواعيد العمل</label><br/><br/>
                        <?php $i = 1 ; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $workdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $j= $i++; ?>
                            <div id="work<?php echo e($row->id); ?>">
                                <div class="row">
                                    <div class="col-lg-1"> <?php echo e($j); ?> - </div>
                                    <div class="col-lg-3">
                                        <select class="form-control" name="workday[<?php echo e($j); ?>][day]">
                                            <option value="Sat" <?php echo e($row->day == 'Sat' ? 'selected' : ''); ?>>السبت</option>
                                            <option value="Sun" <?php echo e($row->day == 'Sun' ? 'selected' : ''); ?>>الأحد</option>
                                            <option value="Mon" <?php echo e($row->day == 'Mon' ? 'selected' : ''); ?>>الإثنين</option>
                                            <option value="Tue" <?php echo e($row->day == 'Tue' ? 'selected' : ''); ?>>الثلاثاء</option>
                                            <option value="Wed" <?php echo e($row->day == 'Wed' ? 'selected' : ''); ?>>الأربعاء</option>
                                            <option value="Thu" <?php echo e($row->day == 'Thu' ? 'selected' : ''); ?>>الخميس</option>
                                            <option value="Fri" <?php echo e($row->day == 'Fri' ? 'selected' : ''); ?>>الجمعة</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3"><input type="time" name="workday[<?php echo e($j); ?>][from]" value="<?php echo e($row->from); ?>" class="form-control"></div>
                                    <div class="col-lg-3"><input type="time" name="workday[<?php echo e($j); ?>][to]" value="<?php echo e($row->to); ?>" class="form-control"></div>
                                    <div class="col-lg-2 removeWorkDay" data-id="<?php echo e($row->id); ?>" data-type="model"><i class="fa fa-remove"></i></div>
                                </div>
                                <br>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div id="work1">
                                <div class="row">
                                    <div class="col-lg-1"> 1 - </div>
                                    <div class="col-lg-3">
                                        <select class="form-control" name="workday[0][day]">
                                            <option value="Sat">السبت</option>
                                            <option value="Sun">الأحد</option>
                                            <option value="Mon">الإثنين</option>
                                            <option value="Tue">الثلاثاء</option>
                                            <option value="Wed">الأربعاء</option>
                                            <option value="Thu">الخميس</option>
                                            <option value="Fri">الجمعة</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3"><input type="time" id="from" name="workday[0][from]" class="form-control" placeholder="من"></div>
                                    <div class="col-lg-3"><input type="time" name="workday[0][to]" class="form-control" placeholder="إلى"></div>
                                    <div class="col-lg-2 removeWorkDay" data-id="0" data-type="flight"><i class="fa fa-remove"></i></div>
                                </div>
                                <br>
                            </div>
                        <?php endif; ?>
                    </div>
                        
                    <?php if(count($workdays) > 0): ?>
                        <div class="form-group text-right m-b-0 ">
                            <button id="mywork" data-myval="<?php echo e(count($workdays)); ?>" class="btn btn-primary waves-effect waves-light m-l-5 m-t-20">
                                إضافة ميعاد عمل</button>
                        </div>
                    <?php else: ?>
                        <div class="form-group text-right m-b-0 ">
                            <button id="mywork" data-myval="0" class="btn btn-primary waves-effect waves-light m-l-5 m-t-20">
                                إضافة ميعاد عمل</button>
                        </div>
                    <?php endif; ?>

                    <!-- end of workdays -->
                        <!-- <div class="form-group<?php echo e($errors->has('start_at') ? ' has-error' : ''); ?><?php echo e($errors->has('end_at') ? ' has-error' : ''); ?>">
                            <label class="control-label">تاريخ البداية والنهاية</label>

                            <div class="input-daterange input-group" id="date-range">
                                <input type="text" class="form-control" name="start_at" value="<?php echo e(old('start_at')); ?>"/>
                                <span class="input-group-addon bg-primary b-0 text-white">إلي</span>
                                <input type="text" class="form-control" name="end_at" value="<?php echo e(old('end_at')); ?>"/>
                            </div>
                            <?php if($errors->has('start_at')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('start_at')); ?>

                                </p>
                            <?php endif; ?>
                            
                            <?php if($errors->has('end_at')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('end_at')); ?>

                                </p>
                            <?php endif; ?>

                        </div> -->
                    <!-- gg -->

                    <div class="form-group text-right m-t-20">
                        <button class="btn btn-primary waves-effect waves-light m-t-20" type="submit">
                            حفظ البيانات
                        </button>
                        <button onclick="window.history.back();return false;" type="reset"
                                class="btn btn-default waves-effect waves-light m-l-5 m-t-20">
                            إلغاء
                        </button>
                    </div>

                </div>
            </div><!-- end col -->

        </div>
        <!-- end row -->
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>

    // jQuery('#date-range').datepicker({
    //     toggleActive: true
    // });

    // $('#from').datetimepicker({
    //     inline:true,
    // });

    // $('#from').datetimepicker('show');

    //$( '#from' ).datetimepicker( { format: 'MM-DD-YYYY' } );

    $('#mydiv').on('click', function (e) {
        e.preventDefault();
        var a = $('#mydiv').data('myval');
        console.log('a:',a);
        var v = a + 1;
        console.log('v:',v)
        $('#mydiv').data('myval', v);

        $('#hotNo').append('<div id="row'+v+'"><div class="row" data-id="row' + v + '"><div class="col-lg-1"> '+(v)+' - </div><div class="col-lg-8"><input type="text" name="hot_no' + v + '" class="form-control"></div><div class="col-lg-3 removeElement" data-id="'+ v + '" data-type="flight"><i class="fa fa-remove"></i></div></div><br/></div>');
    });

    $('#mywork').on('click', function (e) {
        e.preventDefault();
        var a = $('#mywork').data('myval');
        var v = a + 1;
        $('#mywork').data('myval', a + 1);

        $('#workDays').append('<div id="work'+v+'" ><div class="row" data-id="row' + v + '"><div class="col-lg-1"> '+(v)+' - </div><div class="col-lg-3"><select class="form-control" name="workday[' + v + '][day]"><option value="Sat">السبت</option><option value="Sun">الأحد</option><option value="Mon">الإثنين</option><option value="Tue">الثلاثاء</option><option value="Wed">الأربعاء</option><option value="Thu">الخميس</option><option value="Fri">الجمعة</option></select></div><div class="col-lg-3"><input type="time" name="workday[' + v + '][from]" class="form-control"></div><div class="col-lg-3"><input type="time" name="workday[' + v + '][to]" class="form-control"></div><div class="col-lg-2 removeWorkDay" data-id="'+ v + '" data-type="flight"><i class="fa fa-remove"></i></div></div><br/></div>');
    });

    $('body').on('click', '.removeElement', function () {
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
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
                    if(type == 'model'){
                        $.ajax({
                            type: 'get',
                            url: '<?php echo e(route('settings.delete')); ?>',
                            data: {modelId: id},
                            dataType: 'json',
                            success: function (data) {
                                if (data) {
                                    console.log('model_idd',id);
                                    console.log(data);
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

                                    $("#row"+id).fadeOut(1000, function () {
                                        $("#row"+id).remove();
                                    });
                                }

                                // $tr.find('td').fadeOut(1000, function () {
                                //     $tr.remove();
                                // });
                            }
                        });
                    }else{
                        $("#row"+id).fadeOut(1000, function () {
                            $("#row"+id).remove();
                        });
                    }   
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

    $('body').on('click', '.removeWorkDay', function () {
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
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
                    if(type == 'model'){
                        $.ajax({
                            type: 'get',
                            url: '<?php echo e(route('settings.destroyWorkDay')); ?>',
                            data: {modelId: id},
                            dataType: 'json',
                            success: function (data) {
                                if (data) {
                                    console.log('model_idd',id);
                                    console.log(data);
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

                                    $("#work"+id).fadeOut(1000, function () {
                                        $("#work"+id).remove();
                                    });
                                }

                                // $tr.find('td').fadeOut(1000, function () {
                                //     $tr.remove();
                                // });
                            }
                        });
                    }else{
                        $("#work"+id).fadeOut(1000, function () {
                            $("#work"+id).remove();
                        });
                    }   
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>