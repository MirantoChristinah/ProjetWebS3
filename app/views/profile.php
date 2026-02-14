<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?= htmlspecialchars($user['username']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php require 'header.php'; ?>

    <div class="container py-5">
        <!-- En-tête du profil -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <img src="/images/<?= htmlspecialchars($user['photo'] ?? 'default.png') ?>" 
                             alt="Photo de <?= htmlspecialchars($user['username']) ?>"
                             class="rounded-circle mb-3"
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #9b203f;">
                        <h1 class="mb-2"><?= htmlspecialchars($user['username']) ?></h1>
                        <p class="text-muted mb-0">
                            <i class="bi bi-envelope"></i> 
                            <?= htmlspecialchars($user['email']) ?>
                        </p>
                        <p class="mt-2 text-muted">
                            <i class="bi bi-box-seam"></i> 
                            <?= count($produits) ?> produit<?= count($produits) > 1 ? 's' : '' ?> disponible<?= count($produits) > 1 ? 's' : '' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits de l'utilisateur -->
        <div class="row mb-3">
            <div class="col-12">
                <h2 class="mb-4">
                    <i class="bi bi-shop"></i> 
                    Produits de <?= htmlspecialchars($user['username']) ?>
                </h2>
            </div>
        </div>

        <?php if (empty($produits)) { ?>
            <div class="alert alert-info text-center p-5">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <h3 class="mt-3">Aucun produit disponible</h3>
                <p class="mb-0">Cet utilisateur n'a pas encore ajouté de produits.</p>
            </div>
        <?php } else { ?>
            <div class="row g-4">
                <?php foreach ($produits as $produit) { ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <img src="/images/<?= htmlspecialchars($produit['image'] ?? 'placeholder.png') ?>" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;"
                                 alt="<?= htmlspecialchars($produit['nom']) ?>">
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($produit['nom']) ?></h5>
                                
                                <p class="card-text text-muted small flex-grow-1">
                                    <?= htmlspecialchars($produit['description'] ?? 'Aucune description') ?>
                                </p>
                                
                                <p class="fw-bold text-primary mb-2">
                                    <?= number_format((float)($produit['prix'] ?? 0), 2) ?> $
                                </p>
                                
                                <p class="small text-muted mb-3">
                                    <i class="bi bi-tag"></i>
                                    <?= htmlspecialchars($produit['categorie']['nom'] ?? 'Sans catégorie') ?>
                                </p>
                                
                                <div class="mt-auto">
                                    <a href="/produit/<?= $produit['id'] ?>" 
                                       class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-eye"></i> Voir les détails
                                    </a>
                                </div>
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
