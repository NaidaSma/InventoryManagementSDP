<?php

require_once __DIR__ . '/../services/services.php';
require_once __DIR__ . '/../config.php';
Flight::set('services', new Service);

Flight::route('GET /connection-check', function(){
    new BaseDao();
});

Flight::route('GET /dashboard/data', function()  {
    $data = Flight::get('services')->getDashboardData();
    Flight::json($data); 
});

Flight::route('GET /items-per-category', function()  {
    $data = Flight::get('services')->getItemsPerCategoryData();
    Flight::json($data);
});
Flight::route('GET /low-stock-items', function () {
    $lowStockItems = Flight::get('services')->getLowStockItems();
    echo json_encode([
        'items' => $lowStockItems
    ]);
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
    $payload = json_decode(file_get_contents("php://input"), true); 

    if (!$payload || !isset($payload['name']) || !isset($payload['surname']) || !isset($payload['username']) || !isset($payload['role'])) {
        Flight::json(['error' => 'Invalid input'], 400);
        return;
    }

    $success = Flight::get('services')->updateUser($userID, $payload);
    if ($success) {
        Flight::json(['message' => 'User updated successfully']);
    } else {
        Flight::halt(400, 'Error updating user or no changes made');
    }
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
    $item = Flight::get('services')->getItemById($itemID);
    if ($item) {
        Flight::json($item); 
    } else {
        Flight::halt(404, 'Item not found'); 
    }
});

Flight::route('POST /item/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addItem($payload);
    Flight::json($data);
});

Flight::route('PUT /item/update/@itemID', function($itemID){
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data || !isset($data['itemName']) || !isset($data['unitPrice']) || !isset($data['quantity']) || !isset($data['description'])) {
        Flight::json(['error' => 'Invalid input'], 400);
        return;
    }

    $result = Flight::get('services')->updateItem($itemID, $data);

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

Flight::route('POST /updateShipmentStatus', function() {
    $requestData = Flight::request()->data->getData();
    
    if (!isset($requestData['shipmentid']) || !isset($requestData['status'])) {
        Flight::json(['error' => 'Invalid input'], 400);
        return;
    }

    $shipmentId = $requestData['shipmentid'];
    $status = $requestData['status'];

    $services = Flight::get('service');
    $success = $services->updateShipmentStatus($shipmentId, $status);

    if ($success) {
        Flight::json(['message' => 'Shipment status updated successfully']);
    } else {
        Flight::json(['error' => 'Failed to update shipment status'], 500);
    }
});

?>
