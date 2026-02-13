<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errors = $errors ?? [];
$values = $values ?? [];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">

</head>
<body>
    <?php 
        require 'header.php';
    ?>
<main class="form-card" role="main">
    <h1>Connexion</h1>
    <p>Accédez à votre espace en renseignant vos identifiants.</p>

    <!-- Zone d'affichage globale -->
    <div id="formStatus" class="alert d-none" role="alert">
        <?php if (!empty($errors['_global'])) echo htmlspecialchars($errors['_global']); ?>
    </div>

    <form id="loginForm" method="post" action="/logForm" novalidate>
        <!-- Username -->
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" placeholder="Votre identifiant" autocomplete="username"
               class="form-control <?php echo !empty($errors['username']) ? 'is-invalid' : (!empty($values['username']) ? 'is-valid' : ''); ?>"
               value="<?php echo htmlspecialchars($values['username'] ?? ''); ?>" required>
        <div id="usernameError" class="text-danger"><?php echo $errors['username'] ?? ''; ?></div>

        <!-- Email -->

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="prenom.nom@exemple.com" autocomplete="email"
               class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : (!empty($values['email']) ? 'is-valid' : ''); ?>"
               value="<?php echo htmlspecialchars($values['email'] ?? ''); ?>" required>
        <div id="emailError" class="text-danger"><?php echo $errors['email'] ?? ''; ?></div>

        <!-- Password -->
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password"
               class="form-control <?php echo !empty($errors['password']) ? 'is-invalid' : (!empty($values['password']) ? 'is-valid' : ''); ?>"
               value="<?php echo htmlspecialchars($values['password'] ?? ''); ?>" required>
        <div id="passwordError" class="text-danger"><?php echo $errors['password'] ?? ''; ?></div>

        <button type="submit">Se connecter</button>
    </form>
    <div class="helper">
        <span>Pas encore de compte ? <a href="/register">Inscrivez-vous</a></span>
    </div>
</main>

<script src="/js/login.js"></script>
</body>
</html>
