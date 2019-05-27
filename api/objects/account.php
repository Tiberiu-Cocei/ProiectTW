<?php
class Account{
    private $connection;

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
    public $contor_utilizari;
    public $putere_parola;

    public function __construct($db){
        $this->connection = $db;
    }

    function getByCategory(){
        $sqlQuery = "SELECT id_cont, username, parola, adresa_site, nume_site, comentarii, data_adaugare, data_expirare, contor_utilizari, putere_parola
                   FROM conturi WHERE id_categorie = ?";

        $statement = $this->connection->prepare($sqlQuery);
        $this->username=htmlspecialchars(strip_tags($this->id_categorie));
        $statement->bindParam(1, $this->id_categorie);
        $statement->execute();

        return $statement;
    }

    function delete(){
        $sqlQuery = "DELETE FROM conturi WHERE id_cont = ?";
  
        $statement = $this->connection->prepare($sqlQuery);
  
        $this->id_cont=htmlspecialchars(strip_tags($this->id_cont));
        $statement->bindParam(1, $this->id_cont);
  
        if($statement->execute()){
            return true;
        }
        return false;
      }
}
