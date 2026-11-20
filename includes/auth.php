<!--Il vérifie que la personne est connectée. Sinon, il la renvoie vers la page de connexion. On l’ajoutera en haut de chaque page privée. Sans lui, quelqu’un pourrait taper directement l’adresse de dashboard.php et entrer sans mot de passe. -->

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

