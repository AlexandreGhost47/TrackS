<?php
session_start();

// Connecté : direction le tableau de bord. Sinon : page de connexion.
if (isset($_SESSION['user_id'])) {
    header('Location: pages/dashboard.php');
} else {
    header('Location: pages/login.php');
}
exit;