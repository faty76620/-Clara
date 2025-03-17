<?php
$password_plain = "Margesimpson76";
$hash_in_db = "$2y$10$As6f.r9rJMN/AXGYDyKZB.3PHiEIiVwy0LB9NNH.kc9EYvA5ysIPi"; // Hash stocké en base

if (password_verify($password_plain, $hash_in_db)) {
    echo "✅ Mot de passe correct !";
} else {
    echo "❌ Mot de passe incorrect !";
}
?>
