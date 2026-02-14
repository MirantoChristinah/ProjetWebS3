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
                    <div class="mt-3 d-flex align-items-center gap-2">
                        <img src="/images/<?= htmlspecialchars($produit_detail['proprietaire_photo'] ?? $produit_detail['user']['photo'] ?? 'default.png') ?>" 
                             alt="Photo propriétaire"
                             class="rounded-circle"
                             style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #9b203f;">
                        <div>
                            <small class="text-muted d-block">Propriétaire</small>
                            <a href="/profil/<?= $produit_detail['user_id'] ?>" 
                               class="text-decoration-none fw-bold fs-5">
                                <i class="bi bi-person-circle"></i>
                                <?= htmlspecialchars($produit_detail['proprietaire_nom'] ?? $produit_detail['user']['username'] ?? 'Utilisateur') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Proposer un échange -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] != $produit_detail['user_id'] && !empty($mesProduits)): ?>
        <div class="container my-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-arrow-left-right"></i> Proposer un échange</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Sélectionnez un de vos produits pour proposer un échange avec <strong><?= htmlspecialchars($produit_detail['nom']) ?></strong></p>
                    
                    <div class="row g-3">
                        <?php foreach ($mesProduits as $monProduit): ?>
                            <?php
                            $echange = $monProduit['echange'] ?? null;
                            $statusId = $echange ? $echange['status_id'] : 0;
                            ?>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <img src="/images/<?= htmlspecialchars($monProduit['image']) ?>" 
                                         class="card-img-top" style="height: 150px; object-fit: cover;" 
                                         alt="<?= htmlspecialchars($monProduit['nom']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($monProduit['nom']) ?></h5>
                                        <p class="card-text text-muted small">
                                            <i class="bi bi-tag"></i> <?= htmlspecialchars($monProduit['categorie']['nom']) ?><br>
                                            <strong><?= number_format($monProduit['prix'], 2) ?> €</strong>
                                        </p>
                                        
                                        <?php if ($statusId == 1): ?>
                                            <!-- En attente -->
                                            <button class="btn btn-warning btn-sm w-100 mb-1" disabled>
                                                <i class="bi bi-hourglass-split"></i> Demande envoyée
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm w-100" 
                                                    onclick="annulerEchange(<?= $echange['id'] ?>)">
                                                <i class="bi bi-x-circle"></i> Annuler
                                            </button>
                                        <?php elseif ($statusId == 2 || $statusId == 0): ?>
                                            <!-- Refusé ou pas d'échange -->
                                            <form method="POST" action="/api/echanges" class="form-echange-produit">
                                                <input type="hidden" name="produit1_id" value="<?= $monProduit['id'] ?>">
                                                <input type="hidden" name="produit2_id" value="<?= $produit_detail['id'] ?>">
                                                <input type="hidden" name="user1_id" value="<?= $_SESSION['user']['id'] ?>">
                                                <input type="hidden" name="user2_id" value="<?= $produit_detail['user_id'] ?>">
                                                <input type="hidden" name="status_id" value="1">
                                                
                                                <button type="submit" class="btn btn-primary btn-sm w-100 btn-proposer-echange">
                                                    <i class="bi bi-arrow-left-right"></i> Proposer l'échange
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

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
    
    <script>
    // Gérer les formulaires d'échange
    document.querySelectorAll('.form-echange-produit').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const btn = this.querySelector('.btn-proposer-echange');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Envoi...';
            
            fetch("/api/echanges", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.outerHTML = `
                        <button class="btn btn-warning btn-sm w-100 mb-1" disabled>
                            <i class="bi bi-hourglass-split"></i> Demande envoyée
                        </button>
                        <button class="btn btn-outline-danger btn-sm w-100" onclick="location.reload()">
                            <i class="bi bi-x-circle"></i> Annuler
                        </button>
                    `;
                } else {
                    alert('Erreur lors de l\'envoi de la demande');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'envoi de la demande');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    });
    
    // Fonction pour annuler un échange
    function annulerEchange(echangeId) {
        if (!confirm('Voulez-vous vraiment annuler cette demande d\'échange ?')) {
            return;
        }
        
        fetch(`/api/echanges/${echangeId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de l\'annulation');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'annulation');
        });
    }
    </script>
</body>
</html>