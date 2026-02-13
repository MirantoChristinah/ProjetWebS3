<?php
namespace app\controllers;

use app\model\ProduitModel;
use app\model\CategorieModel;

use Flight;

class ProduitController {
    private $produitModel;

    public function __construct()
    {
        $db = Flight::db();
        $this->produitModel = new ProduitModel($db);
    }

    public function listProduitsDisponibles() {
        if (!isset($_SESSION['user'])) {
            return null;
        }

        $userId = $_SESSION['user']['id'];

        return $this->produitModel->getOthersProduit($userId);
    }

    public function listProduitsUtilisateur() {
        if (!isset($_SESSION['user'])) {
            return [];
        }

        $userId = $_SESSION['user']['id'];

        return $this->produitModel->getProduitsByUserId($userId);
    }

    public function getAllProduits() {
        return $this->produitModel->getAllProduits();
    }

    public function getProduitById($id) {
        return $this->produitModel->getProduitById($id);
    }

    public function getProduitsByCategorie($categorieId, $produitAutre){
        $db = Flight::db();
        $categorie = new CategorieModel($db);
        return $categorie->getProduitsByCategorie($categorieId, $produitAutre);
    }

    // Cherche les produits d'un utilisateur par nom et catégorie
    public function searchUserProduits($user_id, $nom = null, $categorie_id = null) {
        $db = Flight::db();
        $produit = new ProduitModel($db);
        return $produit->searchProduitsByUser($user_id, $nom, $categorie_id);
    }

    public function searchProduits(?string $motCle = null, ?int $categorieId = null) {
        return $this->produitModel->searchProduits($motCle, $categorieId);
    } 

    public function getProduitByPourcentage($idProduit, $pourcentage = 10) {
        return $this->produitModel->getProduitByPourcentage($pourcentage, $idProduit);
    } 

    public function getPriceDifference($id1, $id2) {
        return $this->produitModel
                    ->getPriceDifference($id1, $id2);
    } 


}
