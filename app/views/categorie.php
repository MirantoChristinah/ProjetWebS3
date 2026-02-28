<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories - Takalo</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php require 'header.php'; ?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="text-center" style="color: var(--primary);">
                <i class="bi bi-grid-3x3-gap"></i> Gestion des Catégories
            </h1>
            <p class="text-center text-muted">Gérez vos catégories de produits</p>
        </div>
    </div>

    <!-- Grille des catégories publiques -->
    <div class="row mb-5">
        <div class="col">
            <h3 class="mb-3"><i class="bi bi-list-ul"></i> Catégories disponibles</h3>
            <?php if (empty($categories)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucune catégorie disponible.
                </div>
            <?php else: ?>
                <div class="row g-3" id="publicCategoryGrid">
                    <?php foreach($categories as $cat): ?>
                        <div class="col-md-3 col-sm-6">
                            <a href="/produitCategories/<?= htmlspecialchars($cat['id']) ?>" class="text-decoration-none">
                                <div class="card category-card h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <?php if (!empty($cat['icon'])): ?>
                                            <img src="/images/<?= htmlspecialchars($cat['icon']) ?>" 
                                                 alt="<?= htmlspecialchars($cat['nom']) ?>"
                                                 class="category-icon mb-2">
                                        <?php else: ?>
                                            <div class="category-icon-fallback mb-2">
                                                <?= strtoupper(substr($cat['nom'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <h5 class="card-title"><?= htmlspecialchars($cat['nom']) ?></h5>
                                        <p class="card-text text-muted small">Voir les produits</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Section d'administration CRUD -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header text-white" style="background-color: #9b203f;">
                    <h4 class="mb-0"><i class="bi bi-gear"></i> Administration des catégories</h4>
                </div>
                <div class="card-body">
                    <!-- Formulaire d'ajout/modification -->
                    <form id="categorieForm" class="mb-4">
                        <input type="hidden" id="catId" value="">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="catNom" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="catNom" name="nom" required 
                                       placeholder="Ex: Électronique">
                            </div>
                            <div class="col-md-5">
                                <label for="catIcon" class="form-label">Icône (nom du fichier)</label>
                                <input type="text" class="form-control" id="catIcon" name="icon" 
                                       placeholder="Ex: icon.png">
                                <small class="text-muted">Fichier dans /public/images/</small>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" id="saveCat" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-circle"></i> Ajouter
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="button" id="cancelEdit" class="btn btn-secondary" style="display:none">
                                <i class="bi bi-x-circle"></i> Annuler
                            </button>
                        </div>
                    </form>

                    <!-- Tableau des catégories -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover categorie-table" id="catTable">
                            <thead class="table-red">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Icône</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="catTableBody">
                                <!-- Rempli par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Configuration pour les scripts
const BASE_URL = '';

// Récupérer et afficher les catégories dans le tableau
function loadCategories() {
    fetch('/api/categories')
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('catTableBody');
            tbody.innerHTML = '';
            
            data.forEach(cat => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="text-dark fw-bold">${cat.id}</td>
                    <td class="text-dark fw-bold">${cat.nom}</td>
                    <td class="text-dark">${cat.icon || '<em class="text-muted">Aucune</em>'}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-sm btn-warning" onclick="editCategory(${cat.id}, '${cat.nom}', '${cat.icon || ''}')">
                                <i class="bi bi-pencil"></i> Modifier
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteCategory(${cat.id})">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(err => console.error('Erreur:', err));
}

// Ajouter/Modifier une catégorie
document.getElementById('categorieForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const catId = document.getElementById('catId').value;
    const nom = document.getElementById('catNom').value;
    const icon = document.getElementById('catIcon').value;
    
    const method = catId ? 'PUT' : 'POST';
    const url = catId ? `/api/categories/${catId}` : '/api/categories';
    
    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nom, icon })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(catId ? 'Catégorie modifiée !' : 'Catégorie ajoutée !');
            document.getElementById('categorieForm').reset();
            document.getElementById('catId').value = '';
            document.getElementById('saveCat').innerHTML = '<i class="bi bi-plus-circle"></i> Ajouter';
            document.getElementById('cancelEdit').style.display = 'none';
            loadCategories();
            location.reload(); // Recharger pour mettre à jour la grille publique
        }
    })
    .catch(err => console.error('Erreur:', err));
});

// Éditer une catégorie
function editCategory(id, nom, icon) {
    document.getElementById('catId').value = id;
    document.getElementById('catNom').value = nom;
    document.getElementById('catIcon').value = icon;
    document.getElementById('saveCat').innerHTML = '<i class="bi bi-check-circle"></i> Modifier';
    document.getElementById('cancelEdit').style.display = 'inline-block';
}

// Annuler l'édition
document.getElementById('cancelEdit').addEventListener('click', function() {
    document.getElementById('categorieForm').reset();
    document.getElementById('catId').value = '';
    document.getElementById('saveCat').innerHTML = '<i class="bi bi-plus-circle"></i> Ajouter';
    this.style.display = 'none';
});

// Supprimer une catégorie
function deleteCategory(id) {
    if (!confirm('Voulez-vous vraiment supprimer cette catégorie ?')) return;
    
    fetch(`/api/categories/${id}`, {
        method: 'DELETE'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Catégorie supprimée !');
            loadCategories();
            location.reload();
        }
    })
    .catch(err => console.error('Erreur:', err));
}

// Charger les catégories au chargement de la page
loadCategories();
</script>

</body>
</html>

