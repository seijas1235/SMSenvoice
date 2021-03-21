<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login/login.php');
}

require '../../partials/db.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $sql = 'DELETE FROM lista_contactos WHERE id_tabla=:id AND usuario=:user_id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    header('Location: verListas.php');
} else {
    header('Location: verListas.php');
}