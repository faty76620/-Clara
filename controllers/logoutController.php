<?php
 include __DIR__ . '/../../templates/session_start.php'; 
session_destroy();
header("Location: /clara/views/auth/login.php");
exit();
?>
