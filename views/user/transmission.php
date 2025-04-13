<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/logo-onglet.png">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;800&family=Raleway:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="<?= BASE_URL ?>/assets/js.js"></script>
    <title>Transmission</title>
</head>
<body> 
    <?php include TEMPLATE_DIR . '/header_user.php'; ?>

    <main class="dashboard">
        <div class="container-title"><h2>Transmission</h2></div>
        <form class="form-session">
            <div class="group-form">
                <label for="cible">Besoin cyblé:</label>
                <select name="cible" id="cible" required multiple>
                    <option value="">-- Sélectionnez des besoins --</option>
                    <option value="respirer">Respirer</option>
                    <option value="boire_et_manger">Boire et manger</option>
                    <option value="éliminer">Éliminer</option>
                    <option value="se_mouvoir">Se mouvoir et maintenir une bonne posture</option>
                    <option value="dormir">Dormir et se reposer</option>
                    <option value="s_habiller">Se vêtir et se dévêtir</option>
                    <option value="maintenir_température">Maintenir la température du corps</option>
                    <option value="être_propre">Être propre et protéger ses téguments</option>
                    <option value="éviter_dangers">Éviter les dangers</option>
                    <option value="communiquer">Communiquer avec ses semblables</option>
                    <option value="agir_selon_valeurs">Agir selon ses croyances et valeurs</option>
                    <option value="s_occuper">S’occuper en vue de se réaliser</option>
                    <option value="se_recreer">Se récréer</option>
                    <option value="apprendre">Apprendre</option>
                </select>
            </div>
            <div class="group-form">
                <label>Description :</label>
                <textarea name="transmission_description"></textarea>
            </div>
            <div class="group-form">
                <input type="hidden" name="transmitted_by" value="<?php echo $_SESSION['user_id']; ?>">
            </div>
            <div class="btn-container">
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </section>
</main>
</body>
</html>