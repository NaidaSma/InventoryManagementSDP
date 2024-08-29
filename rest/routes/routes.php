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

Flight::route('GET /inventory', function(){
    $data = Flight::get('services')->getAllItems();
    Flight::json($data);
    
});


Flight::route('GET /inventory/@id', function($id){
    $data = Flight::get('services')->getItemById($id);
    Flight::json($data);
   
});


Flight::route('POST /inventory', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addItem($payload);
    Flight::json($data);
});


Flight::route('PUT /inventory/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::inventoryService()->updateItem($id, $data);
});


Flight::route('DELETE /inventory/@id', function($id){
    Flight::inventoryService()->deleteItem($id);
});

?>
