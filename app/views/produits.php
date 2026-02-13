<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Produits</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <!-- Bootstrap Icons (pour l’icône <i>) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

   
</head>

<body>

    <?php
    require 'header.php';
    ?>

    <div class="container-fluid my-5">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3">
            <?php require 'sidebar.php'; ?>
        </div>

        <!-- PRODUITS -->
        <div class="col-md-9">
            <div class="row g-4">

            <?php foreach ($produits as $produit) { ?>
                <div class="col-md-4">
                    <div class="product-card p-3 text-center h-100">

                        <!-- Image du produit -->
                        <img
                            src="/images/<?= htmlspecialchars($produit['image']) ?>"
                            class="product-image"
                        />

                        <!-- Nom -->
                        <div class="mt-2 product-name fs-2">
                            <?= htmlspecialchars($produit['nom']) ?>
                        </div>

                        <!-- Prix -->
                        <div class="mt-2 product-price fs-5">
                            <?= number_format($produit['prix'], 2) ?> $
                        </div>

                        <!-- Categorie -->
                        <div class="mt-2 product-category fs-5">
                            <i class="bi bi-tags "></i>
                            <?= htmlspecialchars($produit['categorie']['nom']) ?>
                        </div>

                        <!-- User -->
                        <div class="mt-2 product-user fs-4">
                            <i class="bi bi-person"></i>
                            <?= htmlspecialchars($produit['user']['username']) ?>
                        </div>

                        <!-- Bouton -->
                        <div class="mt-3">
                            <a href="/produit/<?= $produit['id'] ?>" class="btn btn-details">
                                Plus de détails
                            </a>
                        </div>

                    </div>
                </div>
            <?php } ?>

        </div>
    </div>

    <?php
    require 'footer.php';
    ?>

</body>
</html>
