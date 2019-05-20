<?php
class User{
    private $conn;
    public $id_utilizator;
    public $username;
    public $parola;
    public $nume;
    public $prenume;
    public $email;
    public $cod_resetare;
    public $cheie_criptare;

    public function __construct($db){
        $this->conn = $db;
    }

    //functia asta face un get all, este pentru testare
    function read(){
        $query = "SELECT id_utilizator, username, parola, nume, prenume, email, cod_resetare, cheie_criptare FROM utilizator";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function create(){
    $query = "INSERT INTO utilizator SET username=:username, parola=:parola, nume=:nume, prenume=:prenume, email=:email, cod_resetare=:cod_resetare, cheie_criptare=:cheie_criptare";

    $stmt = $this->conn->prepare($query);

    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->parola=htmlspecialchars(strip_tags($this->parola));
    $this->nume=htmlspecialchars(strip_tags($this->nume));
    $this->prenume=htmlspecialchars(strip_tags($this->prenume));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->cod_resetare=htmlspecialchars(strip_tags($this->cod_resetare));
    $this->cheie_criptare=htmlspecialchars(strip_tags($this->cheie_criptare));

    $stmt->bindParam(":username", $this->username);
    $stmt->bindParam(":parola", $this->parola);
    $stmt->bindParam(":nume", $this->nume);
    $stmt->bindParam(":prenume", $this->prenume);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":cod_resetare", $this->cod_resetare);
    $stmt->bindParam(":cheie_criptare", $this->cheie_criptare);

    if($stmt->execute()){
        return true;
    }
    return false;
  }

  function getByName(){
    $query = "SELECT username, parola, nume, prenume, email FROM utilizator WHERE username = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->username = $row['username'];
    $this->parola = $row['parola'];
    $this->nume = $row['nume'];
    $this->prenume = $row['prenume'];
    $this->email = $row['email'];
  }

function update(){
    $query = "UPDATE utilizator SET parola =:parola, nume =:nume, prenume =:prenume, email =:email WHERE username = :username";

    $stmt = $this->conn->prepare($query);

    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->parola=htmlspecialchars(strip_tags($this->parola));
    $this->nume=htmlspecialchars(strip_tags($this->nume));
    $this->prenume=htmlspecialchars(strip_tags($this->prenume));
    $this->email=htmlspecialchars(strip_tags($this->email));

    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':parola', $this->parola);
    $stmt->bindParam(':nume', $this->nume);
    $stmt->bindParam(':prenume', $this->prenume);
    $stmt->bindParam(':email', $this->email);

    if($stmt->execute()){
        return true;
    }
    return false;
  }

  // pentru testare, nu ar trebui sa fie posibil
function delete(){
    $query = "DELETE FROM utilizator WHERE id_utilizator = ?";
    $stmt = $this->conn->prepare($query);
    $this->id=htmlspecialchars(strip_tags($this->id_utilizator));
    $stmt->bindParam(1, $this->id_utilizator);
    if($stmt->execute()){
        return true;
    }
    return false;
  }
}
