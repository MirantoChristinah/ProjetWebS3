<?php 
if(isset($_SESSION['user'])){
    $nom = $_SESSION['user']['username'] ; 
} else {
    $nom = "Guest" ; 
}
//$nom = $_SESSION['user']['username'] ; 
?>
<header>
<link rel="stylesheet" href="/css/style.css">


    <div class="site-logo">Takalo - Takalo</div>

    <div style="display:flex; align-items:center; gap:20px;">
        <div class="nav-links">
            <a href="./insert">nouveau</a>
            <a href="./echanges">Liste echanges </a>
            <?php if(isset($_SESSION['user'])){ ?>
            <a href="./echange_user/<?php echo $_SESSION['user']['id']; ?>">Mes echanges </a>
            <?php }?>
            <a href="/produits">Home</a>
            <a href="/recherche">Recherche</a>
            <a href="/mes-produits">Mes objets</a>
            <a href="/admin/statistiques">Statistiques</a>
        </div>

        <div class="user-dropdown">
            <button><i class="bi bi-person-fill user-icon"></i><?php echo $nom?></button>
            <div class="user-dropdown-content">
                <a href="#">Log Out</a>
            </div>
        </div>
    </div>
</header>
