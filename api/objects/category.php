<?php
class Category{
    private $connection;

    public $id_categorie;
    public $id_utilizator;
    public $nume_categorie;

    public function __construct($db){
        $this->connection = $db;
    }

    function getByUserId(){
        $sqlQuery = "SELECT id_categorie, nume_categorie FROM categorii WHERE id_utilizator =:id_utilizator";

        $statement = $this->connection->prepare($sqlQuery);

        $this->id_utilizator=htmlspecialchars(strip_tags($this->id_utilizator));
        $statement->bindParam(':id_utilizator', $this->id_utilizator);

        $statement->execute();

        return $statement;
    }

    function create(){
      $sqlQuery = "INSERT INTO categorii SET id_utilizator=:id_utilizator, nume_categorie=:nume_categorie";

      $statement = $this->connection->prepare($sqlQuery);

      $this->id_utilizator=htmlspecialchars(strip_tags($this->id_utilizator));
      $this->nume_categorie=htmlspecialchars(strip_tags($this->nume_categorie));

      $statement->bindParam(":id_utilizator", $this->id_utilizator);
      $statement->bindParam(":nume_categorie", $this->nume_categorie);

      if($statement->execute()){
          return true;
      }
      return false;
    }

    function update(){
      $sqlQuery = "UPDATE categorii SET nume_categorie =:nume_categorie WHERE id_categorie = :id_categorie";

      $statement = $this->connection->prepare($sqlQuery);

      $this->nume_categorie=htmlspecialchars(strip_tags($this->nume_categorie));
      $this->id_categorie=htmlspecialchars(strip_tags($this->id_categorie));

      $statement->bindParam(':nume_categorie', $this->nume_categorie);
      $statement->bindParam(':id_categorie', $this->id_categorie);

      if($statement->execute()){
          return true;
      }
      return false;
    }

    function deleteAccounts(){
      $sqlQuery = "DELETE FROM conturi WHERE id_categorie = ?";
      $statement = $this->connection->prepare($sqlQuery);

      $this->id_categorie=htmlspecialchars(strip_tags($this->id_categorie));

      $statement->bindParam(1, $this->id_categorie);

      if($statement->execute()){
          return true;
      }
      return false;
    }

    function delete(){
      $sqlQuery = "DELETE FROM categorii WHERE id_categorie = ?";
      $statement = $this->connection->prepare($sqlQuery);

      $statement->bindParam(1, $this->id_categorie);

      if($statement->execute()){
          return true;
      }
      return false;
    }
}
