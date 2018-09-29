<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <title>لوحة التحكم | <?php echo $__env->yieldContent('title'); ?></title>

    <!--Morris Chart CSS -->

    <?php echo $__env->make('admin.layouts._partials.styles', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php echo $__env->yieldContent('styles'); ?>



    <style>


        body {
            font-family: "JF-Flat-Regular" !important;
        }
        .errorValidation{
            color: red;
            font-size: 12px;
        }
        .ms-container {
            width: 100%;
            float: right;
        }

        .ms-container .ms-selectable, .ms-container .ms-selection {
            background: #fff;
            color: #555555;
            float: right;
            width: 45%;
        }

        .ms-container .ms-selection {
            float: left;
        }


    </style>

    <?php if(auth()->check()): ?>
        <script>
            var userId = '<?php echo e(auth()->id()); ?>';
            // 
            var lang = '<?php echo e(config('app.locale')); ?>';
        </script>
    <?php endif; ?>

</head>


<body class="demo scroll-hidden"
      style="font-family: 'JF-Flat-Regular' !important; font-weight: 600;">

<?php echo $__env->make('admin.layouts._partials.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<!-- validation errors message -->

<!-- <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?> -->
<!-- end validation errors message -->

<?php echo $__env->yieldContent('content'); ?>

<!-- Footer -->
<footer class="footer text-right">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                 . لوحة تحكم <?php echo e(config('app.name')); ?>

 © <?php echo e(date('Y')); ?> - <?php echo e(date('Y') + 1); ?>

            </div>
            
          
        </div>

        </div>
    </div>
</footer>
<!-- End Footer -->


<?php echo $__env->make('admin.layouts._partials.scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    function testAnim(x) {
        $('.modal .modal-dialog').attr('class', 'modal-dialog  ' + x + '  animated');
    };
    $('#myModal').on('show.bs.modal', function (e) {
        var anim = "slideOutDown";
        testAnim(anim);
    })
    $('#myModal').on('hide.bs.modal', function (e) {
        var anim = "slideOutDown";
        testAnim(anim);
    });

</script>



<script type="text/javascript">
    $(document).ready(function () {

        var table = $('#datatable-fixed-header').DataTable({
            fixedHeader: true,
            columnDefs: [{orderable: false, targets: [0]}],
            "language": {
                "lengthMenu": "عرض _MENU_ للصفحة",
                "info": "عرض صفحة _PAGE_ من _PAGES_",
                "infoEmpty": "لا توجد بيانات مسجلة متاحة ",
                "infoFiltered": "(تصفية من _MAX_ الاجمالى)",
                "paginate": {
                    "first": "الاول",
                    "last": "الاخير",
                    "next": "التالى",
                    "previous": "السابق"
                },
                "search": "البحث:",
                "zeroRecords": "لا توجد بيانات متاحة حالياً",

            },
            "order": [] ,
           // "aaSorting": []

        });
    });
    
    // $(document).ready( function() {
    //     $('#datatable-fixed-header').dataTable({
    //         /* No ordering applied by DataTables during initialisation */
    //         "order": []
    //     });
    // })


    // $('#categories').DataTable({
    //     fixedHeader: true,
    //     "columns": [
    //         {"orderable": false},
    //         null,
    //         null,
    //         null,
    //         null
    //     ],
    //     "language": {
    //         "lengthMenu": "عرض _MENU_ للصفحة",
    //         "info": "عرض صفحة _PAGE_ من _PAGES_",
    //         "infoEmpty": "لا توجد بيانات مسجلة متاحة ",
    //         "infoFiltered": "(تصفية من _MAX_ الاجمالى)",
    //         "paginate": {
    //             "first": "الاول",
    //             "last": "الاخير",
    //             "next": "التالى",
    //             "previous": "السابق"
    //         },
    //         "search": "البحث:",
    //         "zeroRecords": "لا توجد بيانات متاحة حالياً",

    //     }
    // });


</script>


<script type="text/javascript">

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

    function showMessage(message , type) {

        var shortCutFunction = type ;
        var msg = message;

        var title = type == 'success' ? 'نجاح' : 'فشل';
        toastr.options = {
            positionClass: 'toast-top-center',
            onclick: null,
            showMethod: 'slideDown',
            hideMethod: "slideUp",
        };
        var $toast = toastr[shortCutFunction](msg, title);
        // Wire up an event handler to a button in the toast, if it exists
        $toastlast = $toast;

    }

    // $(function () {
    //     $('body').on('change', '.filteriTems', function (e) {

    //             e.preventDefault();

    //             var keyName = $('#filterItems').val();
    //             var pageSize = $('#recordNumber').val();

    //             var url = $(this).attr('data-url');

    //             if (keyName != '' && pageSize != '') {
    //                 var path = '<?php echo e(request()->root().'/'.request()->path()); ?>' + '?name=' + keyName + '&pageSize=' + pageSize;
    //             } else if (keyName != '' && pageSize == '' && pageSize == 'all') {
    //                 var path = '<?php echo e(request()->root().'/'.request()->path()); ?>' + '?name=' + keyName;
    //             } else if (keyName == '' && pageSize != '') {
    //                 var path = '<?php echo e(request()->root().'/'.request()->path()); ?>' + '?pageSize=' + pageSize;
    //             } else {
    //                 var path = '<?php echo e(request()->root().'/'.request()->path()); ?>' + '?pageSize=' + pageSize;
    //             }

    //             $.ajax({
    //                 type: "POST",
    //                 url: url,
    //                 data: {keyName: keyName, path: path, pageSize: pageSize}
    //             }).done(function (data) {
    //                 window.history.pushState("", "", path);
    //                 $('.articles').html(data);
    //             }).fail(function () {
    //                 alert('Articles could not be loaded.');
    //             });


    //         }
    //     );
    // });


    function redirectPage(route) {

        window.history.pushState("", "", route);
    }

    $('.dropify').dropify({
        messages: {
            'default': 'اسحب وافلت الصورة هنا',
            'replace': 'اسحب وافلت هنا او اضغط للإستبدال',
            'remove': 'حذف',
            'error': 'لقد حدث خطأ ما, حاول مرة آخرى.'
        },
        error: {
            'fileSize': 'The file size is too big (1M max).'
        }
    });


    function checkSelect(item) {
        var checked = $(item).prop('checked');

        $('.checkboxes-items').each(function (i) {
            $(this).prop('checked', checked);
        })
    }


    $(document).ready(function () {
        $('form').parsley();
    });

    $('body').delegate('.numbersOnly', 'keyup', function (event) {


        var limit = $(this).attr('data-limit');
        var message = $(this).attr('data-message');
        var field = $(this).attr('name');

        if (!limit) {
            limit = 25;
        }

        if (this.value.length > limit) {
            $(this).css({
                'border': '1px solid red',
                'font-size': '14px',
                'color': 'red'
            });


            /****Not Work yet****/


            $('.' + field).css({
                'font-size': '12px'
            });


            $('.' + field).html(message);


        } else {
            $(this).removeAttr('style');
            $('.' + field).html('');

        }

        if (this.value.length > limit + 1) {
            event.preventDefault();
            return false;
        }


        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

</script>

<script src="https://www.gstatic.com/firebasejs/4.10.1/firebase.js"></script>
<script src="<?php echo e(request()->root()); ?>/public/assets/fcm/FCM-Setup.js"></script>

</body>
</html>