<?php

class BaseDao {
    protected $conn;

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

    public function query_unique($query, $params) {
      $stmt = $this->conn->prepare($query);
      $stmt->execute($params);
      return $stmt->fetch();
  }

   // Get the number of categories
   public function getCategoryCount() {
    $query = "SELECT COUNT(*) FROM category";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Get the number of items
public function getItemCount() {
    $query = "SELECT COUNT(*) FROM item";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}
// Get items per category
public function getItemsPerCategory() {
  $query = "SELECT category.categoryName, COUNT(item.itemID) AS itemCount 
            FROM item
            JOIN category ON item.categoryID = category.categoryid 
            GROUP BY category.categoryid";
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getLowStockItems() {
  $query = "SELECT itemName, quantity FROM item WHERE quantity < 5";
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    public function add_user($user){
      $query = "INSERT INTO user (name, surname, username, password, role) VALUES (:name, :surname, :username, :password, :role)";

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(':name', $user['name']);
      $stmt->bindParam(':surname', $user['surname']);
      $stmt->bindParam(':username', $user['username']);
      $stmt->bindParam(':password', $user['password']);
      $stmt->bindParam(':role', $user['role']);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateUser($userID, $user) {
      $query = "UPDATE user SET name = :name, surname = :surname, username = :username, role = :role WHERE userID = :userID";
      
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);  
      $stmt->bindParam(':name', $user['name']);
      $stmt->bindParam(':surname', $user['surname']);
      $stmt->bindParam(':username', $user['username']);
      $stmt->bindParam(':role', $user['role']);
  
      if ($stmt->execute()) {
          return $stmt->rowCount() > 0; 
      }
      return false; 
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
  $query = "SELECT * FROM item WHERE itemID = :itemID";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':itemID', $itemID, PDO::PARAM_INT); // Bind parameter to prevent SQL injection
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
 
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

public function updateItem($itemID, $item) {
  $query = "UPDATE item 
            SET itemName = :itemName, unitPrice = :unitPrice, quantity = :quantity, description = :description
            WHERE itemID = :itemID";
            
  $stmt = $this->conn->prepare($query);
  error_log("Binding values: itemName = " . $item['itemName'] . ", unitPrice = " . $item['unitPrice'] . ", quantity = " . $item['quantity'] . ", description = " . $item['description'] . ", itemID = " . $itemID);
  $stmt->bindParam(':itemName', $item['itemName']);
  $stmt->bindParam(':unitPrice', $item['unitPrice']);
  $stmt->bindParam(':quantity', $item['quantity']);
  $stmt->bindParam(':description', $item['description']);
  $stmt->bindParam(':itemID', $itemID); 
  
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



//orders dao
public function getOrders() {
  $query = "SELECT shipments.shipmentid, shipments.address, user.username, shipments.status, shipments.date
FROM shipments
JOIN user ON shipments.userid = user.userID;";
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateShipmentStatus($shipmentId, $status) {
  $query = "UPDATE shipments SET status = :status WHERE shipmentid = :shipmentid";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':status', $status);
  $stmt->bindParam(':shipmentid', $shipmentId);
  return $stmt->execute();
}
public function deleteOrder($shipmentId) {
  $query = "DELETE FROM shipments WHERE shipmentid=:shipmentid";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':shipmentid', $shipmentId);
  $stmt->execute();
}


}
?>