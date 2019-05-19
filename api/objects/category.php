<?php
class Category{
    private $conn;
    private $table_name = "categorii";

    public $id_categorie;
    public $id_utilizator;
    public $nume_categorie;

    public function __construct($db){
        $this->conn = $db;
    }
}
