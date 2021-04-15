<?php
session_start();
?>
<link rel="stylesheet" href="../../../app-assets/css-loader-master/dist/css-loader.css">
<div id='load' class="loader loader-double is-active"></div>
<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../login/login.php');
}

require '../../../partials/db.php';
$message = '';

if (isset($_GET['id']) and isset($_GET['lis'])) {
    $idContactos = $_GET['id'];
    $Lis = $_GET['lis'];
    $user_id = $_SESSION['user_id'];
    $sql = 'SELECT cod_pais, nombre_contacto, celular  FROM contactos WHERE id_contacto=:idContactos AND usuario=:user_id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idContactos', $idContactos);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results) {
        header('Location: verContactos.php?id=' . $Lis);
    } else if (isset($_POST['sub'])) {
        $nombre = trim(isset($_POST['contact-name']) ? $_POST['contact-name'] : '');
        $codPais = isset($_POST['code-call']) ? $_POST['code-call'] : '';
        $celular = isset($_POST['contact-number']) ? $_POST['contact-number'] : '';
        if (formValidation($nombre, $codPais, $celular, $conn)) {
            $sql = 'UPDATE contactos SET nombre_contacto=:nombre_contacto, cod_pais=:cod_pais, celular=:celular WHERE id_contacto=:id_contacto AND usuario=:user_id';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre_contacto', $nombre);
            $stmt->bindParam(':cod_pais', $codPais);
            $stmt->bindParam(':celular', $celular);
            $stmt->bindParam(':id_contacto', $idContactos);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            header('Location: verContactos.php?id=' . $Lis);
        }
    }
} else {
    header('Location: verContactos.php');
}

function formValidation($nombre, $codPais, $celular)
{
    if (!nombreValidation($nombre)) {
        return false;
    } else if (!codCallValidation($codPais)) {
        return false;
    } else if (!celularValidation($celular)) {
        return false;
    }
    return true;
}

function nombreValidation($nombre)
{
    global $message;
    if (empty($nombre)) {
        $message = 'Por favor introduzca un nombre';
        return false;
    }
    return true;
}

function codCallValidation($codPais)
{
    global $message;
    if (empty($codPais)) {
        $message = 'Por favor seleccione un código';
        return false;
    } else if (!ctype_digit($codPais)) {
        $message = 'Por favor introduzca solo números';
        return false;
    }
    return true;
}

function celularValidation($celular)
{
    global $message;
    if (empty($celular)) {
        $message = 'Por favor introduzca un número';
        return false;
    } else if (!ctype_digit($celular)) {
        $message = 'Por favor introduzca solo números';
        return false;
    } else if (strlen($celular) < 10 or strlen($celular) > 15) {
        $message = 'Por favor introduzca un número válido';
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
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/bordered-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/plugins/forms/form-validation.css">
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
                <ul class="nav navbar-nav bookmark-icons">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email.html" data-toggle="tooltip" data-placement="top" title="Email"><i class="ficon" data-feather="mail"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-chat.html" data-toggle="tooltip" data-placement="top" title="Chat"><i class="ficon" data-feather="message-square"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calendar.html" data-toggle="tooltip" data-placement="top" title="Calendar"><i class="ficon" data-feather="calendar"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-todo.html" data-toggle="tooltip" data-placement="top" title="Todo"><i class="ficon" data-feather="check-square"></i></a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i class="ficon text-warning" data-feather="star"></i></a>
                        <div class="bookmark-input search-input">
                            <div class="bookmark-input-icon"><i data-feather="search"></i></div>
                            <input class="form-control input" type="text" placeholder="Bookmark" tabindex="0" data-search="search">
                            <ul class="search-list search-list-bookmark"></ul>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
                <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
                    <div class="search-input">
                        <div class="search-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="-1" data-search="search">
                        <div class="search-input-close"><i data-feather="x"></i></div>
                        <ul class="search-list search-list-main"></ul>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-cart mr-25"><a class="nav-link" href="javascript:void(0);" data-toggle="dropdown"><i class="ficon" data-feather="shopping-cart"></i><span class="badge badge-pill badge-primary badge-up cart-item-count">6</span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 mr-auto">My Cart</h4>
                                <div class="badge badge-pill badge-light-primary">4 Items</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <div class="media align-items-center"><img class="d-block rounded mr-1" src="../../../app-assets/images/pages/eCommerce/1.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html"> Apple watch 5</a></h6><small class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="1">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$374.90</h5>
                                </div>
                            </div>
                            <div class="media align-items-center"><img class="d-block rounded mr-1" src="../../../app-assets/images/pages/eCommerce/7.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html"> Google Home Mini</a></h6><small class="cart-item-by">By Google</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="3">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$129.40</h5>
                                </div>
                            </div>
                            <div class="media align-items-center"><img class="d-block rounded mr-1" src="../../../app-assets/images/pages/eCommerce/2.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html"> iPhone 11 Pro</a></h6><small class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="2">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$699.00</h5>
                                </div>
                            </div>
                            <div class="media align-items-center"><img class="d-block rounded mr-1" src="../../../app-assets/images/pages/eCommerce/3.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html"> iMac Pro</a></h6><small class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="1">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$4,999.00</h5>
                                </div>
                            </div>
                            <div class="media align-items-center"><img class="d-block rounded mr-1" src="../../../app-assets/images/pages/eCommerce/5.png" alt="donuts" width="62">
                                <div class="media-body"><i class="ficon cart-item-remove" data-feather="x"></i>
                                    <div class="media-heading">
                                        <h6 class="cart-item-title"><a class="text-body" href="app-ecommerce-details.html"> MacBook Pro</a></h6><small class="cart-item-by">By Apple</small>
                                    </div>
                                    <div class="cart-item-qty">
                                        <div class="input-group">
                                            <input class="touchspin-cart" type="number" value="1">
                                        </div>
                                    </div>
                                    <h5 class="cart-item-price">$2,999.00</h5>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="font-weight-bolder mb-0">Total:</h6>
                                <h6 class="text-primary font-weight-bolder mb-0">$10,999.00</h6>
                            </div><a class="btn btn-primary btn-block" href="app-ecommerce-checkout.html">Checkout</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-notification mr-25"><a class="nav-link" href="javascript:void(0);" data-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span class="badge badge-pill badge-danger badge-up">5</span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 mr-auto">Notifications</h4>
                                <div class="badge badge-pill badge-light-primary">6 New</div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list"><a class="d-flex" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar"><img src="../../../app-assets/images/portrait/small/avatar-s-15.jpg" alt="avatar" width="32" height="32"></div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">Congratulation Sam
                                                🎉</span>winner!</p><small class="notification-text"> Won the monthly
                                            best seller badge.</small>
                                    </div>
                                </div>
                            </a><a class="d-flex" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar"><img src="../../../app-assets/images/portrait/small/avatar-s-3.jpg" alt="avatar" width="32" height="32"></div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">New
                                                message</span>&nbsp;received</p><small class="notification-text"> You
                                            have 10 unread messages</small>
                                    </div>
                                </div>
                            </a><a class="d-flex" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar bg-light-danger">
                                            <div class="avatar-content">MD</div>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">Revised Order
                                                👋</span>&nbsp;checkout</p><small class="notification-text"> MD Inc.
                                            order updated</small>
                                    </div>
                                </div>
                            </a>
                            <div class="media d-flex align-items-center">
                                <h6 class="font-weight-bolder mr-auto mb-0">System Notifications</h6>
                                <div class="custom-control custom-control-primary custom-switch">
                                    <input class="custom-control-input" id="systemNotification" type="checkbox" checked="">
                                    <label class="custom-control-label" for="systemNotification"></label>
                                </div>
                            </div><a class="d-flex" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar bg-light-danger">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">Server
                                                down</span>&nbsp;registered</p><small class="notification-text"> USA
                                            Server is down due to hight CPU usage</small>
                                    </div>
                                </div>
                            </a><a class="d-flex" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar bg-light-success">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">Sales
                                                report</span>&nbsp;generated</p><small class="notification-text"> Last
                                            month sales report generated</small>
                                    </div>
                                </div>
                            </a><a class="d-flex" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar bg-light-warning">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">High
                                                memory</span>&nbsp;usage</p><small class="notification-text"> BLR Server
                                            using high memory</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-menu-footer"><a class="btn btn-primary btn-block" href="javascript:void(0)">Read all notifications</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder">John
                                Doe</span><span class="user-status">Admin</span></div><span class="avatar"><img class="round" src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user"><a class="dropdown-item" href="page-profile.html"><i class="mr-50" data-feather="user"></i>
                            Profile</a><a class="dropdown-item" href="app-email.html"><i class="mr-50" data-feather="mail"></i> Inbox</a><a class="dropdown-item" href="app-todo.html"><i class="mr-50" data-feather="check-square"></i> Task</a><a class="dropdown-item" href="app-chat.html"><i class="mr-50" data-feather="message-square"></i> Chats</a>
                        <div class="dropdown-divider"></div><a class="dropdown-item" href="page-account-settings.html"><i class="mr-50" data-feather="settings"></i>
                            Settings</a><a class="dropdown-item" href="page-pricing.html"><i class="mr-50" data-feather="credit-card"></i> Pricing</a><a class="dropdown-item" href="page-faq.html"><i class="mr-50" data-feather="help-circle"></i> FAQ</a><a class="dropdown-item" href="page-auth-login-v2.html"><i class="mr-50" data-feather="power"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <ul class="main-search-list-defaultlist d-none">
        <li class="d-flex align-items-center"><a href="javascript:void(0);">
                <h6 class="section-label mt-75 mb-0">Files</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../../app-assets/images/icons/xls.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing
                            Manager</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;17kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../../app-assets/images/icons/jpg.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;11kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../../app-assets/images/icons/pdf.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital
                            Marketing Manager</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;150kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="../../../app-assets/images/icons/doc.png" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web
                            Designer</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;256kb</small>
            </a></li>
        <li class="d-flex align-items-center"><a href="javascript:void(0);">
                <h6 class="section-label mt-75 mb-0">Members</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../../app-assets/images/portrait/small/avatar-s-8.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../../app-assets/images/portrait/small/avatar-s-1.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd
                            Developer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../../app-assets/images/portrait/small/avatar-s-14.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing
                            Manager</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="../../../app-assets/images/portrait/small/avatar-s-6.jpg" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
                    </div>
                </div>
            </a></li>
    </ul>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="mr-75" data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="../../../html/ltr/vertical-menu-template/index.html"><span class="brand-logo">
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
                <li class=" nav-item active"><a class="d-flex align-items-center" href="listasContactos.php"><i data-feather="phone"></i><span class="menu-title text-truncate">Lista de contactos</span></a>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center" href="../../sms-masivos/smsMasivos.php"><i data-feather="mail"></i><span class="menu-title text-truncate">Sms masivos</span></a>
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
                                <span aria-hidden="true">×</span>
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
                            <h2 class="content-header-title float-left mb-0">Lista de contactos</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="../listasContactos.php">Lista de contactos</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="../verListas.php">Listas</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="verContactos.php?id=<?= $Lis; ?>">Contactos de la lista</a>
                                    </li>
                                    <li class="breadcrumb-item active">Editar Contacto
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
                                    <h4 class="card-title">Editar contacto</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="editarContactos.php?id=<?= $idContactos; ?>&lis=<?= $Lis; ?>" method="POST">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">Nombre</label>
                                                    <input type="text" autofocus id="first-name-column" class="form-control form-control-lg" placeholder="Luis Rodriguez" name="contact-name" value="" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">Código de país</label>
                                                    <select class="select2-size-lg form-control basic-select" name="code-call">
                                                        <option value="">Seleccione un pais...</option>
                                                        <option value="7">
                                                            Abkhazia
                                                        </option>
                                                        <option value="93">
                                                            Afghanistan
                                                        </option>
                                                        <option value="355">
                                                            Albania
                                                        </option>
                                                        <option value="213">
                                                            Algeria
                                                        </option>
                                                        <option value="1">
                                                            American Samoa
                                                        </option>
                                                        <option value="376">
                                                            Andorra
                                                        </option>
                                                        <option value="244">
                                                            Angola
                                                        </option>
                                                        <option value="1">
                                                            Anguilla
                                                        </option>
                                                        <option value="1">
                                                            Antigua and Barbuda
                                                        </option>
                                                        <option value="54">
                                                            Argentina
                                                        </option>
                                                        <option value="374">
                                                            Armenia
                                                        </option>
                                                        <option value="297">
                                                            Aruba
                                                        </option>
                                                        <option value="247">
                                                            Ascension
                                                        </option>
                                                        <option value="61">
                                                            Australia
                                                        </option>
                                                        <option value="672">
                                                            Australian External Territories
                                                        </option>
                                                        <option value="43">
                                                            Austria
                                                        </option>
                                                        <option value="994">
                                                            Azerbaijan
                                                        </option>
                                                        <option value="1">
                                                            Bahamas
                                                        </option>
                                                        <option value="973">
                                                            Bahrain
                                                        </option>
                                                        <option value="880">
                                                            Bangladesh
                                                        </option>
                                                        <option value="1">
                                                            Barbados
                                                        </option>
                                                        <option value="1">
                                                            Barbuda
                                                        </option>
                                                        <option value="375">
                                                            Belarus
                                                        </option>
                                                        <option value="32">
                                                            Belgium
                                                        </option>
                                                        <option value="501">
                                                            Belize
                                                        </option>
                                                        <option value="229">
                                                            Benin
                                                        </option>
                                                        <option value="1">
                                                            Bermuda
                                                        </option>
                                                        <option value="975">
                                                            Bhutan
                                                        </option>
                                                        <option value="591">
                                                            Bolivia
                                                        </option>
                                                        <option value="387">
                                                            Bosnia and Herzegovina
                                                        </option>
                                                        <option value="267">
                                                            Botswana
                                                        </option>
                                                        <option value="55">
                                                            Brazil
                                                        </option>
                                                        <option value="246">
                                                            British Indian Ocean Territory
                                                        </option>
                                                        <option value="1">
                                                            British Virgin Islands
                                                        </option>
                                                        <option value="673">
                                                            Brunei
                                                        </option>
                                                        <option value="359">
                                                            Bulgaria
                                                        </option>
                                                        <option value="226">
                                                            Burkina Faso
                                                        </option>
                                                        <option value="257">
                                                            Burundi
                                                        </option>
                                                        <option value="855">
                                                            Cambodia
                                                        </option>
                                                        <option value="237">
                                                            Cameroon
                                                        </option>
                                                        <option value="1">
                                                            Canada
                                                        </option>
                                                        <option value="238">
                                                            Cape Verde
                                                        </option>
                                                        <option value="345">
                                                            Cayman Islands
                                                        </option>
                                                        <option value="236">
                                                            Central African Republic
                                                        </option>
                                                        <option value="235">
                                                            Chad
                                                        </option>
                                                        <option value="56">
                                                            Chile
                                                        </option>
                                                        <option value="86">
                                                            China
                                                        </option>
                                                        <option value="61">
                                                            Christmas Island
                                                        </option>
                                                        <option value="61">
                                                            Cocos-Keeling Islands
                                                        </option>
                                                        <option value="57">
                                                            Colombia
                                                        </option>
                                                        <option value="269">
                                                            Comoros
                                                        </option>
                                                        <option value="242">
                                                            Congo
                                                        </option>
                                                        <option value="243">
                                                            Congo, Dem. Rep. of (Zaire)
                                                        </option>
                                                        <option value="682">
                                                            Cook Islands
                                                        </option>
                                                        <option value="506">
                                                            Costa Rica
                                                        </option>
                                                        <option value="385">
                                                            Croatia
                                                        </option>
                                                        <option value="53">
                                                            Cuba
                                                        </option>
                                                        <option value="599">
                                                            Curacao
                                                        </option>
                                                        <option value="537">
                                                            Cyprus
                                                        </option>
                                                        <option value="420">
                                                            Czech Republic
                                                        </option>
                                                        <option value="45">
                                                            Denmark
                                                        </option>
                                                        <option value="246">
                                                            Diego Garcia
                                                        </option>
                                                        <option value="253">
                                                            Djibouti
                                                        </option>
                                                        <option value="1">
                                                            Dominica
                                                        </option>
                                                        <option value="1">
                                                            Dominican Republic
                                                        </option>
                                                        <option value="670">
                                                            East Timor
                                                        </option>
                                                        <option value="56">
                                                            Easter Island
                                                        </option>
                                                        <option value="593">
                                                            Ecuador
                                                        </option>
                                                        <option value="20">
                                                            Egypt
                                                        </option>
                                                        <option value="503">
                                                            El Salvador
                                                        </option>
                                                        <option value="240">
                                                            Equatorial Guinea
                                                        </option>
                                                        <option value="291">
                                                            Eritrea
                                                        </option>
                                                        <option value="372">
                                                            Estonia
                                                        </option>
                                                        <option value="251">
                                                            Ethiopia
                                                        </option>
                                                        <option value="500">
                                                            Falkland Islands
                                                        </option>
                                                        <option value="298">
                                                            Faroe Islands
                                                        </option>
                                                        <option value="679">
                                                            Fiji
                                                        </option>
                                                        <option value="358">
                                                            Finland
                                                        </option>
                                                        <option value="33">
                                                            France
                                                        </option>
                                                        <option value="596">
                                                            French Antilles
                                                        </option>
                                                        <option value="594">
                                                            French Guiana
                                                        </option>
                                                        <option value="689">
                                                            French Polynesia
                                                        </option>
                                                        <option value="241">
                                                            Gabon
                                                        </option>
                                                        <option value="220">
                                                            Gambia
                                                        </option>
                                                        <option value="995">
                                                            Georgia
                                                        </option>
                                                        <option value="49">
                                                            Germany
                                                        </option>
                                                        <option value="233">
                                                            Ghana
                                                        </option>
                                                        <option value="350">
                                                            Gibraltar
                                                        </option>
                                                        <option value="30">
                                                            Greece
                                                        </option>
                                                        <option value="299">
                                                            Greenland
                                                        </option>
                                                        <option value="1">
                                                            Grenada
                                                        </option>
                                                        <option value="590">
                                                            Guadeloupe
                                                        </option>
                                                        <option value="1">
                                                            Guam
                                                        </option>
                                                        <option value="502">
                                                            Guatemala
                                                        </option>
                                                        <option value="224">
                                                            Guinea
                                                        </option>
                                                        <option value="245">
                                                            Guinea-Bissau
                                                        </option>
                                                        <option value="595">
                                                            Guyana
                                                        </option>
                                                        <option value="509">
                                                            Haiti
                                                        </option>
                                                        <option value="504">
                                                            Honduras
                                                        </option>
                                                        <option value="852">
                                                            Hong Kong SAR China
                                                        </option>
                                                        <option value="36">
                                                            Hungary
                                                        </option>
                                                        <option value="354">
                                                            Iceland
                                                        </option>
                                                        <option value="91">
                                                            India
                                                        </option>
                                                        <option value="62">
                                                            Indonesia
                                                        </option>
                                                        <option value="98">
                                                            Iran
                                                        </option>
                                                        <option value="964">
                                                            Iraq
                                                        </option>
                                                        <option value="353">
                                                            Ireland
                                                        </option>
                                                        <option value="972">
                                                            Israel
                                                        </option>
                                                        <option value="39">
                                                            Italy
                                                        </option>
                                                        <option value="225">
                                                            Ivory Coast
                                                        </option>
                                                        <option value="1">
                                                            Jamaica
                                                        </option>
                                                        <option value="81">
                                                            Japan
                                                        </option>
                                                        <option value="962">
                                                            Jordan
                                                        </option>
                                                        <option value="7">
                                                            Kazakhstan
                                                        </option>
                                                        <option value="254">
                                                            Kenya
                                                        </option>
                                                        <option value="686">
                                                            Kiribati
                                                        </option>
                                                        <option value="965">
                                                            Kuwait
                                                        </option>
                                                        <option value="996">
                                                            Kyrgyzstan
                                                        </option>
                                                        <option value="856">
                                                            Laos
                                                        </option>
                                                        <option value="371">
                                                            Latvia
                                                        </option>
                                                        <option value="961">
                                                            Lebanon
                                                        </option>
                                                        <option value="266">
                                                            Lesotho
                                                        </option>
                                                        <option value="231">
                                                            Liberia
                                                        </option>
                                                        <option value="218">
                                                            Libya
                                                        </option>
                                                        <option value="423">
                                                            Liechtenstein
                                                        </option>
                                                        <option value="370">
                                                            Lithuania
                                                        </option>
                                                        <option value="352">
                                                            Luxembourg
                                                        </option>
                                                        <option value="853">
                                                            Macau SAR China
                                                        </option>
                                                        <option value="389">
                                                            Macedonia
                                                        </option>
                                                        <option value="261">
                                                            Madagascar
                                                        </option>
                                                        <option value="265">
                                                            Malawi
                                                        </option>
                                                        <option value="60">
                                                            Malaysia
                                                        </option>
                                                        <option value="960">
                                                            Maldives
                                                        </option>
                                                        <option value="223">
                                                            Mali
                                                        </option>
                                                        <option value="356">
                                                            Malta
                                                        </option>
                                                        <option value="692">
                                                            Marshall Islands
                                                        </option>
                                                        <option value="596">
                                                            Martinique
                                                        </option>
                                                        <option value="222">
                                                            Mauritania
                                                        </option>
                                                        <option value="230">
                                                            Mauritius
                                                        </option>
                                                        <option value="262">
                                                            Mayotte
                                                        </option>
                                                        <option value="52">
                                                            Mexico
                                                        </option>
                                                        <option value="691">
                                                            Micronesia
                                                        </option>
                                                        <option value="1">
                                                            Midway Island
                                                        </option>
                                                        <option value="373">
                                                            Moldova
                                                        </option>
                                                        <option value="377">
                                                            Monaco
                                                        </option>
                                                        <option value="976">
                                                            Mongolia
                                                        </option>
                                                        <option value="382">
                                                            Montenegro
                                                        </option>
                                                        <option value="1664">
                                                            Montserrat
                                                        </option>
                                                        <option value="212">
                                                            Morocco
                                                        </option>
                                                        <option value="95">
                                                            Myanmar
                                                        </option>
                                                        <option value="264">
                                                            Namibia
                                                        </option>
                                                        <option value="674">
                                                            Nauru
                                                        </option>
                                                        <option value="977">
                                                            Nepal
                                                        </option>
                                                        <option value="31">
                                                            Netherlands
                                                        </option>
                                                        <option value="599">
                                                            Netherlands Antilles
                                                        </option>
                                                        <option value="1">
                                                            Nevis
                                                        </option>
                                                        <option value="687">
                                                            New Caledonia
                                                        </option>
                                                        <option value="64">
                                                            New Zealand
                                                        </option>
                                                        <option value="505">
                                                            Nicaragua
                                                        </option>
                                                        <option value="227">
                                                            Niger
                                                        </option>
                                                        <option value="234">
                                                            Nigeria
                                                        </option>
                                                        <option value="683">
                                                            Niue
                                                        </option>
                                                        <option value="672">
                                                            Norfolk Island
                                                        </option>
                                                        <option value="850">
                                                            North Korea
                                                        </option>
                                                        <option value="1">
                                                            Northern Mariana Islands
                                                        </option>
                                                        <option value="47">
                                                            Norway
                                                        </option>
                                                        <option value="968">
                                                            Oman
                                                        </option>
                                                        <option value="92">
                                                            Pakistan
                                                        </option>
                                                        <option value="680">
                                                            Palau
                                                        </option>
                                                        <option value="970">
                                                            Palestinian Territory
                                                        </option>
                                                        <option value="507">
                                                            Panama
                                                        </option>
                                                        <option value="675">
                                                            Papua New Guinea
                                                        </option>
                                                        <option value="595">
                                                            Paraguay
                                                        </option>
                                                        <option value="51">
                                                            Peru
                                                        </option>
                                                        <option value="63">
                                                            Philippines
                                                        </option>
                                                        <option value="48">
                                                            Poland
                                                        </option>
                                                        <option value="351">
                                                            Portugal
                                                        </option>
                                                        <option value="1">
                                                            Puerto Rico
                                                        </option>
                                                        <option value="974">
                                                            Qatar
                                                        </option>
                                                        <option value="262">
                                                            Reunion
                                                        </option>
                                                        <option value="40">
                                                            Romania
                                                        </option>
                                                        <option value="7">
                                                            Russia
                                                        </option>
                                                        <option value="250">
                                                            Rwanda
                                                        </option>
                                                        <option value="685">
                                                            Samoa
                                                        </option>
                                                        <option value="378">
                                                            San Marino
                                                        </option>
                                                        <option value="966">
                                                            Saudi Arabia
                                                        </option>
                                                        <option value="221">
                                                            Senegal
                                                        </option>
                                                        <option value="381">
                                                            Serbia
                                                        </option>
                                                        <option value="248">
                                                            Seychelles
                                                        </option>
                                                        <option value="232">
                                                            Sierra Leone
                                                        </option>
                                                        <option value="65">
                                                            Singapore
                                                        </option>
                                                        <option value="421">
                                                            Slovakia
                                                        </option>
                                                        <option value="386">
                                                            Slovenia
                                                        </option>
                                                        <option value="677">
                                                            Solomon Islands
                                                        </option>
                                                        <option value="27">
                                                            South Africa
                                                        </option>
                                                        <option value="500">
                                                            South Georgia and the South Sandwich Islands
                                                        </option>
                                                        <option value="82">
                                                            South Korea
                                                        </option>
                                                        <option value="34">
                                                            Spain
                                                        </option>
                                                        <option value="94">
                                                            Sri Lanka
                                                        </option>
                                                        <option value="249">
                                                            Sudan
                                                        </option>
                                                        <option value="597">
                                                            Suriname
                                                        </option>
                                                        <option value="268">
                                                            Swaziland
                                                        </option>
                                                        <option value="46">
                                                            Sweden
                                                        </option>
                                                        <option value="41">
                                                            Switzerland
                                                        </option>
                                                        <option value="963">
                                                            Syria
                                                        </option>
                                                        <option value="886">
                                                            Taiwan
                                                        </option>
                                                        <option value="992">
                                                            Tajikistan
                                                        </option>
                                                        <option value="255">
                                                            Tanzania
                                                        </option>
                                                        <option value="66">
                                                            Thailand
                                                        </option>
                                                        <option value="670">
                                                            Timor Leste
                                                        </option>
                                                        <option value="228">
                                                            Togo
                                                        </option>
                                                        <option value="690">
                                                            Tokelau
                                                        </option>
                                                        <option value="676">
                                                            Tonga
                                                        </option>
                                                        <option value="1">
                                                            Trinidad and Tobago
                                                        </option>
                                                        <option value="216">
                                                            Tunisia
                                                        </option>
                                                        <option value="90">
                                                            Turkey
                                                        </option>
                                                        <option value="993">
                                                            Turkmenistan
                                                        </option>
                                                        <option value="1">
                                                            Turks and Caicos Islands
                                                        </option>
                                                        <option value="688">
                                                            Tuvalu
                                                        </option>
                                                        <option value="1">
                                                            U.S. Virgin Islands
                                                        </option>
                                                        <option value="256">
                                                            Uganda
                                                        </option>
                                                        <option value="380">
                                                            Ukraine
                                                        </option>
                                                        <option value="971">
                                                            United Arab Emirates
                                                        </option>
                                                        <option value="44">
                                                            United Kingdom
                                                        </option>
                                                        <option value="1">
                                                            United States
                                                        </option>
                                                        <option value="598">
                                                            Uruguay
                                                        </option>
                                                        <option value="998">
                                                            Uzbekistan
                                                        </option>
                                                        <option value="678">
                                                            Vanuatu
                                                        </option>
                                                        <option value="58">
                                                            Venezuela
                                                        </option>
                                                        <option value="84">
                                                            Vietnam
                                                        </option>
                                                        <option value="1">
                                                            Wake Island
                                                        </option>
                                                        <option value="681">
                                                            Wallis and Futuna
                                                        </option>
                                                        <option value="967">
                                                            Yemen
                                                        </option>
                                                        <option value="260">
                                                            Zambia
                                                        </option>
                                                        <option value="255">
                                                            Zanzibar
                                                        </option>
                                                        <option value="263">
                                                            Zimbabwe
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">Celular</label>
                                                    <input type="text" id="second-celular-column" class="form-control form-control-lg" placeholder="1234567891" name="contact-number" value="" />
                                                </div>
                                            </div>
                                            <input type="hidden" name="sub" value="">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary mr-1">Editar</button>
                                            </div>
                                        </div>
                                    </form>
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
    <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <script src="../../../app-assets/js/scripts/customizer.js"></script>
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
                $('.loader').removeClass('is-active');
            }
        });
    </script>

    <!-- BEGIN: Custom JS-->
    <script>
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