<?php 
namespace app\controllers;
use app\model\CategorieModel ; 

use flight\Engine;
use Flight ; 

class CategorieController {
    private $categorieModel;

    public function __construct() {
        $db = Flight::db();
        $this->categorieModel = new CategorieModel($db);
    }

    public function getCategorie($id) {
        return $this->categorieModel->getCategorieById($id);
    }

    public function getAllCategorie(){
        $db = Flight::db();
        $categorie = new CategorieModel($db);

        $allCategories = $categorie->getAllCategorie();

        return $allCategories;
    }
 

    public function addCategorie($nom, $icon){
        $db = Flight::db();
        $categorie = new CategorieModel($db);
        return $categorie->addCategorie($nom, $icon);
    }

    public function deleteCategorie($id){
        $db = Flight::db();
        $categorie = new CategorieModel($db);
        return $categorie->deleteCategorie($id);
    }

    public function updateCategorie($id, $nom, $icon){
        $db = Flight::db();
        $categorie = new CategorieModel($db);
        return $categorie->updateCategorie($id, $nom, $icon);
    }



}
