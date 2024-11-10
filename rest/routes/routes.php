<?php

require_once __DIR__ . '/../services/services.php';

Flight::set('services', new Service);

Flight::route('GET /connection-check', function(){
    new BaseDao();
});



 /**
     * @OA\Get(
     *      path="/users",
     *      tags={"users"},
     *      summary="Get all users",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all users in the databases"
     *      )
     * )
     */
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
Flight::route('PUT /user/update/@userID', function($userID){
    $payload = Flight::request()->data->getData();
    Flight::services()->updateUser($userID, $payload);
    Flight::json(['message' => 'User updated successfully']);
});
Flight::route('DELETE /user/@userID', function($userID){
    $data = Flight::get('services')->deleteUser($userID);
    Flight::json($data);
});


//item routes
 /**
     * @OA\Get(
     *      path="/items",
     *      tags={"items"},
     *      summary="Get all items",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all items in the database"
     *      )
     * )
     */
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

Flight::route('PUT /item/update', function(){
    $payload = Flight::request()->data->getData();
    
   
    $result = Flight::get('services')->updateItem($payload);

    if ($result) {
        Flight::json(['message' => 'Item updated successfully']);
    } else {
        Flight::halt(400, 'Error updating item');
    }
});

Flight::route('DELETE /item/@itemID', function($itemID){
    $data = Flight::get('services')->deleteItem($itemID);
    Flight::json($data);
    error_log("Deleting item with ID: " . $itemID);
   
});






//category routes
 /**
     * @OA\Get(
     *      path="/categories",
     *      tags={"categories"},
     *      summary="Get all categories",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all categories in the databases"
     *      )
     * )
     */
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
 /**
     * @OA\Get(
     *      path="/suppliers",
     *      tags={"suppliers"},
     *      summary="Get all suppliers",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all suppliers in the databases"
     *      )
     * )
     */
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

//order routes
 /**
     * @OA\Get(
     *      path="/orders",
     *      tags={"orders"},
     *      summary="Get all orders",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all orders in the databases"
     *      )
     * )
     */
Flight::route('GET /orders', function(){
    $data = Flight::get('services')->getOrders();
    Flight::json($data);
});

Flight::route('POST /orders/status', function(){
    $data = Flight::request()->data->getData();
    $shipmentid = $data['shipmentid'];
    $status = $data['status'];
    
    Flight::services()->updateOrderStatus($shipmentid, $status);
    Flight::json(['message' => 'Order status updated successfully']);
});


?>
