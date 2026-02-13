<?php 
namespace app\model; 
use Flight;
use PDO;

Class CategorieModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getCategorieById($id) {
        $stmt = $this->db->prepare("SELECT id, nom FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function  getAllCategorie(){
        $sql = "SELECT * FROM categories"; 
        return $this->db->query($sql)->fetchAll();
    }

    public function addCategorie($nom, $icon){
        $sql = "INSERT INTO categories (nom, icon) VALUES (:nom, :icon)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':icon', $icon);

        return $stmt->execute(); 
    }

    public function deleteCategorie($id){
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateCategorie($id, $nom, $icon){
        $sql = "UPDATE categories 
                SET nom = :nom, icon = :icon 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':icon', $icon);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getProduitsByCategorie($categorieId , $produitAutre){
        foreach($produitAutre as $produit){
          //  echo  "Produit autre : " . $produit['nom'] . " - Catégorie ID : " . $produit['categorie_id'] . "\n";
           // var_dump($produitAutre);
            //var_dump($categorieId);

            if($produit['categorie_id'] == $categorieId){
              //  echo $produit['categorie_id'];
                $resultat[] = $produit;
            } 
        }
        return $resultat ?? [];
    }
}