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
    <title>Inscription</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">

</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Créer un compte</h3>
                    <!-- Message global -->
                    <div id="formStatus" class="alert d-none"></div>

                    <form id="registerForm" method="POST">

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
                        <!-- Confirmation -->
                        <label for="password">Confirmer mot de passe </label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" autocomplete="current-password"
                            class="form-control <?php echo !empty($errors['confirm_password']) ? 'is-invalid' : (!empty($values['confirm_password']) ? 'is-valid' : ''); ?>"
                            value="<?php echo htmlspecialchars($values['confirm_password'] ?? ''); ?>" required>
                        <div id="confirmPasswordError" class="text-danger"><?php echo $errors['confirm_password'] ?? ''; ?></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            S'inscrire
                        </button>
                        <div class="text-center mt-3">
                            <a href="/login">Déjà un compte ? Se connecter</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/register.js"></script>

</body>
</html>
