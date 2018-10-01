<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('discounts.store')); ?>" enctype="multipart/form-data" data-parsley-validate
          novalidate>
    <?php echo e(csrf_field()); ?>


    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                
                <h4 class="page-title">إضافة كود خصم</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                   
                    <h4 class="header-title m-t-0 m-b-30">بيانات كود الخصم</h4>
                    
                    <div class="form-group<?php echo e($errors->has('times') ? ' has-error' : ''); ?>">
                        <label for="userName"> عدد مرات استخدام كود الخصم*</label>
                        <input class="form-control" type="text" name="times" value="<?php echo e(old('times')); ?>" placeholder="عدد مرات استخدام كود الخصم" data-parsley-required-message="هذا الحقل الزامى">
                        <p class="help-block" id="error_userName"></p>
                        <?php if($errors->has('times')): ?>
                            <p class="help-block">
                                <?php echo e($errors->first('times')); ?>

                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group<?php echo e($errors->has('start_from') ? ' has-error' : ''); ?>">
                        <label for="userName"> تاريخ وقت بداية الخصم*</label>
                        <input class="form-control" type="date" name="start_from" value="<?php echo e(old('start_from')); ?>" placeholder="تاريخ وقت بداية الخصم" data-parsley-required-message="هذا الحقل الزامى">
                        <p class="help-block" id="error_userName"></p>
                        <?php if($errors->has('start_from')): ?>
                            <p class="help-block">
                                <?php echo e($errors->first('start_from')); ?>

                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group<?php echo e($errors->has('end_at') ? ' has-error' : ''); ?>">
                        <label for="userName"> تاريخ وقت انتهاء الخصم*</label>
                        <input class="form-control" type="date" name="end_at" value="<?php echo e(old('end_at')); ?>" placeholder="تاريخ وقت انتهاء الخصم" data-parsley-required-message="هذا الحقل الزامى">
                        <p class="help-block" id="error_userName"></p>
                        <?php if($errors->has('end_at')): ?>
                            <p class="help-block">
                                <?php echo e($errors->first('end_at')); ?>

                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group<?php echo e($errors->has('code') ? ' has-error' : ''); ?>">
                        <label for="userName"> كود الخصم*</label>
                        <input class="form-control" type="text" name="code" value="<?php echo e(old('code')); ?>" placeholder="ادخل كود الخصم" data-parsley-required-message="هذا الحقل الزامى">
                        <p class="help-block" id="error_userName"></p>
                        <?php if($errors->has('code')): ?>
                            <p class="help-block">
                                <?php echo e($errors->first('code')); ?>

                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group<?php echo e($errors->has('ratio') ? ' has-error' : ''); ?>">
                        <label for="userName"> نسبة الخصم*</label>
                        <input class="form-control" type="text" name="ratio" value="<?php echo e(old('ratio')); ?>" placeholder="ادخل نسبة الخصم" data-parsley-required-message="هذا الحقل الزامى">
                        <p class="help-block" id="error_userName"></p>
                        <?php if($errors->has('ratio')): ?>
                            <p class="help-block">
                                <?php echo e($errors->first('ratio')); ?>

                            </p>
                        <?php endif; ?>
                    </div>
                    
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

                $("#sub_cat").append('<option value="' + val.id + '">' + val.name + '</option>');

            });

        });
      
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>