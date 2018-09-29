<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('users.store')); ?>" enctype="multipart/form-data" data-parsley-validate
          novalidate>
    <?php echo e(csrf_field()); ?>


    <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <!-- <div class="btn-group pull-right m-t-15">
                    <button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light"
                            data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i
                                    class="fa fa-cog"></i></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                </div> -->
                <h4 class="page-title">إضافة مدير</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card-box">


                    <!-- <div id="errorsHere"></div>
                    <div class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                            <i class="zmdi zmdi-more-vert"></i>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div> -->

                    <h4 class="header-title m-t-0 m-b-30">بيانات المستخدم</h4>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="userName">اسم المستخدم*</label>
                            <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control" required
                                   placeholder="اسم المستخدم..."/>
                            <p class="help-block" id="error_userName"></p>
                            <?php if($errors->has('name')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('name')); ?>

                                </p>
                            <?php endif; ?>
                        </div>

                    </div>

                    <!-- <div class="col-xs-6">
                        <div class="form-group<?php echo e($errors->has('username') ? ' has-error' : ''); ?>">
                            <label for="usernames">اسم المستخدم*</label>
                            <input type="text" name="username" value="<?php echo e(old('username')); ?>" class="form-control"
                                   required placeholder="اسم المستخدم..."/>
                            <?php if($errors->has('username')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('username')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                    </div> -->


                    <div class="col-xs-6">
                        <div class="form-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
                            <label for="userPhone">رقم الجوال*</label>
                            <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control" required
                                   placeholder="رقم الجوال..."/>
                            <?php if($errors->has('phone')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('phone')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="col-xs-6">
                        <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                            <label for="emailAddress">البريد الإلكتروني*</label>

                            <input type="email" name="email" parsley-trigger="change" value="<?php echo e(old('email')); ?>"
                                   class="form-control"
                                   placeholder="البريد الإلكتروني..." required/>
                            <?php if($errors->has('email')): ?>
                                <p class="help-block"><?php echo e($errors->first('email')); ?></p>
                            <?php endif; ?>

                        </div>

                    </div>

                    <div class="col-xs-6">
                        <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                            <label for="pass1">كلمة المرور*</label>


                            <input type="password" name="password" id="pass1" value="<?php echo e(old('password')); ?>"
                                   class="form-control"
                                   placeholder="كلمة المرور..."
                                   required/>

                            <?php if($errors->has('password')): ?>
                                <p class="help-block"><?php echo e($errors->first('password')); ?></p>
                            <?php endif; ?>

                        </div>
                    </div>


                    <div class="col-xs-6">
                        <div class="form-group<?php echo e($errors->has('password_confirmation') ? ' has-error' : ''); ?>">
                            <label for="passWord2">تأكيد كلمة المرور*</label>
                            <input data-parsley-equalto="#pass1" name="password_confirmation" type="password" required
                                   placeholder="تأكيد كلمة المرور..." class="form-control" id="passWord2">
                            <?php if($errors->has('password_confirmation')): ?>
                                <p class="help-block"><?php echo e($errors->first('password_confirmation')); ?></p>
                            <?php endif; ?>


                        </div>
                    </div>


                    <!-- <div class="form-group">
                        <label for="passWord2">العنوان*</label>
                        <input name="address" value="<?php echo e(old('address')); ?>" type="text" required placeholder="العنوان..."
                               class="form-control">

                    </div> -->

                    <div class="form-group<?php echo e($errors->has('roles') ? ' has-error' : ''); ?>">
                        <label for="passWord2">الصلاحيات *</label>
                        <select multiple="multiple" class="multi-select" id="my_multi_select1" name="roles[]"
                                data-plugin="multiselect">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($value->name); ?>" <?php echo e((collect(old('roles'))->contains($value->name)) ? 'selected':''); ?>><?php echo e($value->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                        <?php if($errors->has('roles')): ?>
                            <p class="help-block"> <?php echo e($errors->first('roles')); ?></p>
                        <?php endif; ?>

                    </div>

                    <!-- <div class="form-group">
                        <label for="pass1">الحالة *</label>
                        <select class="form-control select2" name="is_active">
                            <option value="1">مفعل</option>
                            <option value="0">غير مفعل</option>
                        </select>
                    </div> -->

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

            <div class="col-lg-4">
                <div class="card-box" style="overflow: hidden;">
                    <h4 class="header-title m-t-0 m-b-30">الصورة الشخصية</h4>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="file" name="image" class="dropify" data-max-file-size="6M"/>

                        </div>
                    </div>

                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>