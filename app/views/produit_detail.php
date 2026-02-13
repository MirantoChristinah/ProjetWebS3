<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produit Details</title>
    <link rel="stylesheet" href="/css/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons (pour l’icône <i>) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
    <?php 
        require 'header.php';
    ?>
        <div class="container my-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <img
                        src="/images/<?= htmlspecialchars($produit_detail['image']) ?>"
                        class="product-image"
                    />
                </div>
                <div class="col-md-6">
                    <div class="mt-2 product-name fs-2">
                            <?= htmlspecialchars($produit_detail['nom']) ?>
                    </div>
                    <div class="mt-2 product-desc fs-4">
                            <?= htmlspecialchars($produit_detail['description']) ?>
                    </div>
                    <div class="mt-2 fs-4">
                            Price: 
                            <?= number_format($produit_detail['prix'], 2) ?> $
                    </div>
                    <div class="mt-2 fs-4">
                        Category: 
                            <i class="bi bi-tags "></i>
                            <?= htmlspecialchars($produit_detail['categorie']['nom']) ?>
                    </div>
                    <div class="mt-2 fs-4">
                        Owner: 
                        <i class="bi bi-person"></i>
                        <?= htmlspecialchars($produit_detail['user']['username']) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php $historique = $historique ?? []; ?>
        <div class="container product-history-container mb-5">
            <h2 class="history-title mb-4">Historique des propriétaires</h2>

            <?php if (empty($historique)) { ?>
                <div class="history-empty text-center p-5">
                    <i class="bi bi-clock-history history-empty-icon"></i>
                    <h3 class="mt-3">Aucun échange enregistré</h3>
                    <p class="mb-0">Cet objet n'a pas encore changé de propriétaire via la plateforme.</p>
                </div>
            <?php } else { ?>
                <div class="history-list">
                    <?php foreach ($historique as $echange) {
                        $dateBrute = $echange['date_acceptation'] ?: $echange['date_envoie'];
                        $dateFormatee = $dateBrute ? date('d/m/Y H:i', strtotime($dateBrute)) : 'Date inconnue';
                    ?>
                        <div class="history-item p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="history-date">
                                    <i class="bi bi-calendar-event"></i>
                                    <?= htmlspecialchars($dateFormatee) ?>
                                </div>
                                <div class="history-status badge bg-success-subtle text-success">
                                    <?= htmlspecialchars($echange['etat']) ?>
                                </div>
                            </div>
                            <div class="history-owners mt-3">
                                <span class="history-owner-from">
                                    <i class="bi bi-person-circle"></i>
                                    <?= htmlspecialchars($echange['ancien_proprietaire']) ?>
                                </span>
                                <i class="bi bi-arrow-right history-arrow"></i>
                                <span class="history-owner-to">
                                    <i class="bi bi-person-fill"></i>
                                    <?= htmlspecialchars($echange['nouveau_proprietaire']) ?>
                                </span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

    <?php 
        require 'footer.php';
    ?>
</body>
</html>