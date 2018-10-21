<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('administrator.settings.store')); ?>" data-parsley-validate="" novalidate="" method="post"
          enctype="multipart/form-data">

    <?php echo e(csrf_field()); ?>


    <!-- Page-Title -->

        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">نسبة توصيل الطلب</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card-box">


                    <div id="errorsHere"></div>
                    <h4 class="header-title m-t-0 m-b-30">نسبة توصيل الطلب</h4>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="userName">نسبة توصيل الطلب *</label>
                            <input type="number" name="delivery"
                                   value="<?php echo e(setting()->getBody('delivery')); ?>" class="form-control"
                                   required
                                   placeholder = "2 ryal"/> ريال
                            <p class="help-block"></p>

                        </div>

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

<?php echo $__env->make('admin.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>