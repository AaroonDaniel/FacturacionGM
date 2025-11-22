<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Facturación</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url ?>/Assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url ?>/Assets/css/googlecss.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="<?= base_url ?>/Assets/css/sb-admin-2.min.css" rel="stylesheet">
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
                            <div class="col-lg-6 d-none d-lg-block">
                                <img class="img-profile rounded-circle"
                                    src="<?= base_url ?>/Assets/img/undraw_profile.svg" alt="Imagen de login">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Acceso al Sistema</h1>
                                    </div>
                                    <form class="user" id="frmLogin">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="nick" 
                                                   name="nick" placeholder="Nick de usuario" required 
                                                   autocomplete="username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="clave" 
                                                   name="clave" placeholder="Clave de usuario" required 
                                                   autocomplete="current-password">
                                        </div>
                                        <div class="alert alert-danger d-none" role="alert" id="alerta">
                                            Mensaje
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block" onclick="frmLogin(event)">
                                            Ingresar
                                        </button>
                                        <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url ?>/Assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url ?>/Assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url ?>/Assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url ?>/Assets/js/sb-admin-2.min.js"></script>
    <script>    
        const base_url = "<?= base_url ?>"
    </script>
    <script src="<?= base_url ?>/Assets/js/login.js"></script>
    

</body>

</html>
