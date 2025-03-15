<?php
session_start();
session_destroy();
header("Location: /clara/views/auth/login.php?success=Déconnexion réussie");
exit();
?>
