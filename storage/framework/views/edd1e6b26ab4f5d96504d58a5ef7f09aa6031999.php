<?php $__env->startSection('title','تفاصيل المستخدم'); ?>
<?php $__env->startSection('content'); ?>

    <!-- Page-Title -->

    <div class="row">
        <div class="col-xs-12">
            <div class="bg-picture card-box">
                <div class="row">
                    <div class="col-xs-6 col-md-4 col-sm-4">
                        <h3 class="page-title">بيانات المستخدم</h3>
                    </div>
            
                    <div class="m-t-15 col-xs-6 col-md-8 col-sm-8 text-right">
                        <button type="button" class="btn btn-custom  waves-effect waves-light" onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i class="fa fa-reply"></i></span>
                        </button>
                    </div>
                   
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-detail">
                    
                    <?php if($user != null): ?>
                        <div class="panel-body">

                            <div class="col-lg-3 col-xs-12">
                                <label>اسم المستخدم :</label>
                                <p><?php echo e($user->name); ?></p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>الجوال :</label>
                                <p><?php echo e($user->phone); ?></p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>البريد الالكترونى :</label>
                                <p><?php echo e($user->email); ?></p>
                            </div>

                            <div class="col-lg-12 col-xs-12">
                                <label>أكواد الخصم :</label>
                                <?php if($user->coupons): ?>
                                <table class="table table-striped table-hover table-condensed"
                                    style="width:100%">
                                    <tr>
                                        <th>كود الخصم</th>
                                        <th>نسبة الخصم</th>
                                        <th>تاريخ بداية الخصم</th>
                                        <th>تاريخ انتهاء الخصم</th>
                                        <th>عدد مرات استخدام الخصم</th>
                                        <th>عدد مرات الاستفادة من الخصم</th>
                                    </tr>
                                    <?php $__empty_1 = true; $__currentLoopData = $user->coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                       <?php if($item->coupon): ?>
                                        <tr>
                                            <th><?php echo e($item->coupon->code); ?></th>
                                            <th><?php echo e($item->coupon->ratio); ?></th>
                                            <th><?php echo e($item->coupon->from); ?></th>
                                            <th><?php echo e($item->coupon->to); ?></th>
                                            <th><?php echo e($item->coupon->times); ?></th>
                                            <th><?php echo e($item->coupon_times); ?></th>
                                        </tr>
                                        <?php endif; ?>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <p>لا توجد</p>
                                    <?php endif; ?>
                                    
                                </table>
                                <?php endif; ?>

                            </div>

                        </div>
                    <?php endif; ?>
                    </div>
                    <!-- end card-box-->
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>