<?php

require '../vendor/autoload.php';
try{
    $servername="localhost";
    $username="root";
    $password="root";
    $schema="warehouse";
    $this->conn = new PDO("mysql:host=$servername;dbname=$schema, $username, $password");
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected sucessfully!";
       } 
    catch(PDOException $e){
        echo "Connection failed: ".$e->getMessage();
}
//routes for managing items in inventory
Flight::route('GET /items', function(){
    $db = Flight::db();
    $stmt = $db->query('SELECT * FROM inventory');
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Flight::json($items);
});

Flight::route('GET /item/@id', function($id){
    $db = Flight::db();
    $stmt = $db->prepare('SELECT * FROM inventory WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    Flight::json($item);
});

Flight::route('POST /item', function(){
    $db = Flight::db();
    $request = Flight::request();
    $data = $request->data->getData();
    
    $name = $data['itemName'];
    $description=$data['description'];
    $quantity = $data['quantity'];
    $price = $data['unitPrice'];
    $supplier=$data['supplierID'];
    $category=$data['categoryID'];
    $manufacturer=$data['manufacturer'];
    $voltageRating=$data['voltageRating'];
    $amperageRating=$data['amperageRating'];

    $stmt = $db->prepare('INSERT INTO inventory (name, description, quantity, price, supplier, category, manufacturer, voltageRating, amperageRating) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$name, $description, $quantity, $price, $supplier, $category, $manufacturer, $voltageRating, $amperageRating]);

    Flight::json(['message' => 'Item added successfully']);
});

Flight::route('PUT /item/@id', function($id){
    $db = Flight::db();
    $request = Flight::request();
    $data = $request->data->getData();
    
    $name = $data['itemName'];
    $description=$data['description'];
    $quantity = $data['quantity'];
    $price = $data['unitPrice'];
    $supplier=$data['supplierID'];
    $category=$data['categoryID'];
    $manufacturer=$data['manufacturer'];
    $voltageRating=$data['voltageRating'];
    $amperageRating=$data['amperageRating'];

    $stmt = $db->prepare('UPDATE inventory SET name = ?, description=?, quantity = ?, price = ?, supplier= ?, category = ?, manufacturer = ?, voltageRating = ?, amperageRating = ? WHERE id = ?');
    $stmt->execute([$name, $description, $quantity, $price, $supplier, $category, $manufacturer, $voltageRating, $amperageRating, $id]);

    Flight::json(['message' => 'Item updated successfully']);
});

Flight::route('DELETE /item/@id', function($id){
    $db = Flight::db();
    $stmt = $db->prepare('DELETE FROM inventory WHERE id = ?');
    $stmt->execute([$id]);

    Flight::json(['message' => 'Item deleted successfully']);
});
//routes for managing users
Flight::route('GET /user', function(){
    $db = Flight::db();
    $stmt = $db->query('SELECT * FROM user');
    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Flight::json($user);
});

Flight::route('GET /user/@id', function($id){
    $db = Flight::db();
    $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    Flight::json($user);
});

Flight::route('POST /user', function(){
    $db = Flight::db();
    $request = Flight::request();
    $data = $request->data->getData();
    
    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = $data['role'];
    $stmt = $db->prepare('INSERT INTO user (username, password, role) VALUES (?, ?, ?)');
    $stmt->execute([$username, $password, $role]);

    Flight::json(['message' => 'User added successfully']);
});

Flight::route('PUT /user/@id', function($id){
    $db = Flight::db();
    $request = Flight::request();
    $data = $request->data->getData();
    
    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = $data['role'];

    $stmt = $db->prepare('UPDATE user SET username = ?, password = ?, role=? WHERE id = ?');
    $stmt->execute([$username, $password,$role, $id]);

    Flight::json(['message' => 'User updated successfully']);
});

Flight::route('DELETE /user/@id', function($id){
    $db = Flight::db();
    $stmt = $db->prepare('DELETE FROM user WHERE id = ?');
    $stmt->execute([$id]);

    Flight::json(['message' => 'User deleted successfully']);
});

Flight::start();
?>