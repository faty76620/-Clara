<?php
session_start();
require_once '../../models/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($username && $password) {
        $user = getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];



            // REDIRECTION SELON LE ROLE
            if ($user['role_id'] == 1) { // Admin
                header("Location: /clara/views/admin/dashboard.php");

            } else {
                header("Location: /clara/views/dashboard.php");
            }

            exit();

        } else {
            $_SESSION['error'] = "Identifiant ou mot de passe incorrect.";
        }

    } else {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
    }
}

header("Location: /clara/views/login.php");
exit();