<?php $__env->startSection('title','تفاصيل الطلب'); ?>
<?php $__env->startSection('content'); ?>

    <!-- Page-Title -->

    <div class="row">
        <div class="col-xs-12">
            <div class="bg-picture card-box">
                <div class="row">
                    <div class="col-xs-6 col-md-4 col-sm-4">
                        <h3 class="page-title">بيانات الطلب</h3>
                    </div>
            
                    <div class="m-t-15 col-xs-6 col-md-8 col-sm-8 text-right">
                        <button type="button" class="btn btn-custom  waves-effect waves-light" onclick="window.history.back();return false;"> رجوع <span class="m-l-5"><i class="fa fa-reply"></i></span>
                        </button>
                    </div>
                   
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-detail">
                    
                    <?php if($order != null): ?>
                        <div class="panel-body">

                            <div class="col-lg-3 col-xs-12">
                                <label>رقم الطلب :</label>
                                <p><?php echo e($order->id); ?></p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>اسم المستخدم :</label>
                                <p><?php echo e($order->user_name); ?></p>
                            </div>

                            <div class="col-lg-3 col-xs-12">
                                <label>مكان التسليم :</label>
                                <p><?php echo e($order->user_address); ?></p>
                            </div>
                         
       
                            <div class="col-lg-3 col-xs-12">
                                <label>تاريخ الطلب :</label>
                                <p> <?php echo e($order->created_at); ?></p>
                            </div>

                            
                            <div class="col-lg-3 col-xs-12">
                            <label>حالة الطلب :</label>
                            <?php if($order->status ==1 ): ?>
                                <p>مقبول</p>
                            <?php elseif($order->status ==3 ): ?>
                                <p>تجديد</p>
                            <?php elseif($order->status == 2): ?>
                                <p>مرفوض</p>
                                <label>سبب الرفض :</label>
                                <p><?php echo e($order->refuse_reason); ?></p>
                            <?php elseif($order->status == 0): ?>
                                <p>جديد</p>
                            <?php endif; ?>
                        </div>
                            
                            <!-- <div class="col-lg-6 col-xs-12">
                                <label>عنوان التسليم</label>
                                <p>
                                    <iframe width="100%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q=<?php echo e($order->lat); ?>,<?php echo e($order->lng); ?>&z=10&output=embed"></iframe>
                                </p>
                            </div> -->

                            <div class="col-lg-12 col-xs-12">
                                <label>اتفاصيل الطلبية :</label>
                                <?php if($order->items): ?>
                                <table class="table table-striped table-hover table-condensed"
                                    style="width:100%">
                                    <tr style="border: 1px solid #797979;">
                                        <th >تصنيف المنتج</th>
                                        <th>اسم المنتج</th>
                                        <th>النوع</th>
                                        <th>عدد الوحدات</th>
                                        <th>سعر الوحدة</th>
                                        <th>الاجمالى</th>
                                    </tr>
                                    <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                       
                                        <tr>
                                            <th><?php echo e(category($item->category_id)); ?></th>
                                            <th><?php echo e($item->product_name); ?></th>
                                            <th><?php echo e($item->type == 'offer' ? 'عرض' : 'بدون عرض'); ?></th>
                                            <th><?php echo e($item->amount); ?></th>
                                            <th><?php echo e($item->type == 'product' ? $item->product_price : $item->offer_price); ?></th>

                                            <th><?php echo e($item->type == 'product' ? $item->product_price* $item->amount : $item->offer_price * $item->amount); ?></th>
                                        </tr>
                                        
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <p>لا توجد</p>
                                    <?php endif; ?>
                                    <tr style="background: #797979; ">
                                        <th colspan="6"></th>
                                    </tr>
                                    <tr style="border: 1px solid #797979;">
                                        <th>تكلفة الطلب الاجمالية</th>
                                        <th>تكلفة التوصيل</th>
                                        <th>الخصم</th>
                                        <th colspan="3">تكلفة الطلب بعد الخصم</th>
                                    </tr>
                                    <tr>
                                        <th><?php echo e($order->total_price); ?></th>
                                        <th><?php echo e(setting()->getBody('delivery')); ?></th>
                                        <th><?php echo e($order->discount); ?></th>
                                        <th colspan="3"><?php echo e($order->total_price + setting()->getBody('delivery') - $order->discount); ?></th>
                                    </tr>
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