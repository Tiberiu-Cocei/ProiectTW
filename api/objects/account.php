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
        $sqlQuery = "SELECT id_cont, username, adresa_site, nume_site, comentarii, data_adaugare, data_expirare, contor_utilizari, putere_parola
                   FROM conturi WHERE id_categorie = ?";

        $statement = $this->connection->prepare($sqlQuery);
        $this->id_categorie=htmlspecialchars(strip_tags($this->id_categorie));
        $statement->bindParam(1, $this->id_categorie);
        $statement->execute();

        return $statement;
    }

    function getByStrength(){
        $sqlQuery = "SELECT id_cont, username, adresa_site, nume_site, comentarii, data_adaugare, data_expirare, contor_utilizari, putere_parola
                   FROM conturi WHERE id_utilizator = ? ORDER BY putere_parola DESC";

        $statement = $this->connection->prepare($sqlQuery);
        $this->id_utilizator=htmlspecialchars(strip_tags($this->id_utilizator));
        $statement->bindParam(1, $this->id_utilizator);
        $statement->execute();

        return $statement;
    }

    function getByUsage(){
        $sqlQuery = "SELECT id_cont, username, adresa_site, nume_site, comentarii, data_adaugare, data_expirare, contor_utilizari, putere_parola
                   FROM conturi WHERE id_utilizator = ? ORDER BY contor_utilizari DESC";

        $statement = $this->connection->prepare($sqlQuery);
        $this->id_utilizator=htmlspecialchars(strip_tags($this->id_utilizator));
        $statement->bindParam(1, $this->id_utilizator);
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

      function create(){
        $sqlQuery = "INSERT INTO conturi SET id_categorie=:id_categorie, id_utilizator=:id_utilizator, username=:username,
         parola=:parola, adresa_site=:adresa_site, nume_site=:nume_site, comentarii=:comentarii, data_expirare=:data_expirare,
         contor_utilizari=:contor_utilizari, putere_parola=:putere_parola";

        $statement = $this->connection->prepare($sqlQuery);

        $this->id_categorie=htmlspecialchars(strip_tags($this->id_categorie));
        $this->id_utilizator=htmlspecialchars(strip_tags($this->id_utilizator));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->parola=htmlspecialchars(strip_tags($this->parola));
        $this->adresa_site=htmlspecialchars(strip_tags($this->adresa_site));
        $this->nume_site=htmlspecialchars(strip_tags($this->nume_site));
        $this->comentarii=htmlspecialchars(strip_tags($this->comentarii));
        $this->data_expirare=htmlspecialchars(strip_tags($this->data_expirare));
        $this->contor_utilizari=htmlspecialchars(strip_tags($this->contor_utilizari));
        $this->putere_parola=htmlspecialchars(strip_tags($this->putere_parola));

        $statement->bindParam(":id_categorie", $this->id_categorie);
        $statement->bindParam(":id_utilizator", $this->id_utilizator);
        $statement->bindParam(":username", $this->username);
        $statement->bindParam(":parola", $this->parola);
        $statement->bindParam(":adresa_site", $this->adresa_site);
        $statement->bindParam(":nume_site", $this->nume_site);
        $statement->bindParam(":comentarii", $this->comentarii);
        $statement->bindParam(":data_expirare", $this->data_expirare);
        $statement->bindParam(":contor_utilizari", $this->contor_utilizari);
        $statement->bindParam(":putere_parola", $this->putere_parola);

        if($statement->execute()){
            return true;
        }
        return false;
      }

      function getExpiredCount(){
          $sqlQuery = "SELECT count(id_cont) AS numar FROM conturi WHERE data_expirare < ? AND id_utilizator = ?";

          $statement = $this->connection->prepare($sqlQuery);
          $this->username=htmlspecialchars(strip_tags($this->data_expirare));
          $statement->bindParam(1, $this->data_expirare);
          $statement->bindParam(2, $this->id_utilizator);
          $statement->execute();

          $row = $statement->fetch(PDO::FETCH_ASSOC);
          $nr = $row['numar'];
          return $nr;
      }

      function increment($number){
          $sqlQuery = "UPDATE conturi SET contor_utilizari = contor_utilizari + $number WHERE id_cont = ?";
          $statement = $this->connection->prepare($sqlQuery);
          $this->id_cont=htmlspecialchars(strip_tags($this->id_cont));
          $statement->bindParam(1, $this->id_cont);
          if($statement->execute()){
              return true;
          }
          return false;
      }

      function getPassword(){
          $sqlQuery = "SELECT parola FROM conturi WHERE id_cont = ?";
          $statement = $this->connection->prepare($sqlQuery);
          $statement->bindParam(1, $this->id_cont);
          $statement->execute();

          $row = $statement->fetch(PDO::FETCH_ASSOC);
          $parola = $row['parola'];
          return $parola;
      }

      function getById(){
          $sqlQuery = "SELECT username, parola, adresa_site, nume_site, comentarii, data_expirare, putere_parola FROM conturi WHERE id_cont = ?";

          $statement = $this->connection->prepare($sqlQuery);

          $this->id_cont = htmlspecialchars(strip_tags($this->id_cont));
          $statement->bindParam(1, $this->id_cont);
          $statement->execute();
          $row = $statement->fetch(PDO::FETCH_ASSOC);

          $this->username = $row['username'];
          $this->parola = $row['parola'];
          $this->adresa_site = $row['adresa_site'];
          $this->nume_site = $row['nume_site'];
          $this->comentarii = $row['comentarii'];
          $this->data_expirare = $row['data_expirare'];
          $this->putere_parola = $row['putere_parola'];
      }

      function update(){
          $sqlQuery = "UPDATE conturi SET username =:username, parola =:parola, adresa_site =:adresa_site, nume_site =:nume_site,
                                          comentarii =:comentarii, data_expirare =:data_expirare, putere_parola =:putere_parola WHERE id_cont =:id_cont";

          $statement = $this->connection->prepare($sqlQuery);

          $this->username=htmlspecialchars(strip_tags($this->username));
          $this->parola=htmlspecialchars(strip_tags($this->parola));
          $this->adresa_site=htmlspecialchars(strip_tags($this->adresa_site));
          $this->nume_site=htmlspecialchars(strip_tags($this->nume_site));
          $this->comentarii=htmlspecialchars(strip_tags($this->comentarii));
          $this->data_expirare=htmlspecialchars(strip_tags($this->data_expirare));
          $this->putere_parola=htmlspecialchars(strip_tags($this->putere_parola));
          $this->id_cont = htmlspecialchars(strip_tags($this->id_cont));

          $statement->bindParam(':username', $this->username);
          $statement->bindParam(':parola', $this->parola);
          $statement->bindParam(':adresa_site', $this->adresa_site);
          $statement->bindParam(':nume_site', $this->nume_site);
          $statement->bindParam(':comentarii', $this->comentarii);
          $statement->bindParam(':data_expirare', $this->data_expirare);
          $statement->bindParam(':putere_parola', $this->putere_parola);
          $statement->bindParam(':id_cont', $this->id_cont);

          if($statement->execute()){
              return true;
          }
          return false;
      }

      function getForExport(){
          $sqlQuery = "SELECT nume_categorie, nume_site, adresa_site, username, parola, comentarii, data_adaugare, data_expirare, id_cont
                              FROM categorii NATURAL JOIN conturi WHERE conturi.id_utilizator = ?";

          $statement = $this->connection->prepare($sqlQuery);
          $this->id_utilizator = htmlspecialchars(strip_tags($this->id_utilizator));
          $statement->bindParam(1, $this->id_utilizator);
          $statement->execute();

          return $statement;
      }
}
