<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require '../../vendor/autoload.php';
require '../../partials/db.php';

$message = '';

if (!empty($_POST['sub']) and !empty($_POST['email'])) {
    $sql = "SELECT email, active FROM usuarios WHERE email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result != false) {
        if ($result['active'] == 0) {
            $message = 'Por favor active su cuenta';
        } else {
            $email = $_POST['email'];
            $hash = $hash = md5(rand(0, 1000));

            $sql = 'UPDATE usuarios SET hash=:hash WHERE email=:email AND active=1';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':hash', $hash);
            $stmt->execute();

            reenviarEmail($email, $hash);
        }
    } else {
        $message = 'El usuario ingresado no existe';
    }
}

function reenviarEmail($email, $hash)
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
        $stringBody = 'Presione el siguiente link para cambiar la contraseña de su cuenta:
        http://localhost:81/Cellvoz%20API/verificar/verifyPassword.php?email=' . $email . '&hash=' . $hash;
        $emailMsgBody = utf8_encode($stringBody);
        $stringSubject = 'Olvido de contraseña';
        $emailMsgSubject = utf8_encode($stringSubject);
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $emailMsgSubject;
        $mail->Body    = $emailMsgBody;
        $mail->AltBody = $emailMsgBody;

        $mail->send();
        header('Location: ../email/olvidoEmailSend.php');
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
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean & modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Layout Empty - Vuexy - Bootstrap HTML admin template</title>
    <link rel="apple-touch-icon" href="../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/vendors.min.css">
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
    <!-- BEGIN: Content-->
    <div class="app-content content" style="padding: 0 !important; height: 100%; margin: 0;">
        <div class="content-wrapper" style="height: 100%;">
            <?php
            if (!empty($message)) {
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
            <div class="row d-flex justify-content-center align-items-center" style="height: 100%; width: 100%; margin: 0;">
                <div class="col-9 col-md-4">
                    <div class="card">
                        <form action="olvidoPassword.php" method="POST">
                            <div class="card-header">
                                <h4 class="card-title">Olvido de contraseña</h4>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="email-input" class="form-label-group">Email</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="email-input" aria-describedby="emailHelp" name="email">
                                    </div>
                                </div>
                                <input type="hidden" name="sub" value="submit">
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mb-1">Enviar</button>
                                <a href="../../login/login.php"><button type="button" class="btn btn-secondary mb-1">Regresar al login</button></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- END: Content-->

    <!-- BEGIN: Vendor JS-->
    <script src="../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../app-assets/js/core/app-menu.js"></script>
    <script src="../../app-assets/js/core/app.js"></script>
    <script src="../../app-assets/js/scripts/customizer.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../../assets/js/olvidoPassword.js"></script>
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