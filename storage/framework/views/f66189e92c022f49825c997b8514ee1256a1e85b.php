<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- App Favicon -->
    <link rel="shortcut icon" href="<?php echo e(request()->root()); ?>/admin/assets/images/favicon.ico">

    <!-- App title -->
    <title>لوحة تحكم <?php echo e(config('app.name')); ?> - تسجيل الدخول</title>

    <!-- App CSS -->
    <link href="<?php echo e(request()->root()); ?>/assets/admin/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(request()->root()); ?>/assets/admin/css/core.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(request()->root()); ?>/assets/admin/css/components.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(request()->root()); ?>/assets/admin/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(request()->root()); ?>/assets/admin/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(request()->root()); ?>/assets/admin/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(request()->root()); ?>/assets/admin/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="assets/js/modernizr.min.js"></script>
</head>
<body>
<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page" style="margin:  3% auto">
    <div class="text-center">
        <a href="<?php echo e(route('admin.login')); ?>" class="logo" style="font-family: JF-Flat-Regular;"><span><?php echo e(config('app.name')); ?></span></a>
    </div>
    <?php echo $__env->yieldContent('content'); ?>
</div>


<!-- end wrapper page -->



<script>

<?php if(session()->has('success')): ?>
    setTimeout(function () {
        showMessage('<?php echo e(session()->get('success')); ?>' , 'success');
    }, 3000);

    <?php endif; ?>

    <?php if(session()->has('error')): ?>
    setTimeout(function () {
        showMessage('<?php echo e(session()->get('error')); ?>' , 'error');
    }, 3000);

    <?php endif; ?>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/jquery.min.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/bootstrap-rtl.min.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/detect.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/fastclick.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/jquery.slimscroll.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/jquery.blockUI.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/waves.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/wow.min.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/jquery.nicescroll.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/jquery.scrollTo.min.js"></script>

<!-- App js -->
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/jquery.core.js"></script>
<script src="<?php echo e(request()->root()); ?>/assets/admin/js/jquery.app.js"></script>
<!-- Validation js (Parsleyjs) -->
<script type="text/javascript"
        src="<?php echo e(request()->root()); ?>/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('form').parsley();
    });
</script>

</body>
</html>