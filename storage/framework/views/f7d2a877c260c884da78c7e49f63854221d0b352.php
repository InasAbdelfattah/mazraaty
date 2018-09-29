<?php $__env->startSection('title', 'تفاصيل الاشعار'); ?>
<?php $__env->startSection('content'); ?>

    
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="btn-group pull-right m-t-15">
                    <a href="<?php echo e(route('notifs')); ?>" type="button" class="btn btn-custom waves-effect waves-light"
                       aria-expanded="false"> مشاهدة الاشعارات
                        <span class="m-l-5">
                        <i class="fa fa-backward"></i>
                    </span>
                    </a>

                </div>
                <h4 class="header-title m-t-0 m-b-30">بيانات الاشعار</h4>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">تاريخ الاشعار</label>
                            <p><?php echo e($data->created_at); ?></p>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">فئة الاشعار</label>
                            <p><?php echo e($data->push_type =='global' ? 'جماعى' : 'بناء على مدينة'); ?></p>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">نص الاشعار</label>
                            <p><?php echo e($data->body); ?></p>
                        </div>
                    </div>
                </div>
              
            </div>

        </div><!-- end col -->

    </div>
    <!-- end row -->

<?php $__env->stopSection(); ?>







<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>