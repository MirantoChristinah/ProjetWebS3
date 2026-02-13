<aside>
<link rel="stylesheet" href="/css/style.css">

    <h3>Categories</h3>
    <ul>
        <?php foreach ($categories as $categorie) : ?>
            <li><a href="/produitCategories/<?= $categorie['id'] ?>"><?= htmlspecialchars($categorie['nom']) ?></a></li>
        <?php endforeach; ?>
    </ul>
</aside>
