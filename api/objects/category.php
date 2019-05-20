<?php
class Category{
    private $connection;

    public $id_categorie;
    public $id_utilizator;
    public $nume_categorie;

    public function __construct($db){
        $this->connection = $db;
    }
}
