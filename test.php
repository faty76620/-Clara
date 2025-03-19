<?php
$password = 'Administrateur30';
$hashed_password = '$2y$10$HEP88MHhCKpII7IT2LH0/uDw00PpNbZMssH6ZGl5lkAlQb8wcBx/K'; // Le hachage que tu as en base de données

if (password_verify($password, $hashed_password)) {
    echo "Mot de passe correct.";
} else {
    echo "Mot de passe incorrect.";
}
?>