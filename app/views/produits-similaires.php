<?php 
    $idProduit = $_SESSION['produit_id'] ?? null;
    $id_userproprietaire = $_SESSION['user'];
?>
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

                            <!-- Propriétaire -->
                            <div class="mb-2 d-flex align-items-center gap-2">
                                <img src="/images/<?= htmlspecialchars($produit['proprietaire_photo'] ?? 'default.png') ?>" 
                                     alt="Photo <?= htmlspecialchars($produit['proprietaire_nom'] ?? 'Utilisateur') ?>"
                                     class="rounded-circle"
                                     style="width: 30px; height: 30px; object-fit: cover;">
                                <small class="text-muted">
                                    Propriétaire : 
                                    <a href="/profil/<?= $produit['user_id'] ?>" class="text-decoration-none fw-bold">
                                        <?= htmlspecialchars($produit['proprietaire_nom'] ?? 'Utilisateur') ?>
                                    </a>
                                </small>
                            </div>

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
                                <?php
                                $echange = $produit['echange'] ?? null;
                                $statusId = $echange ? $echange['status_id'] : 0;
                                $etat = $echange ? $echange['etat'] : '';
                                
                                if ($statusId == 1) {
                                    // En attente
                                    ?>
                                    <button class="btn btn-warning w-100" disabled>
                                        <i class="bi bi-hourglass-split"></i> Demande envoyée
                                    </button>
                                    <?php
                                } elseif ($statusId == 2 || $statusId == 0) {
                                    // Refusé ou pas d'échange
                                    ?>
                                    <form method="POST" action="/api/echanges" class="form-echange">
                                        <input type="hidden" name="produit1_id" value="<?= $produit['id'] ?>">
                                        <input type="hidden" name="produit2_id" value="<?= $idProduit ?>">
                                        <input type="hidden" name="user1_id" value="<?= $id_userproprietaire['id'] ?>">
                                        <input type="hidden" name="user2_id" value="<?= $produit['user_id'] ?>">
                                        <input type="hidden" name="status_id" value="1">

                                        <button type="submit" class="btn btn-primary w-100 btn-echange">
                                            <i class="bi bi-arrow-left-right"></i> Échanger
                                        </button>
                                    </form>
                                    <?php
                                }
                                // Si accepté (status_id = 3), le produit ne s'affiche pas du tout
                                ?>
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

<script>
// Gérer tous les formulaires d'échange
document.querySelectorAll('.form-echange').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const formData = new FormData(this);
        const btn = this.querySelector('.btn-echange');
        
        // Désactiver le bouton immédiatement
        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass"></i> Envoi...';
        
        fetch("/api/echanges", {
            method: "POST",
            headers: {
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Demande envoyée';
                btn.classList.remove("btn-primary");
                btn.classList.add("btn-warning");
                // Recharger la page pour voir le nouveau statut
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                alert('Erreur lors de l\'envoi de la demande');
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'envoi de la demande');
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    });
});
</script>

</body>
</html>
