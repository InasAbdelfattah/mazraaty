<?php $__env->startSection('styles'); ?>
    <style>
        .wrapper-page {
            margin: 3% auto;
            position: relative;
            width: 420px;
        }
    </style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>




    <div class="m-t-40 card-box">
        <div class="text-center">
            <h4 class="text-uppercase font-bold m-b-0">تسجيل الدخول</h4>
        </div>
        <div class="panel-body">

            <?php if(session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong> <?php echo e(session()->get('error')); ?></strong>
                </div>
            <?php endif; ?>


            <form class="form-horizontal m-t-20" method="POST" action="<?php echo e(route('admin.login')); ?>">
                <?php echo e(csrf_field()); ?>




                <div class="form-group">
                    <div class="col-xs-12">
                        <input id="email" type="email" class="form-control" name="provider"
                               value="<?php echo e(old('provider')); ?>" required data-parsley-required-message="هذا الحقل الزامى" data-parsley-type="email" data-parsley-type-message="يرجى ادخال صيغة بريد الكترونى صحيحة" autofocus
                               placeholder="اسم المستخدم او البريد الإلكتروني...">

                        <?php if($errors->has('email')): ?>
                            <span class="help-block">
                                        <strong><?php echo e($errors->first('provider')); ?></strong>
                                    </span>
                        <?php endif; ?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-xs-12">
                        <input id="password" type="password" class="form-control" name="password" required data-parsley-required-message="هذا الحقل الزامى"
                               placeholder="كلمة المرور...">

                        <?php if($errors->has('password')): ?>
                            <span class="help-block">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group ">
                    <div class="col-xs-12">
                        <div class="checkbox checkbox-custom">

                            <input id="checkbox-signup" type="checkbox"
                                   name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                            <label for="checkbox-signup">
                                تذكرنى
                            </label>
                        </div>

                    </div>
                </div>

                <div class="form-group text-center m-t-30">
                    <div class="col-xs-12">
                        <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" type="submit">
                            دخول
                        </button>
                    </div>
                </div>

                <div class="form-group m-t-30 m-b-0">
                    <div class="col-sm-12">
                        <a href="<?php echo e(route('administrator.password.request')); ?>" class="text-muted"><i
                                    class="fa fa-lock m-r-5"></i>
                            هل نسيت كلمة المرور؟</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <!-- end card-box-->




<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.login', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>