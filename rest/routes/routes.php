<?php

require_once __DIR__ . '/../services/services.php';

Flight::set('services', new Service);

Flight::route('GET /connection-check', function(){
    new BaseDao();
});

/*Flight::route('POST /create_user', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->create_user($payload);
    Flight::json($data);
});
*/
Flight::route('GET /profile/@userID', function($userID){
   
   $data = Flight::get('services')->get_profile_info($userID);
    Flight::json($data);

});


//item routes
Flight::route('GET /inventory', function(){
    $data = Flight::get('services')->getAllItems();
    Flight::json($data);
    
});
Flight::route('GET /inventory/@id', function($id){
    $data = Flight::get('services')->getItemById($id);
    Flight::json($data);
   
});

Flight::route('POST /inventory/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addItem($payload);
    Flight::json($data);
});

Flight::route('PUT /inventory/@id', function($id){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->updateItem($payload);
    Flight::json($data);
    
});

Flight::route('DELETE /inventory/@id', function($id){
    $data = Flight::get('services')->deleteItem($id);
    Flight::json($data);
});



//category routes
Flight::route('GET /categories', function(){
    $data = Flight::get('services')->getAllCategories();
    Flight::json($data);
});

Flight::route('GET /categories/@id', function($id){
    $data = Flight::get('services')->getCategoryById($id);
    Flight::json($data);
});

Flight::route('POST /categories/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addCategory($payload);
    Flight::json($data);
});


Flight::route('DELETE /categories/@id', function($id){
    Flight::services()->deleteCategory($id);
});


//supplier routes
Flight::route('GET /suppliers', function(){
    $data = Flight::get('services')->getAllSuppliers();
    Flight::json($data);
});
Flight::route('POST /suppliers/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addSupplier($payload);
    Flight::json($data);
    error_log("Received supplier data: " . json_encode($data));

});


Flight::route('DELETE /suppliers/@id', function($id){
    Flight::services()->deleteSupplier($id);
    Flight::json(["message" => "Supplier deleted"]);
});
?>
