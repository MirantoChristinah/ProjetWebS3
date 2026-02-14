<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php require 'header.php'; ?>

    <div class="container search-container py-5">
        <div class="search-header text-center mb-5">
            <h1 class="search-title mb-2">Trouver un objet</h1>
            <p class="search-subtitle mb-0">Filtrez par mot-clé et catégorie pour dénicher l'article parfait.</p>
        </div>

        <form class="search-form mb-5" method="get" action="/recherche">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="q" class="form-label">Mot-clé</label>
                    <input
                        type="text"
                        id="q"
                        name="q"
                        class="form-control"
                        placeholder="Ex: guitare, vélo..."
                        value="<?= htmlspecialchars($motCle ?? '') ?>"
                    >
                </div>
                <div class="col-md-4">
                    <label for="categorie" class="form-label">Catégorie</label>
                    <select id="categorie" name="categorie" class="form-select">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $categorie) { ?>
                            <option
                                value="<?= (int) $categorie['id'] ?>"
                                <?= ($categorieActive ?? null) == $categorie['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($categorie['nom']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary search-submit">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>
                </div>
            </div>
        </form>

        <?php if (empty($produits)) { ?>
            <div class="search-empty text-center p-5">
                <i class="bi bi-search search-empty-icon"></i>
                <h2 class="mt-3">Aucun résultat</h2>
                <p class="mb-0">Essayez d'autres mots-clés ou élargissez votre recherche.</p>
            </div>
        <?php } else { ?>
            <div class="row g-4">
                <?php foreach ($produits as $produit) { ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="product-card search-card h-100 p-3 text-center">
                            <img
                                src="/images/<?= htmlspecialchars($produit['image'] ?? 'placeholder.png') ?>"
                                class="product-image"
                                alt="Image du produit"
                            >
                            <div class="mt-3">
                                <h3 class="product-name mb-1"><?= htmlspecialchars($produit['nom']) ?></h3>
                                <p class="product-category mb-1">
                                    <i class="bi bi-tag"></i>
                                    <?= htmlspecialchars($produit['categorie']['nom'] ?? 'Sans catégorie') ?>
                                </p>
                                <p class="product-price mb-2"><?= number_format((float)($produit['prix'] ?? 0), 2) ?> $</p>
                                
                                <!-- Propriétaire -->
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <img src="/images/<?= htmlspecialchars($produit['proprietaire_photo'] ?? $produit['user']['photo'] ?? 'default.png') ?>" 
                                         alt="Photo propriétaire"
                                         class="rounded-circle"
                                         style="width: 30px; height: 30px; object-fit: cover;">
                                    <a href="/profil/<?= $produit['user_id'] ?>" 
                                       class="text-decoration-none fw-bold text-primary small">
                                        <i class="bi bi-person"></i>
                                        <?= htmlspecialchars($produit['proprietaire_nom'] ?? $produit['user']['username'] ?? 'Utilisateur') ?>
                                    </a>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="/produit/<?= $produit['id'] ?>" class="btn btn-details">
                                    Voir la fiche
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php require 'footer.php'; ?>
</body>
</html>
