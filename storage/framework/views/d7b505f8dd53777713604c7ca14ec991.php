<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
    <head>
        <title>Login | Mendoza Cafe</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="CQA Review Center">
        <meta name="author" content="Infinit Soloutions">
        <link rel="shortcut icon" href="<?php echo e(asset('images/logos/logo.jpg')); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo e(asset('login/css/style.css')); ?>">
    </head>
    <body>
        <?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('layouts.partials.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('layouts.partials.onclick', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-10">
                        <div class="wrap d-md-flex">
                            <div class="img" style="background-image: url(<?php echo e(asset('images/logos/logo_black.jpg')); ?>);">
                            </div>
                            <div class="login-wrap p-4 p-md-4">
                                <div class="d-flex justify-content-center">
                                    <img src="<?php echo e(asset('images/logos/logo.jpg')); ?>" class="img-circle elevation-2" style="width:80px;height:80px;" alt="">
                                </div>
                                <div class="d-flex justify-content-center">
                                    <h5 class="text-center" style="color:gray;">Mendoza Cafe</h5>
                                </div>
                                <p><small>PLEASE LOGIN YOUR ACCOUNT</small></p>
                                <?php if(session('errorMessage')): ?>
                                    <p style="color:red;"><small><?php echo e(session('errorMessage')); ?></small></p>
                                <?php endif; ?>
                               
                                <form method="POST" action="<?php echo e(action('App\Http\Controllers\LoginController@validate_user')); ?>">
                                <?php echo e(csrf_field()); ?>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="email">E-Mail</label>
                                            <input class="form-control" type="email" name="email" />
                                        </div>
                                        <div class="col-md-12 password">Password</label>
                                            <input class="form-control" type="password" name="password" />
                                        </div>
                                        <div class="col-md-12 col-sm-12 mb-2 mt-2">
                                            <button class="btn btn-secondary btn-block" style="background-color:#313131;color:white;" type="submit"><span class="fa fa-sign-in"></span> Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="<?php echo e(asset('login/js/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(asset('login/js/popper.js')); ?>"></script>
        <script src="<?php echo e(asset('login/js/bootstrap.min.js')); ?>"></script>
        <script src="<?php echo e(asset('login/js/main.js')); ?>"></script>
    </body>
</html>
<?php /**PATH C:\laragon\www\pest_control\resources\views/login.blade.php ENDPATH**/ ?>