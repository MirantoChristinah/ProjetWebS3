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
                            <div class="d-flex gap-2 mt-3">
                                <a href="/produit/<?= $produit['id'] ?>/similaires/10" 
                                    class="btn btn-warning btn-sm fw-bold">
                                    <i class="bi bi-search"></i> ±10%
                                </a>
                                <a href="/produit/<?= $produit['id'] ?>/similaires/20" 
                                    class="btn btn-info btn-sm fw-bold text-white">
                                    <i class="bi bi-search"></i> ±20%
                                </a>
                            </div>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <span class="badge text-bg-light my-products-badge">
                                    <i class="bi bi-people"></i>
                                    Visible par vos contacts
                                </span>
                                <a href="/produit/<?= $produit['id'] ?>" class="btn btn-details">
                                    Voir la fiche
                                </a>
                            </div>
                            
                            <!-- Demandes d'\u00e9change en attente -->
                            <?php if (!empty($produit['echanges_attente'])): ?>
                                <div class="mt-3 pt-3 border-top">
                                    <h6 class="text-warning mb-2">
                                        <i class="bi bi-hourglass-split"></i> 
                                        <?= count($produit['echanges_attente']) ?> demande(s) en attente
                                    </h6>
                                    <?php foreach ($produit['echanges_attente'] as $echange): ?>
                                        <div class="alert alert-warning alert-sm p-2 mb-2">
                                            <small>
                                                <strong><?= htmlspecialchars($echange['autre_user']) ?></strong> 
                                                souhaite \u00e9changer
                                            </small>
                                            <div class="mt-1">
                                                <button type="button" class="btn btn-success btn-sm" 
                                                        onclick="event.preventDefault(); accepterEchange(<?= $echange['id'] ?>)">
                                                    <i class="bi bi-check-circle"></i> Accepter
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="event.preventDefault(); refuserEchange(<?= $echange['id'] ?>)">
                                                    <i class="bi bi-x-circle"></i> Refuser
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php require 'footer.php'; ?>
    
    <script>
    function accepterEchange(echangeId) {
        if (!confirm('Voulez-vous accepter cette demande d\'échange ? Les deux produits seront échangés.')) {
            return;
        }
        
        // Désactiver les boutons pour éviter les doubles clics
        const buttons = document.querySelectorAll('button');
        buttons.forEach(btn => btn.disabled = true);
        
        fetch(`/api/echanges/${echangeId}/status`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status_id: 3 })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Recharger la page pour voir les changements
                window.location.href = '/mes-produits';
            } else {
                alert('Erreur lors de l\'acceptation de l\'échange');
                buttons.forEach(btn => btn.disabled = false);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'acceptation de l\'échange');
            buttons.forEach(btn => btn.disabled = false);
        });
    }
    
    function refuserEchange(echangeId) {
        if (!confirm('Voulez-vous refuser cette demande d\'échange ?')) {
            return;
        }
        
        // Désactiver les boutons pour éviter les doubles clics
        const buttons = document.querySelectorAll('button');
        buttons.forEach(btn => btn.disabled = true);
        
        fetch(`/api/echanges/${echangeId}/status`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status_id: 2 })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Recharger la page pour voir les changements
                window.location.href = '/mes-produits';
            } else {
                alert('Erreur lors du refus de l\'échange');
                buttons.forEach(btn => btn.disabled = false);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du refus de l\'échange');
            buttons.forEach(btn => btn.disabled = false);
        });
    }
    </script>
</body>
</html>
