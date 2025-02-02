<!DOCTYPE html>
<html lang="en">

<head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>NLE - Login</title>

        <!-- Custom fonts for this template-->
        <link href="<?php echo base_url()?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link
                href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
                rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="<?php echo base_url()?>css/sb-admin-2.min.css" rel="stylesheet">
        <style>
                .bg-login-image {
                        background-image: url('https://www.dunextr.com/images/Dunex1.jpg')
                }
        </style>

</head>

<body class="bg-gradient-primary">

        <div class="container">

                <!-- Outer Row -->
                <div class="row justify-content-center">

                        <div class="col-xl-10 col-lg-12 col-md-9">

                                <div class="card o-hidden border-0 shadow-lg my-5">
                                        <div class="card-body p-0">
                                                <!-- Nested Row within Card Body -->
                                                <div class="row">
                                                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                                        <div class="col-lg-6">
                                                                <div class="p-5">
                                                                        <div class="text-center">
                                                                                <h1 class="h4 text-gray-900 mb-4">NLE (National Logistic Ecosystem)</h1>
                                                                        </div>

                                    <?php if(validation_errors()): ?>
                                        <div class="alert alert-danger">
                                            <?php echo validation_errors(); ?>
                                        </div>
                                    <?php endif ?>
                                    <form class="user" method="post" action="<?php echo base_url('nle/setLogin') ?>">
                                                                                                                 <div class="form-group">
                                                                                                                         <input type="email" name="email" class="form-control form-control-user"
                                                                                                                                 id="exampleInputEmail" aria-describedby="emailHelp"
                                                                                                                                 placeholder="Enter Email Address..." required="true">
                                                                                                                 </div>
                                                                                                                 <div class="form-group">
                                                                                                                         <input type="password" name="password"
                                                                                                                                 class="form-control form-control-user" id="exampleInputPassword"
                                                                                                                                 placeholder="Password" required="true">
                                                                                                                 </div>
                                                                                                                 <div class="form-group">
                                                                                                                         <div class="custom-control custom-checkbox small">
                                                                                                                                 <input type="checkbox" class="custom-control-input" id="customCheck">
                                                                                                                                 <label class="custom-control-label" for="customCheck">Remember
                                                                                                                                         Me</label>
                                                                                                                         </div>
                                                                                                                 </div>
                                                                                                                 <div id="html_element"></div>
                                                                                                                 <button type="submit" class="btn btn-primary btn-user btn-block">
                                                                                                                         Login
                                                                                                                 </button>
                                                                                                                 <!-- <hr> -->
                                                                                                                 <!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                                                                                                                         <i class="fab fa-google fa-fw"></i> Login with Google
                                                                                                                 </a>
                                                                                                                 <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                                                                                                         <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                                                                                                 </a> -->
                                                                                                         </form>
                                                                                                         <!-- <hr> -->
                                                                                                         <!-- <div class="text-center">
                                                                                                                 <a class="small" href="forgot-password.html">Forgot Password?</a>
                                                                                                         </div> -->
                                                                                                         <div class="text-center">
                                                                                                                 <a class="small" href="<?php echo base_url('nle/registration') ?>">Create an Account!</a>
                                                                                                         </div>
                                                                                                 </div>
                                                                                         </div>
                                                                                 </div>
                                                                         </div>
                                                                 </div>

                                                         </div>

                                                 </div>

                                         </div>
                                         <!-- Bootstrap core JavaScript-->
                                                <script src="<?php echo base_url()?>vendor/jquery/jquery.min.js"></script>
                                                <script src="<?php echo base_url()?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                                                <!-- Core plugin JavaScript-->
                                                <script src="<?php echo base_url()?>vendor/jquery-easing/jquery.easing.min.js"></script>

                                                <!-- Custom scripts for all pages-->
                                                <script src="<?php echo base_url()?>js/sb-admin-2.min.js"></script>
                                                <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
                                                <script type="text/javascript">
                                                        var onloadCallback = function() {
                                                                grecaptcha.render('html_element', {
                                                                        'sitekey' : '6Le3P8YUAAAAACR2BGDZQau2HJF_LJDyy8ivgBi5'
                                                                });
                                                        };
                                                </script>

                                        </body>

                                        </html>
                                                                                                                                                                                                                                                                    125,1         Bot
