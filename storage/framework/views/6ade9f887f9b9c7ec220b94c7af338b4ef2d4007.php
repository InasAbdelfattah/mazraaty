<?php $__env->startSection('title', 'بيانات المستخدم'); ?>
<?php $__env->startSection('content'); ?>


    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
           
            <h4 class="page-title">بيانات المستخدم</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <h4 class="header-title m-t-0 m-b-30">بيانات المستخدم</h4>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">الاسم</label>
                            <p><?php echo e($user->name); ?></p>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userName">الهاتف</label>
                            <p><?php echo e($user->phone); ?></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="userPhone">البريد الالكترونى</label>
                            <p><?php echo e($user->email); ?></p>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <?php if($user->is_admin == 1): ?>
                            <label for="pass1">تاريخ انشاء المستخدم</label>
                            <?php else: ?>
                            <label for="pass1">تاريخ الإشتراك بالتطبيق</label>
                            <?php endif; ?>
                            <p><?php echo e($user->created_at); ?></p>

                        </div>
                    </div>
                                        
                </div>
                <div class="row">
                    <?php if($user->is_admin == 0): ?>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>عدد الطلبات التى قام بها</label>
                            <p><?php echo e($user->orders->count()); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="pass1">عدد مرات الدخول على حسابه</label>
                            <p><?php echo e($login ? $login->logins_count : 0); ?></p>

                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="pass1">اخر تاريخ وقت قام بالدخول على حسابه</label>
                            <p><?php echo e($login ? $login->updated_at : ''); ?></p>

                        </div>
                    </div>

                    
                    <?php if($user->is_admin == 1): ?>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="pass1">الصلاحيات الممنوحة اليه</label>
                                <?php if($user->roles->pluck('name')): ?> 
                                <?php $__currentLoopData = $user->roles->pluck('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 - <p><?php echo e($roleUser); ?></p>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                                <?php endif; ?>
                                

                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($user->is_admin == 0): ?>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="pass1">عناوين العميل : </label>
                                <?php $__empty_1 = true; $__currentLoopData = $user->addresses->pluck('address'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <p>- <?php echo e($address); ?></p>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p> لا توجد عناوين مسجلو للعميل</p>
                                <?php endif; ?> 
                            
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
               
            </div>

        </div><!-- end col -->

    </div>
    <!-- end row -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>