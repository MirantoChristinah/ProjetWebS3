<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php require 'header.php'; ?>

    <main class="container py-5 admin-stats-container">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="admin-stats-title mb-1">Tableau de bord</h1>
                <p class="admin-stats-subtitle mb-0">Suivez l'activité de la plateforme en un coup d'œil.</p>
            </div>
            <a href="/produits" class="btn btn-outline-primary admin-stats-back">
                <i class="bi bi-arrow-left"></i>
                Retour à l'accueil
            </a>
        </div>

        <section class="row g-4">
            <div class="col-md-6">
                <div class="admin-stats-card h-100">
                    <div class="admin-stats-icon bg-primary-subtle text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="admin-stats-info">
                        <p class="admin-stats-label mb-1">Utilisateurs inscrits</p>
                        <p class="admin-stats-value mb-0"><?= (int) ($totalUsers ?? 0) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="admin-stats-card h-100">
                    <div class="admin-stats-icon bg-success-subtle text-success">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="admin-stats-info">
                        <p class="admin-stats-label mb-1">Échanges effectués</p>
                        <p class="admin-stats-value mb-0"><?= (int) ($totalEchanges ?? 0) ?></p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require 'footer.php'; ?>
</body>
</html>
