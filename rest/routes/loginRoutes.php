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
Flight::route('POST /create_user', function(){
    $name = Flight::request()->data['name'];
    $surname = Flight::request()->data['surname'];
    $username = Flight::request()->data['username'];
    $password = Flight::request()->data['password'];
    $role = Flight::request()->data['role'];

    // Replace 'your_db_host', 'your_db_name', 'your_db_user', and 'your_db_password' with actual database credentials
    $db = new PDO('mysql:host=your_db_host;dbname=your_db_name', 'your_db_user', 'your_db_password');
    $stmt = $db->prepare("INSERT INTO user (name, surname, username, password, role) VALUES (:name, :surname, :username, :password, :role)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        // User created successfully, redirect to login page or some other page
        Flight::redirect('/login');
    } else {
        // User creation failed, redirect back to create user page with error message
        Flight::redirect('/create_user?error=1');
    }
});

Flight::route('POST /login', function(){
    $username = Flight::request()->data['username'];
    $password = Flight::request()->data['password'];

    // Query the database to check if the user exists and the password is correct
    $db = Flight::db();
    $stmt = $db->prepare("SELECT * FROM user WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        
        Flight::redirect('/dashboard');
    } else {
        
        echo "Invalid username or password.";
    }
});
Flight::start();
?>