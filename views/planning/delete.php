<?php
$id = $_GET['id'];
deletePlanning($conn, $id);
$_SESSION['success'] = "Créneau supprimé";
header('Location: index.php');
exit;
?>