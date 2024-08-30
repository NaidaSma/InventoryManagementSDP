<?php
require_once __DIR__."/../dao/BaseDao.php";

class Service {
    protected $dao;

    public function __construct(){
        $this->dao = new BaseDao();
    }

public function create_user($user){
    return $this->dao->create_user($user);
}
 public function get_profile_info($userID){

     return $this->dao->get_profile_info($userID);

}



//item services
public function getAllItems() {
    return $this->dao->getAllItems();
   
}
public function getItemById($id) {
    return $this->dao->getItemById($id);
    if ($item) {
        Flight::json($item);
    } else {
        Flight::halt(404, "Item not found");
    }
}
public function addItem($data) {
    return $this->dao->addItem($data);
    //Flight::json(['message' => 'Item added successfully']);
}
public function updateItem($id, $data) {
    $item = $this->dao->getItemById($id);
    if ($item) {
        $this->dao->updateItem($id, $data);
        Flight::json(['message' => 'Item updated successfully']);
    } else {
        Flight::halt(404, "Item not found");
    }
}
public function deleteItem($id) {
    $item = $this->dao->getItemById($id);
    if ($item) {
        $this->dao->deleteItem($id);
        Flight::json(['message' => 'Item deleted successfully']);
    } else {
        Flight::halt(404, "Item not found");
    }
}


//category services 
public function getAllCategories() {
    return $this->dao->getAllCategories();
}

public function getCategoryById($id) {
    return $this->dao->getCategoryById($id);
    if ($item) {
        Flight::json($item);
    } else {
        Flight::halt(404, "Item not found");
    }
}
public function addCategory($category) {
    return $this->dao->addCategory($category);
   // Flight::json(['message' => 'Category added successfully']);
}

public function deleteCategory($id) {
    $category = $this->dao->getCategoryById($id);
    if ($category) {
        $this->dao->deleteCategory($id);
        Flight::json(['message' => 'Category deleted successfully']);
    } else {
        Flight::halt(404, "Category not found");
    }
}
}



?>