<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login/login.php');
} else if (isset($_SESSION['user_id'])){
    header('Location: app/listas-contactos/listasContactos.php');
}