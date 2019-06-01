<?php
class User{
    private $connection;

    public $id_utilizator;
    public $username;
    public $parola;
    public $nume;
    public $prenume;
    public $email;
    public $cod_resetare;
    public $cheie_criptare;

    public function __construct($db){
        $this->connection = $db;
    }

    //functia asta face un get all, este pentru testare
    function read(){
        $sqlQuery = "SELECT id_utilizator, username, parola, nume, prenume, email, cod_resetare, cheie_criptare FROM utilizator";

        $statement = $this->connection->prepare($sqlQuery);
        $statement->execute();

        return $statement;
    }

    function create(){
      $sqlQuery = "INSERT INTO utilizator SET username=:username, parola=:parola, nume=:nume, prenume=:prenume, email=:email, cod_resetare=:cod_resetare, cheie_criptare=:cheie_criptare";

      $statement = $this->connection->prepare($sqlQuery);

      $this->username=htmlspecialchars(strip_tags($this->username));
      $this->parola=htmlspecialchars(strip_tags($this->parola));
      $this->nume=htmlspecialchars(strip_tags($this->nume));
      $this->prenume=htmlspecialchars(strip_tags($this->prenume));
      $this->email=htmlspecialchars(strip_tags($this->email));
      $this->cod_resetare=htmlspecialchars(strip_tags($this->cod_resetare));
      $this->cheie_criptare=htmlspecialchars(strip_tags($this->cheie_criptare));

      $statement->bindParam(":username", $this->username);
      $statement->bindParam(":parola", $this->parola);
      $statement->bindParam(":nume", $this->nume);
      $statement->bindParam(":prenume", $this->prenume);
      $statement->bindParam(":email", $this->email);
      $statement->bindParam(":cod_resetare", $this->cod_resetare);
      $statement->bindParam(":cheie_criptare", $this->cheie_criptare);

      if($statement->execute()){
          return true;
      }
      return false;
    }

  function getByName(){
    $sqlQuery = "SELECT username, parola, id_utilizator, nume, prenume, email FROM utilizator WHERE username = ?";

    $statement = $this->connection->prepare($sqlQuery);

    $statement->bindParam(1, $this->username);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    $this->username = $row['username'];
    $this->id_utilizator = $row['id_utilizator'];
    $this->parola = $row['parola'];
    $this->nume = $row['nume'];
    $this->prenume = $row['prenume'];
    $this->email = $row['email'];
  }

  function update(){
    $sqlQuery = "UPDATE utilizator SET parola =:parola, nume =:nume, prenume =:prenume, email =:email WHERE username = :username";

    $statement = $this->connection->prepare($sqlQuery);

    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->parola=htmlspecialchars(strip_tags($this->parola));
    $this->nume=htmlspecialchars(strip_tags($this->nume));
    $this->prenume=htmlspecialchars(strip_tags($this->prenume));
    $this->email=htmlspecialchars(strip_tags($this->email));

    $statement->bindParam(':username', $this->username);
    $statement->bindParam(':parola', $this->parola);
    $statement->bindParam(':nume', $this->nume);
    $statement->bindParam(':prenume', $this->prenume);
    $statement->bindParam(':email', $this->email);

    if($statement->execute()){
        return true;
    }
    return false;
  }

  // pentru testare, nu ar trebui sa fie posibil
  function delete(){
    $sqlQuery = "DELETE FROM utilizator WHERE username = ?";

    $statement = $this->connection->prepare($sqlQuery);

    $this->username=htmlspecialchars(strip_tags($this->username));
    $statement->bindParam(1, $this->username);

    if($statement->execute()){
        return true;
    }
    return false;
  }

  function login(){
    $sqlQuery = "SELECT email FROM utilizator WHERE LOWER(username) =:username AND parola =:parola";

    $statement = $this->connection->prepare($sqlQuery);

    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->parola=htmlspecialchars(strip_tags($this->parola));
    $statement->bindParam(':username', $this->username);
    $statement->bindParam(':parola', $this->parola);

    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    $this->email = $row['email'];
  }

  function loginPentruParolaCriptata(){
    $sqlQuery = "SELECT parola FROM utilizator WHERE LOWER(username) =:username";

    $statement = $this->connection->prepare($sqlQuery);

    $this->username=htmlspecialchars(strip_tags($this->username));
    $statement->bindParam(':username', $this->username);

    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    $this->parola = $row['parola'];
  }
}
