<?php

require_once __DIR__ . '/../services/services.php';
require_once __DIR__ . '/../config.php';
Flight::set('services', new Service);
/**
 * @OA\Get(
 *   path="/connection-check",
 *   tags={"Utility"},
 *   summary="Check database connection",
 *   @OA\Response(
 *     response=200,
 *     description="Connection successful"
 *   )
 * )
 */
Flight::route('GET /connection-check', function(){
    new BaseDao();
});
/**
 * @OA\Get(
 *   path="/dashboard/data",
 *   tags={"Dashboard"},
 *   summary="Get dashboard data",
 *   @OA\Response(
 *     response=200,
 *     description="Number of categories and items retrieved successfully"
 *   )
 * )
 */

Flight::route('GET /dashboard/data', function()  {
    $data = Flight::get('services')->getDashboardData();
    Flight::json($data); 
});
/**
 * @OA\Get(
 *   path="/items-per-category",
 *   tags={"Dashboard"},
 *   summary="Get items per category data",
 *   @OA\Response(
 *     response=200,
 *     description="Items per category data retrieved successfully"
 *   )
 * )
 */

Flight::route('GET /items-per-category', function()  {
    $data = Flight::get('services')->getItemsPerCategoryData();
    Flight::json($data);
});
/**
 * @OA\Get(
 *   path="/low-stock-items",
 *   tags={"Dashboard"},
 *   summary="Get low stock items",
 *   @OA\Response(
 *     response=200,
 *     description="Low stock items retrieved successfully"
 *   )
 * )
 */

Flight::route('GET /low-stock-items', function () {
    $lowStockItems = Flight::get('services')->getLowStockItems();
    echo json_encode([
        'items' => $lowStockItems
    ]);
});


/**
 * @OA\Get(
 *   path="/users",
 *   tags={"Users"},
 *   summary="Get all users",
 *   @OA\Response(
 *     response=200,
 *     description="Array of all users"
 *   )
 * )
 */
Flight::route('GET /users', function(){
    $data = Flight::get('services')->getUsers();
    Flight::json($data);
});
/**
 * @OA\Get(
 *   path="/user/{userID}",
 *   tags={"Users"},
 *   summary="Get user by ID",
 *   @OA\Parameter(
 *     name="userID",
 *     in="path",
 *     required=true,
 *     description="ID of the user",
 *     @OA\Schema(type="number")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="User details"
 *   ),
 *   @OA\Response(
 *     response=404,
 *     description="User not found"
 *   )
 * )
 */
Flight::route('GET /user/@userID', function($userID) {
    $user = Flight::get('services')->getUserById($userID); 
    if ($user) {
        Flight::json($user);
    } else {
        Flight::halt(404, 'User not found');
    }
});
/**
 * @OA\Post(
 *   path="/user/add",
 *   tags={"Users"},
 *   summary="Add a new user",
 *   @OA\RequestBody(
 *     description="User data",
 *     required=true,
 *     @OA\JsonContent(
 *       @OA\Property(property="name", type="string"),
 *       @OA\Property(property="surname", type="string"),
 *       @OA\Property(property="username", type="string"),
 * @OA\Property(property="password", type="string"),
 *       @OA\Property(property="role", type="string")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="User added successfully"
 *   )
 * )
 */
Flight::route('POST /user/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->add_user($payload);
    Flight::json($data);
});
/**
 * @OA\Put(
 *   path="/user/update/{userID}",
 *   tags={"Users"},
 *   summary="Update user details",
 *   @OA\Parameter(
 *     name="userID",
 *     in="path",
 *     required=true,
 *     description="ID of the user to update",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\RequestBody(
 *     description="Updated user data",
 *     required=true,
 *     @OA\JsonContent(
 *       @OA\Property(property="name", type="string"),
 *       @OA\Property(property="surname", type="string"),
 *       @OA\Property(property="username", type="string"),
 *       @OA\Property(property="role", type="string")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="User updated successfully"
 *   )
 * )
 */

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
/**
 * @OA\Delete(
 *   path="/user/{userID}",
 *   tags={"Users"},
 *   summary="Delete a user",
 *   @OA\Parameter(
 *     name="userID",
 *     in="path",
 *     required=true,
 *     description="ID of the user to delete",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="User deleted successfully"
 *   )
 * )
 */

Flight::route('DELETE /user/@userID', function($userID){
    $data = Flight::get('services')->deleteUser($userID);
    Flight::json($data);
});





//item routes
/**
 * @OA\Get(
 *   path="/items",
 *   tags={"Items"},
 *   summary="Get all items",
 *   @OA\Response(
 *     response=200,
 *     description="Array of all items"
 *   )
 * )
 */

Flight::route('GET /items', function(){
    $data = Flight::get('services')->getInventory();
    Flight::json($data);
});
/**
 * @OA\Get(
 *   path="/items/{itemID}",
 *   tags={"Items"},
 *   summary="Get item by ID",
 *   @OA\Parameter(
 *     name="itemID",
 *     in="path",
 *     required=true,
 *     description="ID of the item",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Item details"
 *   ),
 *   @OA\Response(
 *     response=404,
 *     description="Item not found"
 *   )
 * )
 */

Flight::route('GET /items/@itemID', function($itemID){
    $item = Flight::get('services')->getItemById($itemID);
    if ($item) {
        Flight::json($item); 
    } else {
        Flight::halt(404, 'Item not found'); 
    }
});


/**
 * @OA\Post(
 *   path="/item/add",
 *   tags={"Items"},
 *   summary="Add a new item",
 *   @OA\RequestBody(
 *     description="Item data",
 *     required=true,
 *     @OA\JsonContent(
 *       @OA\Property(property="itemName", type="string"),
 *       @OA\Property(property="unitPrice", type="number"),
 *       @OA\Property(property="quantity", type="integer"),
 *       @OA\Property(property="description", type="string")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Item added successfully"
 *   )
 * )
 */

Flight::route('POST /item/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addItem($payload);
    Flight::json($data);
});
/**
 * @OA\Put(
 *   path="/item/update/{itemID}",
 *   tags={"Items"},
 *   summary="Update item details",
 *   @OA\Parameter(
 *     name="itemID",
 *     in="path",
 *     required=true,
 *     description="ID of the item to update",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\RequestBody(
 *     description="Updated item data",
 *     required=true,
 *     @OA\JsonContent(
 *       @OA\Property(property="itemName", type="string"),
 *       @OA\Property(property="unitPrice", type="number"),
 *       @OA\Property(property="quantity", type="integer"),
 *       @OA\Property(property="description", type="string")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Item updated successfully"
 *   )
 * )
 */
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
/**
 * @OA\Delete(
 *   path="/item/{itemID}",
 *   tags={"Items"},
 *   summary="Delete an item",
 *   @OA\Parameter(
 *     name="itemID",
 *     in="path",
 *     required=true,
 *     description="ID of the item to delete",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Item deleted successfully"
 *   )
 * )
 */
Flight::route('DELETE /item/@itemID', function($itemID){
    $data = Flight::get('services')->deleteItem($itemID);
    Flight::json($data);
    error_log("Deleting item with ID: " . $itemID);
   
});






//category routes
/**
 * @OA\Get(
 *   path="/categories",
 *   tags={"Categories"},
 *   summary="Get all categories",
 *   @OA\Response(
 *     response=200,
 *     description="Array of all categories"
 *   )
 * )
 */

Flight::route('GET /categories', function(){
    $data = Flight::get('services')->getAllCategories();
    Flight::json($data);
});
/**
 * @OA\Get(
 *   path="/categories/{categoryid}",
 *   tags={"Categories"},
 *   summary="Get category by ID",
 *   @OA\Parameter(
 *     name="categoryid",
 *     in="path",
 *     required=true,
 *     description="ID of the category",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Category details"
 *   ),
 *   @OA\Response(
 *     response=404,
 *     description="Category not found"
 *   )
 * )
 */
Flight::route('GET /categories/@categoryid', function($categoryid){
    $data = Flight::get('services')->getCategoryById($categoryid);
    Flight::json($data);
});
/**
 * @OA\Post(
 *   path="/categories/add",
 *   tags={"Categories"},
 *   summary="Add a new category",
 *   @OA\RequestBody(
 *     description="Category data",
 *     required=true,
 *     @OA\JsonContent(
 *       @OA\Property(property="name", type="string", description="Name of the category"),
 *       @OA\Property(property="description", type="string", description="Description of the category")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Category added successfully"
 *   )
 * )
 */

Flight::route('POST /categories/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addCategory($payload);
    Flight::json($data);
});

/**
 * @OA\Delete(
 *   path="/category/{categoryid}",
 *   tags={"Categories"},
 *   summary="Delete a category",
 *   @OA\Parameter(
 *     name="categoryid",
 *     in="path",
 *     required=true,
 *     description="ID of the category to delete",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Category deleted successfully"
 *   )
 * )
 */
Flight::route('DELETE /category/@categoryid', function($categoryid){
    $data = Flight::get('services')->deleteCategory($categoryid);
    Flight::json($data);
    error_log("Deleting category with ID: " . $categoryid);
   
});





//supplier routes
/**
 * @OA\Get(
 *   path="/suppliers",
 *   tags={"Suppliers"},
 *   summary="Get all suppliers",
 *   @OA\Response(
 *     response=200,
 *     description="Array of all suppliers"
 *   )
 * )
 */

Flight::route('GET /suppliers', function(){
    $data = Flight::get('services')->getAllSuppliers();
    Flight::json($data);
});
/**
 * @OA\Get(
 *   path="/supplier/{supplierid}",
 *   tags={"Suppliers"},
 *   summary="Get supplier by ID",
 *   @OA\Parameter(
 *     name="supplierid",
 *     in="path",
 *     required=true,
 *     description="ID of the supplier",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Supplier details"
 *   ),
 *   @OA\Response(
 *     response=404,
 *     description="Supplier not found"
 *   )
 * )
 */

Flight::route('GET /supplier/@supplierid', function($supplierid){
    $data = Flight::get('services')->getSupplierById($supplierid);
    Flight::json($data);
});

/**
 * @OA\Post(
 *   path="/suppliers/add",
 *   tags={"Suppliers"},
 *   summary="Add a new supplier",
 *   @OA\RequestBody(
 *     description="Supplier data",
 *     required=true,
 *     @OA\JsonContent(
 *       @OA\Property(property="name", type="string", description="Name of the supplier"),
 *       @OA\Property(property="address", type="string", description="Address of the supplier"),
 *       @OA\Property(property="phone", type="string", description="Phone number of the supplier")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Supplier added successfully"
 *   )
 * )
 */

Flight::route('POST /suppliers/add', function(){
    $payload = Flight::request()->data->getData();

    $data = Flight::get('services')->addSupplier($payload);
    Flight::json($data);
    error_log("Received supplier data: " . json_encode($data));

});
/**
 * @OA\Delete(
 *   path="/supplier/{supplierid}",
 *   tags={"Suppliers"},
 *   summary="Delete a supplier",
 *   @OA\Parameter(
 *     name="supplierid",
 *     in="path",
 *     required=true,
 *     description="ID of the supplier to delete",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Supplier deleted successfully"
 *   )
 * )
 */
Flight::route('DELETE /supplier/@supplierid', function($supplierid){
    $data = Flight::get('services')->deleteSupplier($supplierid);
    Flight::json($data);
    error_log("Deleting supplier with ID: " . $supplierid);
   
});

//order routes
/**
 * @OA\Get(
 *   path="/orders",
 *   tags={"Orders"},
 *   summary="Get all orders",
 *   @OA\Response(
 *     response=200,
 *     description="Array of all orders"
 *   )
 * )
 */

Flight::route('GET /orders', function(){
    $data = Flight::get('services')->getOrders();
    Flight::json($data);
});
/**
 * @OA\Post(
 *   path="/updateShipmentStatus",
 *   tags={"Orders"},
 *   summary="Update shipment status",
 *   @OA\RequestBody(
 *     description="Shipment status data",
 *     required=true,
 *     @OA\JsonContent(
 *       @OA\Property(property="shipmentid", type="integer", description="ID of the shipment"),
 *       @OA\Property(property="status", type="string", description="Updated status of the shipment")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Shipment status updated successfully"
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Invalid input"
 *   ),
 *   @OA\Response(
 *     response=500,
 *     description="Failed to update shipment status"
 *   )
 * )
 */
Flight::route('POST /updateShipmentStatus', function() {
    $requestData = Flight::request()->data->getData();
    
    if (!isset($requestData['shipmentid']) || !isset($requestData['status'])) {
        Flight::json(['error' => 'Invalid input'], 400);
        return;
    }

    $shipmentId = $requestData['shipmentid'];
    $status = $requestData['status'];

    $services = Flight::get('services'); 
    $success = $services->updateShipmentStatus($shipmentId, $status);

    if ($success) {
        Flight::json(['message' => 'Shipment status updated successfully']);
    } else {
        Flight::json(['error' => 'Failed to update shipment status'], 500);
    }
});
/**
 * @OA\Delete(
 *   path="/order/{shipmentid}",
 *   tags={"Orders"},
 *   summary="Delete an order",
 *   @OA\Parameter(
 *     name="shipmentid",
 *     in="path",
 *     required=true,
 *     description="ID of the order to delete",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Order deleted successfully"
 *   )
 * )
 */
Flight::route('DELETE /order/@shipmentid', function($shipmentId){
    $data = Flight::get('services')->deleteOrder($shipmentId);
    Flight::json($data);
    error_log("Deleting order with ID: " . $shipmentId);
   
});



?>
