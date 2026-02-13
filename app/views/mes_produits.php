<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes objets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php require 'header.php'; ?>

    <div class="container my-products-container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="my-products-title mb-1">Mes objets</h1>
                <p class="my-products-subtitle mb-0">Retrouvez ici tous les articles que vous avez ajoutés.</p>
            </div>
            <a href="/produits" class="btn btn-outline-primary my-products-back">
                <i class="bi bi-arrow-left"></i>
                Retour aux échanges
            </a>
        </div>

        <?php if (empty($produits)) { ?>
            <div class="my-products-empty text-center p-5">
                <i class="bi bi-box-seam my-products-empty-icon"></i>
                <h2 class="mt-3">Aucun objet publié</h2>
                <p class="mb-0">Ajoutez votre premier objet pour le proposer à l'échange.</p>
            </div>
        <?php } else { ?>
            <div class="row g-4">
                <?php foreach ($produits as $produit) { ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="product-card my-products-card h-100 p-3">
                            <img src="/images/<?= htmlspecialchars($produit['image'] ?? 'placeholder.png') ?>" class="product-image" alt="Image du produit">

                            <div class="mt-3">
                                <h3 class="product-name mb-1"><?= htmlspecialchars($produit['nom']) ?></h3>
                                <p class="product-category mb-1">
                                    <i class="bi bi-tag"></i>
                                    <?= htmlspecialchars($produit['categorie']['nom'] ?? 'Sans catégorie') ?>
                                </p>
                                <p class="product-price mb-2"><?= number_format((float)($produit['prix'] ?? 0), 2) ?> $</p>
                                <p class="product-description mb-0"><?= htmlspecialchars($produit['description'] ?? 'Aucune description') ?></p>
                            </div>
                            <a href="/produit/<?= $produit['id'] ?>/similaires/10" 
                                class="btn btn-outline-secondary btn-sm">
                                ±10%
                            </a>
                            <a href="/produit/<?= $produit['id'] ?>/similaires/20" 
                                class="btn btn-outline-secondary btn-sm">
                                ±20%
                            </a>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <span class="badge text-bg-light my-products-badge">
                                    <i class="bi bi-people"></i>
                                    Visible par vos contacts
                                </span>
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
