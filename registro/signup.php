<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
}

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require '../vendor/autoload.php';
require '../partials/db.php';

$errorEmail = '';
$errorUsername = '';
$errorPassword = '';
$message = '';

if (!empty($_POST['sub'])) {
    if (validarForm($_POST['register-email'], $_POST['register-username'], $_POST['register-password'])) {
        $hashedPass = password_hash($_POST['register-password'], PASSWORD_BCRYPT);
        $hash = md5(rand(0, 1000));
        $sql = 'INSERT INTO usuarios (nombre_usuario, email, password, hash) VALUES (:nombre_usuario, :email, :password, :hash)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre_usuario', $_POST['register-username']);
        $stmt->bindParam(':email', $_POST['register-email']);
        $stmt->bindParam(':password', $hashedPass);
        $stmt->bindParam(':hash', $hash);
        $stmt->execute();

        enviarEmail($_POST['register-email'], $hash);
    }
}

function validarForm($email, $username, $password)
{
    global $errorEmail, $errorUsername, $errorPassword;

    validarEmail($email);
    validarUsername($username);
    validarPassword($password);

    if (empty($errorEmail) and empty($errorUsername) and empty($errorPassword)) {
        return true;
    } else {
        return false;
    }
}

function validarEmail($email)
{
    global $errorEmail;

    if (empty($email)) {
        $errorEmail = 'Por favor introduzca un email';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail = 'Email no valido';
    } else if (buscarEmail($email)) {
        $errorEmail = 'Este email ya existe';
    }
    return $errorEmail;
}

function buscarEmail($email)
{
    global $conn;

    $sql = "SELECT email FROM usuarios WHERE email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return false;
    }
    return true;
}

function validarUsername($username)
{
    global $errorUsername;

    if (empty($username)) {
        $errorUsername = 'Por favor introduzca un nombre de usuario';
    } else if (buscarUsername($username)) {
        $errorUsername = 'Este username ya existe';
    }
    return $errorUsername;
}

function buscarUsername($username)
{
    global $conn;

    $sql = "SELECT nombre_usuario FROM usuarios WHERE nombre_usuario=:username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return false;
    }
    return true;
}

function validarPassword($password)
{
    global $errorPassword;

    if (empty($password)) {
        $errorPassword = 'Por favor introduzca una contraseña';
    }
    return $errorPassword;
}

function mensajeError($errorUsername, $errorEmail)
{
    global $message;

    if ($errorEmail == 'Este email ya existe') {
        $message = 'Este email ya existe';
    } else if ($errorUsername == 'Este username ya existe') {
        $message = 'Este username ya existe';
    }
    return $message;
}

function enviarEmail($email, $hash)
{
    try {
        //Create a new PHPMailer instance
        $mail = new PHPMailer();

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        //SMTP::DEBUG_OFF = off (for production use)
        //SMTP::DEBUG_CLIENT = client messages
        //SMTP::DEBUG_SERVER = client and server messages
        $mail->SMTPDebug = SMTP::DEBUG_OFF;

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        //Use `$mail->Host = gethostbyname('smtp.gmail.com');`
        //if your network does not support SMTP over IPv6,
        //though this may cause issues with TLS

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

        //Set the encryption mechanism to use - STARTTLS or SMTPS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = '';

        //Password to use for SMTP authentication
        $mail->Password = '';

        //Set who the message is to be sent from
        $mail->setFrom('adrianjaa10142002@gmail.com', 'Support');

        //Set an alternative reply-to address
        $mail->addReplyTo('adrianjaa10142002@gmail.com', 'Support');

        //Set who the message is to be sent to
        $mail->addAddress($email);

        $stringBody = 'Gracias por registrarse!
        Su cuenta ha sido creada, presione el siguiente link para activar su cuenta:
        http://localhost:81/Cellvoz%20API/verificar/verify.php?email=' . $email . '&hash=' . $hash;
        $emailMsgBody = utf8_encode($stringBody);
        $stringSubject = 'Registro | Verificación';
        $emailMsgSubject = utf8_encode($stringSubject);
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $emailMsgSubject;
        $mail->Body    = $emailMsgBody;
        $mail->AltBody = $emailMsgBody;

        $mail->send();
        header('Location: emailSend.php');
    } catch (Exception $e) {
        global $message;
        $message = 'Ha ocurrido un error enviando el correo de verificacion. ' . $e;
    }
}
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Register Page - Vuexy - Bootstrap HTML admin template</title>
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
    <link rel="stylesheet" type="text/css" href="../app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="../app-assets/css/pages/page-auth.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">

    <?php
    if (!empty(mensajeError($errorUsername, $errorEmail))) {
        echo '<!-- BEGIN: Custom modal -->
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
                        ' . $message . '
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Custom modal -->';
    }
    ?>

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-v1 px-2">
                    <div class="auth-inner py-2">
                        <!-- Register v1 -->
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="brand-logo">
                                    <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="28">
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
                                                    <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill: currentColor"></path>
                                                    <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                                                    <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                                    <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                                    <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <h2 class="brand-text text-primary ml-1">Vuexy</h2>
                                </a>

                                <h4 class="card-title mb-1">¿Es nuevo en la página?</h4>
                                <p class="card-text mb-2">Por favor ingrese sus datos e el siguiente formulario</p>

                                <form class="auth-register-form mt-2" action="signup.php" method="POST">
                                    <div class="form-group">
                                        <label for="register-username" class="form-label">Nombre de usuario</label>
                                        <input type="text" class="form-control" id="register-username" name="register-username" placeholder="johndoe" aria-describedby="register-username" tabindex="1" autofocus />
                                    </div>
                                    <input type="hidden" name="sub" value="submit">
                                    <div class="form-group">
                                        <label for="register-email" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="register-email" name="register-email" placeholder="john@example.com" aria-describedby="register-email" tabindex="2" />
                                    </div>

                                    <div class="form-group">
                                        <label for="register-password" class="form-label">Contraseña</label>

                                        <div class="input-group form-password-toggle">
                                            <input type="password" class="form-control form-control-merge" id="register-password" name="register-password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="register-password" tabindex="3" />
                                            <div class="input-group-append">
                                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-block" tabindex="5">Registrarse</button>
                                </form>

                                <p class="text-center mt-2">
                                    <span>¿Ya posee una cuenta?</span>
                                    <a href="../login/login.php">
                                        <span> Inicie sessión</span>
                                    </a>
                                </p>
                                <p class="text-center mt-2">
                                    <span>¿No ha activado su cuenta?</span>
                                    <a href="../reenviar/reenviar.php">
                                        <span> Actívela ahora</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <!-- /Register v1 -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../app-assets/js/core/app-menu.js"></script>
    <script src="../app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../app-assets/js/scripts/pages/page-auth-register.js"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
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