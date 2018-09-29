<?php $__env->startSection('title','الإشعارات'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    #cities{
        display:none;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

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
                    <a href="<?php echo e(route('new-notif')); ?>" style="float: left; margin-right: 15px;" class="btn btn-primary btn-sm">ارسال اشعار</a>
                </div>

                <div class="col-sm-4 col-sm-offset-4 pull-left">
                    <?php if(isset($type) && $type == 'search'): ?>
                        <a href="<?php echo e(route('notifs')); ?>" style="float: left; margin-right: 15px;" class="btn btn-primary btn-sm"><i class="fa fa-eye" style="margin-left: 5px"></i>عرض الاشعارات
                        </a>
                    <?php endif; ?>
                </div>

                <?php if(isset($type) && $type != 'search'): ?>
                    <div class="row">
                        <form action="<?php echo e(route('notifs.search')); ?>" method="get">
                            
                            <?php 
                                $old = date('Y-m-d', strtotime('-5days'));
                                $new = date("Y-m-d"); 
                            ?>
                            <div class="col-lg-4">
                                <label>تاريخ الإشعار</label>
                                <input type="date" name="notif_date" value="<?php echo e(old
                                ('notif_date')); ?>" class="form-control"/>
                            </div>

                            <div class="col-lg-3">
                                <label>فئة الإشعار</label>
                                <select class="form-control" name="push_type" id="push_type" required data-parsley-required-message="هذا الحقل إلزامي">
                                    <option value="global">المستخدمين</option>
                                    <option value="cities">بناء على المدينة</option>
                                </select>
                            </div>

                            <div class="col-lg-3" id="cities">
                                <label>المدينة</label>
                                <select class="form-control" name="city" required data-parsley-required-message="هذا الحقل إلزامي">
                                    <?php $__empty_1 = true; $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($city->id); ?>"><?php echo e($city->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <option value="">لا توجد مدن</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                            
                        </form>
                    </div>
                <?php endif; ?>

                <br> <br>
                <h4 class="header-title m-t-0 m-b-30">الاشعارات</h4>

                <table id="datatable-fixed-header" class="table table-striped table-hover"
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
                    <?php $i = 1; ?>
                    <?php if(count($notifs) > 0): ?>
                    <?php $__currentLoopData = $notifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- <?php echo e($i++); ?> -->
                        <tr>
                            <td>
                                <?php echo e($i++); ?>

                                
                            </td>
                            
                            <!-- <td><?php echo e($row->title); ?></td> -->
                            <td><?php echo e($row->created_at); ?></td>
                            
                            
                            <td> <?php echo e($row->push_type =='global' ? 'جماعى' : 'بناء على مدينة'); ?> </td>                           
                            <!-- <td>
                                <?php if($row->notif_type =='single'): ?>
                                <?php 
                                    $user = \App\User::find($row->to_user)
                                ?>
                                    <?php echo e($user ? $user->phone : '--'); ?>

                                <?php else: ?> ---
                                <?php endif; ?>
                            </td> -->
                            <td>
                                <a href="<?php echo e(route('notifs.show', $row->id)); ?>"
                                   class="btn btn-icon btn-xs waves-effect btn-info m-b-5">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="javascript:;" id="elementRow<?php echo e($row->id); ?>"
                                   data-id="<?php echo e($row->id); ?>"
                                   class="removeElement btn btn-icon btn-trans btn-xs waves-effect waves-light btn-danger m-b-5">
                                    <i class="fa fa-remove"></i>
                                </a>
                                
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>

            </div>
            </div>
        </div><!-- end col -->

   
    <!-- end row -->
<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>

    <script>
        $('#push_type').on('change', function(e){

            var type = e.target.value;

            if(type == 'cities'){
                $("#cities").show();
            }else{
                $("#cities").hide();
            }

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>