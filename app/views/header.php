<?php 
if(isset($_SESSION['user'])){
    $nom = $_SESSION['user']['username'];
    $isAdmin = ($nom === 'admin'); // Vérifier si l'utilisateur est admin
} else {
    $nom = "Guest";
    $isAdmin = false;
}
?>
<header>
<link rel="stylesheet" href="/css/style.css">


    <div class="site-logo">Takalo - Takalo</div>

    <div style="display:flex; align-items:center; gap:20px;">
        <div class="nav-links">
            <a href="/nouveau-produit" class="nouveau-link"><i class="bi bi-plus-circle"></i> Nouveau</a>
            <?php if ($isAdmin): ?>
                <a href="/categories">Catégories</a>
                <a href="/admin/statistiques">Statistiques</a>
                <a href="/echanges">Liste échanges</a>
            <?php endif; ?>
            <?php if(isset($_SESSION['user'])){ ?>
            <a href="/echange_user/<?php echo $_SESSION['user']['id']; ?>">Mes échanges</a>
            <?php }?>
            <a href="/produits">Home</a>
            <a href="/recherche">Recherche</a>
            <a href="/mes-produits">Mes objets</a>
        </div>

        <div class="user-dropdown">
            <button><i class="bi bi-person-fill user-icon"></i><?php echo $nom?></button>
            <div class="user-dropdown-content">
                <a href="/logout"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
            </div>
        </div>
    </div>
</header>
