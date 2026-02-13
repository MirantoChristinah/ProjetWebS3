<?php 
namespace app\config;
use flight\Engine;
use app\controllers\UserController;
use app\controllers\MessageController;
use app\controllers\ProduitController;
use app\controllers\CategorieController;
use app\controllers\EchangeController;

use flight\net\Router;
use Flight; 
/** 
 * @var Router $router 
 * @var Engine $app 
 */
session_start();

Flight::route('/', function () {
    Flight::render('login'); 
});

Flight::route('GET /home', function () {
    Flight::render('home');
}); 
Flight::route('GET /register', function () {
    Flight::render('FormInscription');
});

Flight::route('POST /logForm', function() {

    $controller = new UserController();
    $result = $controller->validate(true);

    $req = Flight::request();
    $isAjax = ($req->ajax ?? false) 
        || (strtolower($req->headers['X-Requested-With'] ?? '') === 'xmlhttprequest');

    // 🔥 On remplit la session AVANT
    if ($result['ok']) {
        $_SESSION['user'] = [
            'username' => $result['values']['username'],
            'email' => $result['values']['email'],
            'id' => $controller->getUserIdByEmail($result['values']['email']),
        ];
    }

    if ($isAjax) {
        Flight::json($result);
        return;
    }

    if ($result['ok']) {
        Flight::redirect('/home');
    } else {
        Flight::render('login', [
            'errors' => $result['errors'] ?? [],
            'values' => $result['values'] ?? [],
        ]);
    }
});


Flight::route('POST /inscription', function() {
    $controller = new UserController();
    $result = $controller->register(true);

    $req = Flight::request();
    $isAjax = ($req->ajax ?? false) || (strtolower($req->headers['X-Requested-With'] ?? '') === 'xmlhttprequest');
    if($result['ok']){
           // Flight::json($result);
           // $controller->createUser($result['values']['username'], $result['values']['email'], $result['values']['password']);
            $_SESSION['user'] = [
                'username' => $result['values']['username'],
                'email' => $result['values']['email'],
                'id' => $controller->getUserIdByEmail($result['values']['email']),
            ];
           // Flight::render('/home'); 
        } 
        if ($isAjax) {
        Flight::json($result);
        return;
    } 

    if($result['ok']){
        Flight::json($result);
        $controller->createUser($result['values']['username'], $result['values']['email'], $result['values']['password']);
        $_SESSION['user'] = [
            'username' => $result['values']['username'],
            'email' => $result['values']['email'],
            'id' => $controller->getUserIdByEmail($result['values']['email']),
        ];
        // Flight::render('header',[
        //     'user' => $_SESSION['user']
        // ]);
        Flight::redirect('/home'); 
    } else { 
        Flight::json($result); 
        Flight::render('FormInscription',[
            'errors' => $result['errors'] ?? [],
            'values' => $result['values'] ?? [],
        ]); 
    } 
});
if(isset($_SESSION['user'])){


Flight::route('/produits', function () {
    $produitController = new ProduitController();
    $userController = new UserController();
    $categorieController = new CategorieController();

    $categories = $categorieController->getAllCategorie();
   // Flight::render('sidebar', [ 'liste' => $result ]);
    $produits = $produitController->listProduitsDisponibles();

    // Sécurité
    if (!isset($_SESSION['user'])) {
        Flight::render('login');
        return;
    } 

    // Ajouter user et categorie pour chaque produit
    foreach ($produits as &$produit) {
        $produit['user'] = $userController->getUserById($produit['user_id']);
        $produit['categorie'] = $categorieController->getCategorie($produit['categorie_id']);
    } 

    Flight::render('produits', [
        'produits' => $produits,
        'categories' => $categories
    ]);

});


// ============ detaille produit ================
Flight::route('/produit/@id', function ($id) {
    $produitController = new ProduitController();
    $userController = new UserController();
    $categorieController = new CategorieController();
    $echangeController = new EchangeController();

    $product_selected = $produitController->getProduitById($id);

    if (!$product_selected) {
        Flight::notFound();
        return;
    }

    // Ajouter infos user + catégorie
    $product_selected['user'] = $userController->getUserById($product_selected['user_id']);
    $product_selected['categorie'] = $categorieController->getCategorie($product_selected['categorie_id']);
    $historique = $echangeController->getHistoriqueProduit($id);

    Flight::render('produit_detail', [
        'produit_detail' => $product_selected,
        'historique' => $historique
    ]);
});

//================ produits par categorie ================
Flight::route('GET /produitCategories/@id', function ($id_categorie) {
    $produitController = new ProduitController();
    $categorieController = new CategorieController();
    $userController = new UserController();

    $categories = $categorieController->getAllCategorie();
    
    $resultat = $produitController->getProduitsByCategorie(
        $id_categorie,
        $produitController->listProduitsDisponibles()
    );

    // Ajouter infos user et catégorie pour chaque produit
    foreach ($resultat as &$produit) {
        $produit['user'] = $userController->getUserById($produit['user_id']);
        $produit['categorie'] = $categorieController->getCategorie($produit['categorie_id']);
    }

    Flight::render('produits', [
        'produits' => $resultat,
        'categories' => $categories
    ]);
});

Flight::route('GET /recherche', function () {
    if (!isset($_SESSION['user'])) {
        Flight::redirect('/');
        return;
    }

    $req = Flight::request();
    $motCle = trim($req->query['q'] ?? '');
    $categorieId = $req->query['categorie'] ?? null;
    $categorieId = is_numeric($categorieId) ? (int) $categorieId : null;

    $produitController = new ProduitController();
    $categorieController = new CategorieController();
    $userController = new UserController();

    $categories = $categorieController->getAllCategorie();
    $results = $produitController->searchProduits($motCle, $categorieId);

    foreach ($results as &$produit) {
        $produit['user'] = $userController->getUserById($produit['user_id']);
        $produit['categorie'] = $categorieController->getCategorie($produit['categorie_id']);
    }

    Flight::render('recherche', [
        'produits' => $results,
        'categories' => $categories,
        'motCle' => $motCle,
        'categorieActive' => $categorieId
    ]);
});
} else {
    Flight::route('/produits', function () {
        Flight::render('login');
    });
}

Flight::route('GET /mes-produits', function () {
    if (!isset($_SESSION['user'])) {
        Flight::redirect('/');
        return;
    }

    $produitController = new ProduitController();
    $categorieController = new CategorieController();

    $mesProduits = $produitController->listProduitsUtilisateur();

    foreach ($mesProduits as &$produit) {
        $produit['categorie'] = $categorieController->getCategorie($produit['categorie_id']);
    }

    Flight::render('mes_produits', [
        'produits' => $mesProduits,
        'user' => $_SESSION['user']
    ]);
});

Flight::route('GET /admin/statistiques', function () {
    if (!isset($_SESSION['user'])) {
        Flight::redirect('/');
        return;
    }

    $userController = new UserController();
    $echangeController = new EchangeController();

    $totalUsers = count($userController->getAllUser());
    $echanges = $echangeController->getAllEchanges();
    $totalEchanges = 0;

    if (is_array($echanges)) {
        if (isset($echanges['count'])) {
            $totalEchanges = (int) $echanges['count'];
        } elseif (isset($echanges['rows']) && is_array($echanges['rows'])) {
            $totalEchanges = count($echanges['rows']);
        } else {
            $totalEchanges = count($echanges);
        }
    }

    Flight::render('admin_statistiques', [
        'totalUsers' => $totalUsers,
        'totalEchanges' => $totalEchanges
    ]);
});

Flight::route('/echanges', function () {
    Flight::render('echanges');
});
// API JSON
Flight::route('GET /api/echanges', function () {
    $controller = new EchangeController();
    Flight::json($controller->getAllEchanges());

});

// Update status
Flight::route('POST /api/echanges/@id/status', function ($id) {
    $data = Flight::request()->data;
    $controller = new EchangeController();

    $success = $controller->updateEchangeStatus($id, $data->status_id);

    Flight::json(['success' => $success]);
});

//====== echange user connecter 

Flight::route('GET /echange_user/@user_id', function($user_id) {
    Flight::render('echange_user', ['user_id' => $user_id]);
});
//=== tous les echanges d'un user
Flight::route('GET /api/echanges/user/@user_id', function($user_id) {
    $controller = new EchangeController();
    Flight::json($controller->getAllEchangesUsers($user_id));
});
//=== mes demandes envoyées
Flight::route('GET /api/echanges/user/@user_id/envoyees', function($user_id) {
    $controller = new EchangeController();
    Flight::json($controller->getMesDemandesEnvoyees($user_id));
});
//=== mes demandes reçues
Flight::route('GET /api/echanges/user/@user_id/recues', function($user_id) {
    $controller = new EchangeController();
    Flight::json($controller->getDemandesRecues($user_id));
});

// ===== mes échanges filtrés par statut
Flight::route('GET /api/echanges/user/@user_id/status/@status_id', function($user_id, $status_id) {
    $controller = new EchangeController();
    Flight::json($controller->getMesEchangesByStatus($user_id, $status_id));
});

//3️⃣ Routes API pour les produits (pour ton formulaire)
// Tous les produits
Flight::route('GET /api/produits', function () {
    $controller = new ProduitController();
    Flight::json($controller->getAllProduits());
});



// ===== ajout de echange
Flight::route('POST /api/echanges', function () {
    $data = Flight::request()->data;
    $controller = new EchangeController();
    
    $result = $controller->addEchange(
        $data->produit1_id,
        $data->produit2_id,
        $data->user1_id,
        $data->user2_id,
        $data->status_id
    );
    
    Flight::json($result);
});

//================= statistiques admin =================
Flight::route('GET /admin/statistiques', function() {
    session_start();
    if(!isset($_SESSION['user'])) {
        Flight::halt(403, 'Accès refusé');
    }
    $db = Flight::db(); // ton PDO ou la connexion Flight
    $controller = new EchangeController();

    Flight::json($controller->getStats());
});
