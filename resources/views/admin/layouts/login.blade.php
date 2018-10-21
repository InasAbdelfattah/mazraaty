<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- App Favicon -->
    <link rel="shortcut icon" href="{{ request()->root() }}/admin/assets/images/favicon.ico">

    <!-- App title -->
    <title>لوحة تحكم {{ config('app.name') }} - تسجيل الدخول</title>

    <!-- App CSS -->
    <link href="{{ request()->root() }}/assets/admin/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ request()->root() }}/assets/admin/css/core.css" rel="stylesheet" type="text/css" />
    <link href="{{ request()->root() }}/assets/admin/css/components.css" rel="stylesheet" type="text/css" />
    <link href="{{ request()->root() }}/assets/admin/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="{{ request()->root() }}/assets/admin/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="{{ request()->root() }}/assets/admin/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="{{ request()->root() }}/assets/admin/css/responsive.css" rel="stylesheet" type="text/css" />

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
        <a href="{{ route('admin.login') }}" class="logo" style="font-family: JF-Flat-Regular;"><span>{{ config('app.name') }}</span></a>
    </div>
    @yield('content')
</div>


<!-- end wrapper page -->



<script>

@if(session()->has('success'))
    setTimeout(function () {
        showMessage('{{ session()->get('success') }}' , 'success');
    }, 3000);

    @endif

    @if(session()->has('error'))
    setTimeout(function () {
        showMessage('{{ session()->get('error') }}' , 'error');
    }, 3000);

    @endif
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

<!-- App js -->
<script src="{{ request()->root() }}/assets/admin/js/jquery.core.js"></script>
<script src="{{ request()->root() }}/assets/admin/js/jquery.app.js"></script>
<!-- Validation js (Parsleyjs) -->
<script type="text/javascript"
        src="{{ request()->root() }}/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('form').parsley();
    });
</script>

</body>
</html>