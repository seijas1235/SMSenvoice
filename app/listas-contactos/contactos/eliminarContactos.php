<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login/login.php');
}

require '../../../partials/db.php';

if(isset($_GET['id']) and isset($_GET['lis'])) {
    $id = $_GET['id'];
    $lis = $_GET['lis'];
    $user_id = $_SESSION['user_id'];
    $sql = 'DELETE FROM contactos WHERE id_contacto=:id AND usuario=:user_id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    header('Location: verContactos.php?id=' . $lis);
} else {
    header('Location: verContactos.php');
}