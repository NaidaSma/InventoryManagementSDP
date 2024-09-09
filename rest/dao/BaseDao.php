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

  public function get_profile_info($userID){
    $query = "SELECT name, surname, role 
    from user 
    where userID=".$userID;

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
//Inventory dao
  public function getAllItems() {
    $query = "SELECT * from item";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getItemById($id) {
  $query = "SELECT * from item where itemID=".$id;
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function addItem($data) {
  $query = "INSERT INTO item (itemName, description, quantity, unitPrice, supplierID, categoryID, voltageRating, amperageRating, warehouseID, useridx)
   VALUES (:itemName, :description, :quantity, :unitPrice, :supplierID, :categoryID, :voltageRating, :amperageRating, :warehouseID, :useridx)";

  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':itemName', $data['itemName']);
  $stmt->bindParam(':quantity', $data['quantity']);
  $stmt->bindParam(':unitPrice', $data['unitPrice']);
  $stmt->bindParam(':categoryID', $data['categoryID']);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
   
}

public function updateItem($id, $data) {
  $query = "UPDATE item SET itemName = :itemName, quantity = :quantity, unitPrice = :unitPrice, categoryID = :categoryID WHERE itemID = ".$id;
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':itemID', $id);
  $stmt->bindParam(':itemName', $data['itemName']);
  $stmt->bindParam(':quantity', $data['quantity']);
  $stmt->bindParam(':unitPrice', $data['unitPrice']);
  $stmt->bindParam(':categoryID', $data['categoryID']);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function deleteItem($id) {
  $query = "DELETE FROM item WHERE id ".$id;
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':itemID', $id);
  $stmt->execute();
  
}


//Category dao
public function getAllCategories() {
  $query = "SELECT * from category";

  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 
}

public function getCategoryById($id) {
  $query = "SELECT * from category where categoryid=:id";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':id', $id);
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
  $query = "DELETE FROM category WHERE categoryid=:id";
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
  $query = "DELETE FROM supplier WHERE supplierid=".$id;
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':supplierid', $id);
  $stmt->execute();
}
}
?>