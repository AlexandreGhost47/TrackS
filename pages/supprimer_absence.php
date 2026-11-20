<?php
require '../includes/auth.php';
require '../config/db.php';

// On n'accepte que l'envoi d'un formulaire (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM absences WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header('Location: absences.php?supprime=1');
    exit;
}

header('Location: absences.php');
exit;