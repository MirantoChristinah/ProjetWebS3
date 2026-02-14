<?php 
if(isset($_SESSION['user'])){
    $userId = $_SESSION['user']['id'];
} else {
    header('Location: /');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Échanges</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php require 'header.php'; ?>

<div class="container my-5">
    <h1 class="mb-4" style="color: var(--primary);">
        <i class="bi bi-arrow-left-right"></i> Mes Échanges
    </h1>
    
    <!-- Filtres -->
    <div class="filters mb-4 d-flex flex-wrap gap-2">
        <button class="filter-btn btn btn-outline-primary active" onclick="filterEchanges('tous')">
            <i class="bi bi-list"></i> Tous
        </button>
        <button class="filter-btn btn btn-outline-warning" onclick="filterEchanges('envoyees')">
            <i class="bi bi-send"></i> Mes demandes envoyées
        </button>
        <button class="filter-btn btn btn-outline-info" onclick="filterEchanges('recues')">
            <i class="bi bi-inbox"></i> Demandes reçues
        </button>
        <button class="filter-btn btn btn-outline-secondary" onclick="filterEchanges('attente')">
            <i class="bi bi-hourglass-split"></i> En attente
        </button>
        <button class="filter-btn btn btn-outline-success" onclick="filterEchanges('accepte')">
            <i class="bi bi-check-circle"></i> Acceptés
        </button>
        <button class="filter-btn btn btn-outline-danger" onclick="filterEchanges('refuse')">
            <i class="bi bi-x-circle"></i> Refusés
        </button>
    </div>
    
    <!-- Messages -->
    <div id="messageContainer"></div>
    
    <!-- Tableau des échanges -->
    <div class="table-responsive">
        <table class="table table-striped table-hover echange-table">
            <thead class="table-red">
                <tr>
                    <th>ID</th>
                    <th>Mon Produit</th>
                    <th>Produit Demandé</th>
                    <th>Demandeur</th>
                    <th>Destinataire</th>
                    <th>Statut</th>
                    <th>Date Envoi</th>
                    <th>Date Réponse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="echangeTable">
                <tr><td colspan="9" class="text-center py-4 text-dark">Chargement...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<?php require 'footer.php'; ?>

<script>
window.ECHANGE_CONFIG = {
    userId: <?= (int)$userId ?>,
    BASE_URL: ''
};
</script>
<script src="/js/echange.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>