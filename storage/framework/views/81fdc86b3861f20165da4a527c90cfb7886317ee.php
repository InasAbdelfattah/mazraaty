<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('roles.update', $role->id)); ?>" enctype="multipart/form-data"
          data-parsley-validate novalidate>

    <?php echo e(csrf_field()); ?>

    <?php echo e(method_field('PUT')); ?>


    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="btn-group pull-right m-t-15">
                    <a href="<?php echo e(route('roles.index')); ?>" type="button" class="btn btn-custom waves-effect waves-light"
                       aria-expanded="false"> مشاهدة جميع الادوار
                        <span class="m-l-5">
                        <i class="fa fa-backward"></i>
                    </span>
                    </a>
                </div>
                <h4 class="page-title">إدارة الادوار</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">


                    <div id="errorsHere"></div>
                    <div class="dropdown pull-right">


                    </div>

                    <h4 class="header-title m-t-0 m-b-30">تعديل الدور</h4>


                    
                        
                            
                            
                                   
                                   
                            
                            
                                
                                    
                                
                            
                        

                    

                    <div class="col-xs-12">
                        <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                            <label for="usernames">الاسم *</label>
                            <input type="text" name="name" value="<?php echo e(isset($role->name) ? $role->name : old('name')); ?>" class="form-control"
                                   required placeholder="الاسم..."/>
                            <?php if($errors->has('name')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('name')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                    </div>


                    
                    <?php if($role->id != 1): ?>
                    <div class="form-group<?php echo e($errors->has('roles') ? ' has-error' : ''); ?>">
                        <label for="passWord2">الصلاحيات *</label>
                        <select multiple="multiple" class="multi-select" id="my_multi_select1" name="abilities[]"
                                required
                                data-plugin="multiselect">
                            <?php $__currentLoopData = $abilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ability): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ability->name); ?>"
                                        <?php if($role->abilities->pluck('name', 'name')): ?> <?php $__currentLoopData = $role->abilities->pluck('name', 'name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bilityVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($bilityVal == $ability->name): ?> selected <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
                                        
                                >

                                    <?php echo e($ability->title); ?>


                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                        <?php if($errors->has('abilities')): ?>
                            <p class="help-block"> <?php echo e($errors->first('abilities')); ?></p>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

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


<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>