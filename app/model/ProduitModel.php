<?php 
namespace app\model; 

use PDO;

class ProduitModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllProduits() {
        $sql = "SELECT * FROM products";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Tous les produits sauf ceux de l'utilisateur connecté
    public function getOthersProduit($idConnecter) {
        $sql = "SELECT * FROM products WHERE user_id != ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idConnecter]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 

    // Produits appartenant à un utilisateur spécifique
    public function getProduitsByUserId($userId) {
        $sql = "SELECT * FROM products WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Produit par ID
    public function getProduitById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Produits par catégorie
    public function getProduitByCategorieId($categorieId) {
        $sql = "SELECT * FROM products WHERE categorie_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categorieId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un produit
    public function addProduit($nom, $description, $prix, $categorieId, $userId) {
        $sql = "INSERT INTO products (nom, description, prix, categorie_id, user_id)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$nom, $description, $prix, $categorieId, $userId
        ]);
    }


    // Cherche les produits d'un utilisateur par nom et catégorie
    public function searchProduitsByUser($user_id, $nom = null, $categorie_id = null) {
        $query = "SELECT * FROM products WHERE user_id = :user_id";
        $params = ['user_id' => $user_id];

        if ($nom) {
            $query .= " AND nom LIKE :nom";
            $params['nom'] = "%$nom%";
        }

        if ($categorie_id) {
            $query .= " AND categorie_id = :categorie_id";
            $params['categorie_id'] = $categorie_id;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recherche globale des produits avec mot-clé et catégorie optionnels
    public function searchProduits(?string $motCle = null, ?int $categorieId = null) {
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];

        if ($motCle !== null && $motCle !== '') {
            $sql .= " AND nom LIKE :motcle";
            $params['motcle'] = '%' . $motCle . '%';
        }

        if ($categorieId !== null && $categorieId > 0) {
            $sql .= " AND categorie_id = :categorie";
            $params['categorie'] = $categorieId;
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}