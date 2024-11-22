<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-layout="vertical" data-topbar="light" data-sidebar="dark"
      data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $__env->yieldContent('title'); ?> | Velzon - Admin & Dashboard Template</title>
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo e(URL::asset('build/images/favicon.ico')); ?>">
        <?php echo $__env->make('layouts.head-css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <style>
            #button{
                display:block;
                margin:20px auto;
                padding:10px 30px;
                background-color:#eee;
                border:solid #ccc 1px;
                cursor: pointer;


            }
            #overlay{	
                position: fixed;
                top: 0;
                z-index: 100;
                width: 100%;
                height:100%;
                display: none;
                background: rgba(0,0,0,0.6);


            }
            .cv-spinner {
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;


            }
            .spinner {
                width: 40px;
                height: 40px;
                border: 4px #ddd solid;
                border-top: 4px #2e93e6 solid;
                border-radius: 50%;
                animation: sp-anime 0.8s infinite linear;


            }
            @keyframes sp-anime {
                100% {
                    transform: rotate(360deg);


                }


            }
            .is-hide{
                display:none;


            }
        </style>
    </head>

    <?php $__env->startSection('body'); ?>
        <div id="overlay">
            <div class="cv-spinner">
                <span class="spinner"></span>
            </div>
        </div>
        <?php echo $__env->make('layouts.body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldSection(); ?>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo $__env->make('layouts.topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <?php echo $__env->make('layouts.customizer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- JAVASCRIPT -->
    <?php echo $__env->make('layouts.vendor-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
<script>
function backbtn() {

window.history.back();
}
    $('button.previestab').on('click', function(e){
    e.preventDefault();
    window.history.back();
});
</script>
</html>
<?php /**PATH D:\xampp\htdocs\velzon-laravel-10-main\resources\views/layouts/master.blade.php ENDPATH**/ ?>