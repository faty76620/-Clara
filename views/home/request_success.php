<!DOCTYPE html>
<html lang="en">
<?php 
    $title = "Clara"; 
    include __DIR__ . '/../../templates/head.php'; ?>   
<body>
    <?php 
    include __DIR__ . '/../../templates/header.php'; ?>
    <main class="main">
        <div class="flex-container">
            <div class="success-message">
                <h2>Votre demande d'inscription a bien été prise en compte.</h2>
                <a href="/clara/views/home/home.php">Retour à la page d'accueil</a>
            </div>
        </div>
    </main>
    <footer>
        <?php include __DIR__ . '/../../templates/footer.php';?>
    </footer>   
</body>
</html>