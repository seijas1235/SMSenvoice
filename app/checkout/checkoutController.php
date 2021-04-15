<?php

// QUITAR NOTICE Y WARNINGS

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login/login.php');
}

require '../../partials/db.php';

if (isset($_POST['sub_check'])) {
    try {
        $userId = $_SESSION['user_id'];
        $sql = 'SELECT nombre_usuario, email FROM usuarios WHERE ID=:user_id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $infoPago = new stdClass();
        $infoPago->user = $userId;
        $infoPago->description = "recarga " . $result['nombre_usuario'];
        $infoPago->referenceCode = md5(rand());
        $infoPago->amount = (isset($_POST['amount']) and ctype_digit($_POST['amount'])) ? $_POST['amount'] : '';
        $infoPago->signature = md5('4Vj8eK4rloUd272L48hsrarnUA~508029~' . $infoPago->referenceCode . '~' . $infoPago->amount . '~' . 'COP');
        $infoPago->buyerEmail = $result['email'];
        if (empty($infoPago->amount)) {
            throw new Exception("El formato tiene que ser numérico y no puede puede estar vacío");
        } else if (($infoPago->amount<25000)) {
            throw new Exception("El monto minimo de recarga es de $25,000.00");
        }
        
        echo json_encode($infoPago);
    } catch (Exception $e) {
        $error = new stdClass();
        $error->message = $e->getMessage();
        echo json_encode($error);
    }
} else {
    header('Location: checkout.php');
}
