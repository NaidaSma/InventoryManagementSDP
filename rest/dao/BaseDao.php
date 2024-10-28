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


    //user dao
    public function getUsers() {
      $query = "SELECT * from user";
  
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getUserById($id) {
    $query = "SELECT * FROM user WHERE userID = :userID";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':userID', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC); 
}
    public function add_user($data){
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
    public function updateUser($id, $data) {
      $query = "UPDATE user SET name = :name, surname = :surname, username = :username, password = :password, role = :role WHERE userID =".$id;
      
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':userID', $id);  
      $stmt->bindParam(':name', $data['name']);
      $stmt->bindParam(':surname', $data['surname']);
      $stmt->bindParam(':username', $data['username']);
      $stmt->bindParam(':password', $data['password']); 
      $stmt->bindParam(':role', $data['role']);
  
      $stmt->execute();
      
      return true;  
  }
    public function deleteUser($id) {
      $query = "DELETE FROM user WHERE userID =:userID";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':userID', $id); 
      $stmt->execute();
  }







//Inventory dao

public function getInventory() {
  $query = "SELECT * from item";

  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getItemById($itemID) {
  $query = "SELECT * from item where itemID=".$itemID;
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 
}

public function addItem($data) {
  $query = "INSERT INTO item (itemName, unitPrice, quantity, supplierID, categoryID, voltageRating, amperageRating, description) 
            VALUES (:itemName, :unitPrice, :quantity, :supplierID, :categoryID, :voltageRating, :amperageRating, :description)";
            
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':itemName', $data['itemName']);
  $stmt->bindParam(':unitPrice', $data['unitPrice']);
  $stmt->bindParam(':quantity', $data['quantity']);
  $stmt->bindParam(':supplierID', $data['supplierID']);
  $stmt->bindParam(':categoryID', $data['categoryID']);
  $stmt->bindParam(':voltageRating', $data['voltageRating']);
  $stmt->bindParam(':amperageRating', $data['amperageRating']);
  $stmt->bindParam(':description', $data['description']);

  return $stmt->execute(); 
}

public function updateItem($data) {
  $query = "UPDATE item 
            SET itemName = :itemName, unitPrice = :unitPrice, quantity = :quantity, description = :description
            WHERE itemID = :itemID";
            
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':itemName', $data['itemName']);
  $stmt->bindParam(':unitPrice', $data['unitPrice']);
  $stmt->bindParam(':quantity', $data['quantity']);
  $stmt->bindParam(':description', $data['description']);
  $stmt->bindParam(':itemID', $data['itemID']);
  
  return $stmt->execute();
}

public function deleteItem($itemID) {
  $query = "DELETE FROM item WHERE itemID=:itemID"; 
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':itemID', $itemID); 
  $stmt->execute();
}








//Category dao
public function getAllCategories() {
  $query = "SELECT * from category";

  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 
}

public function getCategoryById($categoryid) {
  $query = "SELECT * from category where categoryid=".$categoryid;
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 
}

public function addCategory($data) {
  $query = "INSERT INTO category (categoryName) VALUES (:categoryName)";

  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':categoryName', $data['categoryName']);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

public function deleteCategory($id) {
  $query = "DELETE FROM category WHERE categoryid=:categoryid"; 
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':categoryid', $id); 
  $stmt->execute();
}

//supplier dao
public function getAllSuppliers() {
  $query = "SELECT * from supplier";

  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
  
}
public function getSupplierById($supplierid) {
  $query = "SELECT * from supplier where supplierid=".$supplierid;
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 
}

public function addSupplier($data) {
  $query = "INSERT INTO supplier (supplierName, address, contactNo) VALUES (:supplierName, :address, :contactNo)";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':supplierName', $data['supplierName']);
  $stmt->bindParam(':address', $data['address']);
  $stmt->bindParam(':contactNo', $data['contactNo']);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

public function deleteSupplier($id) {
  $query = "DELETE FROM supplier WHERE supplierid=:supplierid";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':supplierid', $id);
  $stmt->execute();
}
}
?>