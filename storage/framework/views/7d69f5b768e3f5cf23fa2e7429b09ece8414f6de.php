<?php $__env->startSection('title','ارسال اشعار'); ?>
<?php $__env->startSection('styles'); ?>
<style>
    #cities{
        display:none;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-xs-6 col-md-4 col-sm-4">
            <h3 class="page-title">إرسال إشعار</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="bg-picture card-box">
                <div class="profile-info-name">
                    <div class="profile-info-detail">
                        <h3 class="m-t-0 m-b-0">إرسال إشعار</h3>

                        <div class="panel-body">

                            <form action="<?php echo e(route('notif-send')); ?>" method="post">
                                <!-- Highlighting rows and columns -->
                                <div class="panel panel-flat">

                                    <br>
                                    <div style="width: 80%; margin: 20px auto;">

                                        <div class="form-group">
                                            <label>فئة الإشعار</label>
                                            <select class="form-control select" name="push_type" id="push_type" required data-parsley-required-message="هذا الحقل إلزامي">
                                                <option value="global">المستخدمين</option>
                                                <option value="cities">بناء على المدينة</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="cities">
                                            <label>المدينة</label>
                                            <select class="form-control select" name="city" required data-parsley-required-message="هذا الحقل إلزامي">
                                                <?php $__empty_1 = true; $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <option value="<?php echo e($city->id); ?>"><?php echo e($city->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <option value="">لا توجد مدن</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">

                                            <label>نص الإشعار</label>
                                            <textarea class="form-control description" rows="10" cols="9" name="body" placeholder="نص الرسالة"> <?php echo e(old('body')); ?> </textarea>

                                        </div>

                                        <input type="hidden" value="إرسال" name="type">

                                        <?php echo e(csrf_field()); ?>


                                        <button type="submit" style="padding: 10px 30px; margin-top: 20px;" class="btn btn-lg btn-primary">
                                            ارسال
                                        </button>

                                    </div>


                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        //$("#push_type").change(function(){
        $('#push_type').on('change', function(e){

            var type = e.target.value;
            console.log('type',type);

            if(type == 'cities'){
                $("#cities").show();
            }else{
                $("#cities").hide();
            }

        });
      
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>