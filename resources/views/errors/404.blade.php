<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- App Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App title -->
    <title>{{ config('app.name') }}</title>

    <!-- App CSS -->
    <link href="{{ request()->root() }}/assets/admin/css/bootstrap-rtl.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{ request()->root() }}/assets/admin/css/core.css" rel="stylesheet" type="text/css"/>
    <link href="{{ request()->root() }}/assets/admin/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="{{ request()->root() }}/assets/admin/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="{{ request()->root() }}/assets/admin/css/pages.css" rel="stylesheet" type="text/css"/>
    <link href="{{ request()->root() }}/assets/admin/css/menu.css" rel="stylesheet" type="text/css"/>
    <link href="{{ request()->root() }}/assets/admin/css/responsive.css" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="{{ request()->root() }}/assets/admin/js/modernizr.min.js"></script>

</head>
<body>

<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
    <div class="ex-page-content text-center">
        <div class="text-error">404</div>
        <h3 class="text-uppercase font-600">غير موجود</h3>
        <p class="text-muted">
            هذا المستخدم غير موجود او ربما تم حذفه من النظام
        </p>
        <br>
        <a class="btn btn-success waves-effect waves-light" onclick="window.history.back(); return false;">

            رجوع
        </a>

    </div>
</div>
<!-- End wrapper page -->


<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="{{ request()->root() }}/assets/admin/js/jquery.min.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/bootstrap-rtl.min.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/detect.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/fastclick.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/jquery.slimscroll.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/jquery.blockUI.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/waves.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/wow.min.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/jquery.nicescroll.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/jquery.scrollTo.min.js"></script>


<script src="{{ request()->root() }}/assets/admin/assets/js/jquery.core.js"></script>
<script src="{{ request()->root() }}/assets/admin/assets/js/jquery.app.js"></script>

</body>
</html>