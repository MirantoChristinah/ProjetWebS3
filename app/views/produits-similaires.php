<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Produits similaires</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php require 'header.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">Produits similaires</h2>

    <div class="row g-4">

        <?php if (!empty($produits)) { ?>

            <?php foreach ($produits as $produit) { ?>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">

                        <!-- Image -->
                        <img src="/images/<?= htmlspecialchars($produit['image']) ?>" 
                             class="card-img-top" 
                             style="height:200px; object-fit:cover;">

                        <div class="card-body d-flex flex-column">

                            <!-- Nom -->
                            <h5 class="card-title">
                                <?= htmlspecialchars($produit['nom']) ?>
                            </h5>

                            <!-- Description -->
                            <p class="card-text">
                                <?= htmlspecialchars($produit['description']) ?>
                            </p>

                            <!-- Prix -->
                            <p class="fw-bold">
                                <?= number_format($produit['prix'], 2) ?> €
                            </p>

                            <!-- Catégorie -->
                            <p>
                                <i class="bi bi-tags"></i>
                                <?= htmlspecialchars($produit['categorie']['nom'] ?? '') ?>
                            </p>

                            <!-- Différence en % -->
                            <p class="mt-2">
                                <?php 
                                    $diff = $produit['difference_pourcentage'];
                                    if ($diff > 0) {
                                        echo "<span class='text-success'>+" . $diff . "%</span>";
                                    } elseif ($diff < 0) {
                                        echo "<span class='text-danger'>" . $diff . "%</span>";
                                    } else {
                                        echo "<span class='text-secondary'>0%</span>";
                                    }
                                ?>
                            </p>

                            <!-- Bouton échanger -->
                            <div class="mt-auto">
                                <a href="/echanger/<?= $produit['id'] ?>" 
                                   class="btn btn-primary w-100">
                                   Échanger
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="col-12">
                <div class="alert alert-warning">
                    Aucun produit trouvé dans cette fourchette de prix.
                </div>
            </div>

        <?php } ?>

    </div>
</div>

<?php require 'footer.php'; ?>

</body>
</html>
