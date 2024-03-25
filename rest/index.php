<?php

require_once 'vendor/autoload.php';

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

// Home route
Flight::route('/', function(){
    Flight::redirect('/items');
});

// Items Routes

Flight::route('GET /items', function(){
    $db = Flight::db();
    $stmt = $db->query('SELECT * FROM inventory');
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Flight::render('items.php', array('title' => 'Items', 'items' => $items));
});

Flight::route('GET /item/@id', function($id){
    $db = Flight::db();
    $stmt = $db->prepare('SELECT * FROM inventory WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    Flight::render('items.php', array('title' => 'Item Details', 'item' => $item));
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

    Flight::redirect('/items');
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

    Flight::redirect('/item/' . $id);
});

Flight::route('DELETE /item/@id', function($id){
    $db = Flight::db();
    $stmt = $db->prepare('DELETE FROM inventory WHERE id = ?');
    $stmt->execute([$id]);

    Flight::redirect('/items');
});


Flight::start();

?>