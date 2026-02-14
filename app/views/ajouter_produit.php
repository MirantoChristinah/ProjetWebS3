<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php require 'header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-plus-circle"></i> Ajouter un nouveau produit</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success">
                            Produit ajouté avec succès ! <a href="/mes-produits">Voir mes produits</a>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/api/produits" enctype="multipart/form-data" id="formProduit">
                        <!-- Nom du produit -->
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du produit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom" name="nom" 
                                   value="<?= htmlspecialchars($values['nom'] ?? '') ?>" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="4" required><?= htmlspecialchars($values['description'] ?? '') ?></textarea>
                        </div>

                        <!-- Prix -->
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix (€) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="prix" name="prix" 
                                   step="0.01" min="0" value="<?= htmlspecialchars($values['prix'] ?? '') ?>" required>
                        </div>

                        <!-- Catégorie -->
                        <div class="mb-3">
                            <label for="categorie_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-select" id="categorie_id" name="categorie_id" required>
                                <option value="">-- Sélectionnez une catégorie --</option>
                                <?php if (isset($categories) && !empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" 
                                                <?= (isset($values['categorie_id']) && $values['categorie_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Image du produit</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Formats acceptés : JPG, PNG, GIF</small>
                        </div>

                        <!-- Message d'alerte dynamique -->
                        <div id="alertMessage" class="alert d-none"></div>

                        <!-- Boutons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="btnSubmit">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>
                            <a href="/mes-produits" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('formProduit').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const btnSubmit = document.getElementById('btnSubmit');
    const alertMessage = document.getElementById('alertMessage');
    
    // Désactiver le bouton pendant l'envoi
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Envoi en cours...';
    
    fetch('/api/produits', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alertMessage.className = 'alert alert-success';
            alertMessage.textContent = 'Produit ajouté avec succès ! Redirection...';
            alertMessage.classList.remove('d-none');
            
            // Rediriger vers mes-produits après 1.5 secondes
            setTimeout(() => {
                window.location.href = '/mes-produits';
            }, 1500);
        } else {
            alertMessage.className = 'alert alert-danger';
            alertMessage.innerHTML = '<strong>Erreur:</strong><br>' + 
                (data.errors ? data.errors.join('<br>') : data.message || 'Une erreur est survenue');
            alertMessage.classList.remove('d-none');
            
            // Réactiver le bouton
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-check-circle"></i> Enregistrer';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alertMessage.className = 'alert alert-danger';
        alertMessage.textContent = 'Erreur de connexion au serveur';
        alertMessage.classList.remove('d-none');
        
        // Réactiver le bouton
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="bi bi-check-circle"></i> Enregistrer';
    });
});
</script>
</body>
</html>
