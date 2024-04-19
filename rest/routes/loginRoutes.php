<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
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
    $login = Flight::request()->data->getData();
    $user = Flight::userDao()->get_user_by_username($login['username']);
    if (isset($user['userID'])){
      if($user['password'] == md5($login['password'])){
        unset($user['password']);
        $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
        Flight::json(['token' => $jwt]);
      }else{
        Flight::json(["message" => "Wrong password"], 404);
      }
    }else{
      Flight::json(["message" => "User doesn't exist"], 404);
    }
});
Flight::start();

?>