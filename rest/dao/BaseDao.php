<?php

class BaseDao {
    private $conn;

    public function __construct(){
        try {
          
          $host = '127.0.0.1';
          $username = 'root';
          $password = 'root';
          $dbname = 'warehouse';
          $port = 3306;

          /** TODO
           * Create new connection
           */
          $this->conn = new PDO(
            "mysql:host=" . $host . ";dbname=" . $dbname . ";port=" . $port,
            $username,
            $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
            
        );
        
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }
    public function create_user($data){
      $query = "INSERT INTO user (name, surname, username, password, role) VALUES (:name, :surname, :username, :password, :role)";

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(':name', $data['name']);
      $stmt->bindParam(':surname', $data['surname']);
      $stmt->bindParam(':username', $data['username']);
      $stmt->bindParam(':password', $data['password']);
      $stmt->bindParam(':role', $data['role']);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


  }

?>