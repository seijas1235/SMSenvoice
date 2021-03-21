<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
}

require '../partials/db.php';

$message = '';
$messageError = '';

if (isset($_GET['email']) && !empty($_GET['email']) and isset($_GET['hash']) && !empty($_GET['hash'])) {
    $email = $_GET['email'];
    $hash = $_GET['hash'];

    $sql = 'SELECT email, hash, password, active FROM usuarios WHERE email=:email AND hash=:hash';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':hash', $hash);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result != false) {
        if ($result['active'] = 0) {
            $message = 'Por favor active su cuenta';
        } else {
            if (isset($_POST['sub'])) {
                if (validarConfirmPassword($_POST['password'], $_POST['confirmPassword'])) {

                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $sql = 'UPDATE usuarios SET hash="", password=:password WHERE email=:email AND hash=:hash';
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':hash', $hash);
                    $stmt->execute();

                    header('Location: ../olvido/cambio/olvidoChanged.php');
                }
            }
        }
    } else {
        $message = 'El url ingresado no es válido';
    }
} else {
    $message = 'Petición inválida';
}

function validarConfirmPassword($password, $confirmPassword)
{
    global $messageError;
    if (empty($password)) {
        $messageError = 'Por favor introduzca una contraseña';
    } else if ($password != $confirmPassword) {
        $messageError = 'Las contraseñas no coinciden';
    } else {
        return true;
    }
    return false;
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
    <link rel="apple-touch-icon" href="../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/themes/bordered-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Content-->
    <div class="app-content content" style="padding: 0 !important; height: 100%; margin: 0;">
        <div class="content-wrapper" style="height: 100%;">
            <?php if (!empty($messageError)) : ?>
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
                                <?= $messageError; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Custom modal -->
            <?php endif; ?>
            <?php if (!empty($message)) : ?>
                <div class="row d-flex justify-content-center align-items-center" style="height: 100%; width: 100%; margin: 0;">
                    <div class="col-9 col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Cambio de contraseña</h4>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?= $message; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif (empty($message)) :  ?>
                <div class="row d-flex justify-content-center align-items-center" style="height: 100%; width: 100%; margin: 0;">
                    <div class="col-9 col-md-4">
                        <div class="card">
                            <form action="verifyPassword.php?email=<?= $_GET['email']; ?>&hash=<?= $_GET['hash']; ?>" method="POST">
                                <div class="card-header">
                                    <h4 class="card-title">Cambio de contraseña</h4>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="password" class="form-label-group">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" aria-describedby="password" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword" class="form-label-group">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirmPassword" aria-describedby="confirmPassword" name="confirmPassword">
                                        </div>
                                    </div>
                                    <input type="hidden" name="sub" value="submit">
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary mb-1">Enviar</button>
                                    <a href="../login/login.php"><button type="button" class="btn btn-secondary mb-1">Regresar al login</button></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- END: Content-->

    <!-- BEGIN: Vendor JS-->
    <script src="../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../app-assets/js/core/app-menu.js"></script>
    <script src="../app-assets/js/core/app.js"></script>
    <script src="../app-assets/js/scripts/customizer.js"></script>
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
        });
    </script>
    <!-- BEGIN: Custom JS-->
    <script>
        $(function() {
            // SHOW MODAL
            $('#errorModal').modal('show');
        });
    </script>
    <!-- END: Custom JS-->
</body>
<!-- END: Body-->

</html>