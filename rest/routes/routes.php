<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
require_once __DIR__ . '/../services/services.php';

Flight::set('services', new Service);

Flight::route('GET /connection-check', function(){
    new BaseDao();
});
Flight::route('POST /login', function() {
    // Retrieve the input data (username and password) from the request body
    $login = Flight::request()->data->getData();
    $user=Flight::services()->getUsers($login['username']);
    if(count($user)>0){
        $user=$user[0];
    }
    if(isset($user['userID'])){
        if($user['password']==md5($login['password'])){
            unset($user['password']);
            $user['is_admin']= false;
            //$jwt=JWT::encode($user, Config::JWT_SECRET(), 'HS256');
           // Flight::json(['token'=>$jwt]);

        }
        else{
            Flight::json(["message"=>"Wrong password"], 404);
        }
    }
    else{Flight::json(["message"=>"User doesn't exist"], 404);

    }});



Flight::route('GET /users', function(){
    $data = Flight::get('services')->getUsers();
    Flight::json($data);
});
Flight::route('GET /user/@userID', function($userID) {
    $user = Flight::get('services')->getUserById($userID); 
    if ($user) {
        Flight::json($user);
    } else {
        Flight::halt(404, 'User not found');
    }
});
Flight::route('POST /user/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->add_user($payload);
    Flight::json($data);
});
Flight::route('PUT /user/@userID', function($id){
    $payload = Flight::request()->data->getData(); 
    Flight::services()->updateUser($id, $payload);  
    Flight::json(['message' => 'User updated successfully']);
});
Flight::route('DELETE /user/@userID', function($userID){
    $data = Flight::get('services')->deleteUser($userID);
    Flight::json($data);
});


//item routes
Flight::route('GET /items', function(){
    $data = Flight::get('services')->getInventory();
    Flight::json($data);
});

Flight::route('GET /items/@itemID', function($itemID){
    $data = Flight::get('services')->getItemById($itemID);
    Flight::json($data);
});

Flight::route('POST /item/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addItem($payload);
    Flight::json($data);
});







//category routes
Flight::route('GET /categories', function(){
    $data = Flight::get('services')->getAllCategories();
    Flight::json($data);
});

Flight::route('GET /categories/@categoryid', function($categoryid){
    $data = Flight::get('services')->getCategoryById($categoryid);
    Flight::json($data);
});

Flight::route('POST /categories/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addCategory($payload);
    Flight::json($data);
});


Flight::route('DELETE /category/@categoryid', function($categoryid){
    $data = Flight::get('services')->deleteCategory($categoryid);
    Flight::json($data);
    error_log("Deleting category with ID: " . $categoryid);
   
});





//supplier routes
Flight::route('GET /suppliers', function(){
    $data = Flight::get('services')->getAllSuppliers();
    Flight::json($data);
});

Flight::route('GET /supplier/@supplierid', function($supplierid){
    $data = Flight::get('services')->getSupplierById($supplierid);
    Flight::json($data);
});

Flight::route('POST /suppliers/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addSupplier($payload);
    Flight::json($data);
    error_log("Received supplier data: " . json_encode($data));

});

Flight::route('DELETE /supplier/@supplierid', function($supplierid){
    $data = Flight::get('services')->deleteSupplier($supplierid);
    Flight::json($data);
    error_log("Deleting category with ID: " . $supplierid);
   
});

?>
