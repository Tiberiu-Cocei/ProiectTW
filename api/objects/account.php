<?php
class Account{
    private $conn;
    private $table_name = "conturi";

    public $id_cont;
    public $id_categorie;
    public $id_utilizator;
    public $username;
    public $parola;
    public $adresa_site;
    public $nume_site;
    public $comentarii;
    public $data_adaugare;
    public $data_expirare;
    public $contor_utilizari
    public $putere_parola

    public function __construct($db){
        $this->conn = $db;
    }
}
