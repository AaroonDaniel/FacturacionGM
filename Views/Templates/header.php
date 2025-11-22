<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ventas y Facturación</title> <link href="<?=base_url?>Assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url?>Assets/css/googlecss.css" rel="stylesheet" type="text/css">

    <link href="<?=base_url?>Assets/css/sb-admin-2.min.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url?>Assets/css/dataTables.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url?>Assets/css/sweetalert2.min.css" rel="stylesheet" type="text/css">
    

</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=base_url?>"> <div class="sidebar-brand-icon">
                    <i class="fas fa-store-alt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Facturación</div> </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="<?=base_url?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Inicio</span></a>
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Parámetros</span> </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?=base_url?>Usuarios">Usuarios</a>
                        <a class="collapse-item" href="<?=base_url?>Clientes">Clientes</a>
                        <a class="collapse-item" href="<?=base_url?>Categorias">Categorías</a>
                        <a class="collapse-item" href="<?=base_url?>Medidas">Unidad Med.</a>
                        <a class="collapse-item" href="<?=base_url?>Productos">Productos</a>

                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?=base_url?>Productos">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Productos</span></a> </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTree"
                    aria-expanded="true" aria-controls="collapseTree">
                    <i class="fas fa-fw fa-file-invoice-dollar"></i> <span>Pedidos</span>
                </a>
                <div id="collapseTree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?=base_url?>Pedidos/nuevo_pedido">Nuevo pedido</a>
                        <a class="collapse-item" href="<?=base_url?>Pedidos">Facturas</a>
                    </div>
                </div>
            </li>


        </ul>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$_SESSION['nombre']?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?=base_url?>Assets/img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                            
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar Sesión
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <div class="container-fluid">

                    <div class=" align-items-center justify-content-between mb-4">