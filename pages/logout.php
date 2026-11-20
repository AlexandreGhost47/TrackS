//page de deconnexion

<?php
session_start();
session_destroy();
header('Location: login.php');
exit;