<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Motomob SMT</title>
    <link rel="icon" type="image/x-icon" href<?=base_url()?>cork/src/assets/img/favicon.ico"/>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="<?=base_url()?>cork/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="<?=base_url()?>cork/layouts/vertical-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url()?>cork/src/assets/css/light/authentication/auth-cover.css" rel="stylesheet" type="text/css" />

    <link href="<?=base_url()?>cork/layouts/vertical-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url()?>cork/src/assets/css/dark/authentication/auth-cover.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

</head>
<body class="form">

    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">

            <div class="row">


                <div class="col-6 d-lg-flex d-none h-100 my-auto top-0 start-0 text-center justify-content-center flex-column">
                    <div class="auth-cover-bg-image"></div>
                    <div class="auth-overlay"></div>

                    <div class="auth-cover">

                        <div class="position-relative">

                            <img src="<?=base_url()?>cork/src/assets/img/auth-cover.svg" alt="auth-img">

                            <h2 class="mt-5 text-white font-weight-bolder px-2">Join the community of expert developers</h2>
                            <p class="text-white px-2">It is easy to setup with great customer experience. Start your 7-day free trial</p>
                        </div>

                    </div>

                </div>

                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto">
                    <div class="card">
                        <div class="card-body">
                          <form name="login_form" action="<?=base_url()?>index.php/login" method="post">
                            <div class="row">
                                <div class="col-md-12 mb-3">

                                    <h2>Sign In</h2>
                                    <p>Masukan user dan password anda</p>

                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">User</label>
                                        <input type="text" id="username" name="username" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label">Password</label>
                                        <input type="password" id="password" name="password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input class="form-check-input me-3" type="checkbox" id="form-check-default">
                                            <label class="form-check-label" for="form-check-default">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-4">
                                        <button class="btn btn-secondary w-100">SIGN IN</button>
                                        
                                    </div>
                                </div>

                            </div>

                            </form>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>


</body>
</html>
