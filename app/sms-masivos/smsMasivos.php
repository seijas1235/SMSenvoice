<?php
session_start();
error_reporting(0);
?>
<script src="../../app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/extensions/sweetalert2.min.css">

<!-- Template files -->
<link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/extensions/ext-component-sweet-alerts.css">
<link rel="stylesheet" href="../../app-assets/css-loader-master/dist/css-loader.css">
<div id='load' class="loader loader-double is-active"></div>
<?php

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login/login.php');
}

require '../../partials/db.php';

$userId = $_SESSION['user_id'];
$sql = 'SELECT saldo FROM usuarios WHERE ID=:user_id';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';
$user_id = $_SESSION['user_id'];
$sql = 'SELECT l.id_tabla as id_tabla, l.nombre_tabla_contactos as nombre_tabla_contactos, count(C.id_contacto) as cantidad FROM lista_contactos l
inner join contactos C on C.lista= l.id_tabla
WHERE l.usuario=:user_id';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['sub'])) {
    $lista = isset($_POST['sms-list']) ? $_POST['sms-list'] : '';
    $message_sms = isset($_POST['sms-message']) ? $_POST['sms-message'] : '';
    if (formValidation($lista, $message_sms, $user_id,$conn)) {
        header('Location: enviarSms.php?id=' . $lista . '&m=' . urlencode($message_sms));
    }
}

function formValidation($lista, $message_sms, $user_id,$conn)
{
    if (!listValidation($lista, $user_id,$conn)) {
        return false;
    } else if (!messageValidation($message_sms)) {
        return false;
    }
    return true;
}

function listValidation($lista, $user_id, $conn)
{
    global $message;
    if (empty($lista)) {
        $message = 'Por favor introduzca una lista';
        return false;
    } else if (!buscarLista($lista, $conn)) {
        $message = 'Seleccione una lista v??lida';
        return false;
    } else if (!buscarContactos($lista, $user_id,$conn)) {
        $message = 'Por favor seleccione una lista con contactos';
        return false;
    }
    return true;
}

function buscarLista($lista, $conn)
{
    global $user_id;
    $sql = 'SELECT id_tabla FROM lista_contactos WHERE usuario=:user_id AND id_tabla=:lista';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':lista', $lista);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        return false;
    }
    return true;
}

function buscarContactos($lista, $user_id,$conn)
{
    $sql = 'SELECT id_contacto FROM contactos WHERE lista=:lista AND usuario=:usuario';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':lista', $lista);
    $stmt->bindParam(':usuario', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        return false;
    }
    
    return true;
}

function messageValidation($message_sms)
{
    global $message;
    if (empty($message_sms)) {
        $message = 'Por favor introduzca un mensaje';
        return false;
    } else if (strlen($message) > 160) {
        $message = 'El m??ximo de caracteres es de 160';
        return false;
    }
    return true;
}
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean & modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Layout Empty - Vuexy - Bootstrap HTML admin template</title>
    <link rel="apple-touch-icon" href="../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/themes/bordered-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/forms/form-validation.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">
                <li class="nav-item"><a class="nav-link d-flex align-items-center" style="cursor: default;"><i data-feather="dollar-sign"></i><span class="h4 m-0"><?= $result['saldo']; ?></span></a>
                </li>
                <li><a class="dropdown-item" href="../checkout/checkout.php"><i class="mr-50" data-feather="dollar-sign"></i> Recarga
                        </a>
                        </li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder">adrianlibra</span></div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
   
                        <!-- <a class="dropdown-item" href="page-faq.html"><i class="mr-50" data-feather="help-circle"></i> FAQ
                        </a> -->
                        <a class="dropdown-item" href="../../logout/logout.php"><i class="mr-50" data-feather="power"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="../../html/ltr/vertical-menu-template/index.html"><span class="brand-logo">
                            <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                <defs>
                                    <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                    <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                                            <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                                            <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325">
                                            </polygon>
                                            <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338">
                                            </polygon>
                                            <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288">
                                            </polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg></span>
                        <h2 class="brand-text">Vuexy</h2>
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Funciones</span><i data-feather="more-horizontal"></i>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center" href="../listas-contactos/listasContactos.php"><i data-feather="phone"></i><span class="menu-title text-truncate">Lista de contactos</span></a>
                </li>
                <li class=" nav-item active"><a class="d-flex align-items-center" href="sms-masivos/smsMasivos.php"><i data-feather="mail"></i><span class="menu-title text-truncate">Sms masivos</span></a>
                </li>
            </ul>
        </div>
    </div>

    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <?php if (!empty($message)) : ?>
            <!-- BEGIN: Custom modal -->
            <div class="modal fade modal-danger" id="errorModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Error</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">??</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?= $message; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Custom modal -->
        <?php endif; ?>
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">SMS Masivos</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">SMS Masivos
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">SMS</h4>
                                </div>
                                <div class="row">
                                <div class="col-6">
                                    <label class="container">Lista de contactos
                                    <input type="radio" checked="checked" value='lt' name="radio">
                                    <span class="checkmark"></span>
                                    </label></div>
                                <div class="col-6">
                                    <label class="container">Archivo
                                    <input type="radio" name="radio" value='ex'>
                                    <span class="checkmark"></span>
                                    </label>
                                    </div>
                                </div>
                                
                                    
                                <div class="card-body">
                                <div id="ar" style="display:none">
                                <form class="form" action="enviarSmsExcel.php" method="post"
                                        name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                                        <div class='row'>
                                            <div class="col-md-6 col-12">
                                            
                                                <label>Elija Archivo Excel</label> 
                                                <input class="form-control" type="file" name="file"
                                                id="file" accept=".xls,.xlsx">
                                            
                                            </div>
                                            <div class="col-md-6 col-12">
                                            <br>
                                            
                                                 <button type="submit" id="submit" name="import"
                                                    class="btn btn-primary mr-1">Continuar</button>
                                            </div>
                                         
                                            <br><a href="../../ejemplo.xlsx" download="ejemplo.xlsx">Descargar archivo de ejemplo</a> <br>
                                        </div>
                                    
                                    </form>
                                </div>
                                <div id="lista" style="display:block">
                                    <form class="form" action="smsMasivos.php" method="POST">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">Lista de contactos</label>
                                                    <select class="select2-size-lg form-control basic-select" name="sms-list">
                                                        <option value="">Seleccione una lista de contactos...</option>
                                                        <?php foreach ($results as $result) : ?>
                                                            <option value="<?= $result['id_tabla']; ?>"><?= $result['nombre_tabla_contactos'] ; ?> <span style='color:green'> (<?= $result['cantidad'] ; ?>)</span> </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="text-sms">Mensaje</label>
                                                    <textarea data-length="160" class="form-control char-textarea" name="sms-message" id="text-sms" rows="5" style="resize: none;"></textarea>
                                                    <small class="textarea-counter-value float-right"><span class="char-count">0</span> / 160 </small>
                                                </div>
                                            </div>
                                            <input type="hidden" name="sub" value="">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary mr-1">Enviar</button>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">

    </footer>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../app-assets/js/core/app-menu.js"></script>
    <script src="../../app-assets/js/core/app.js"></script>
    <script src="../../app-assets/js/scripts/customizer.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
            $('.loader').removeClass('is-active');
           
        });
    </script>

    <!-- BEGIN: Custom JS-->
    <script>
        $('input[type=radio][name=radio]').change(function() {
            if (this.value == 'lt') {
                $('#ar').hide();
                $('#lista').show();
            }
            else if (this.value == 'ex') {
                
                $('#ar').show();
                $('#lista').hide();
            }
        });

        $(function() {
            // SHOW MODAL
            $('#errorModal').modal('show');

            // SELECT2
            $(".basic-select").select2();
        });
    </script>
    <!-- END: Custom JS-->
</body>
<!-- END: Body-->

</html>