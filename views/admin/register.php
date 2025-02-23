<!DOCTYPE html>
<html lang="en">
<?php 
    $title = "Inscription"; 
    include __DIR__ . '/../../templates/head.php'; ?>
<body>
    <h1>Inscription d'un nouvel utilisateur</h1>
    <form action="../controllers/adminController.php?action=register_user" method="post">
        <input type="hidden" name="establishment_id" value="<?php echo $request['establishment_id']; ?>">
        <input type="hidden" name="role_id" value="2"> <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" value="<?php echo $request['mail_admin']; ?>" required><br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?php echo $request['mail_admin']; ?>" required><br>
        <button type="submit">Inscrire</button>
    </form>
</body>
</html>

