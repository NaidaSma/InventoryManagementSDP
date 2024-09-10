<?php
require_once __DIR__."/../dao/BaseDao.php";

class Service {
    protected $dao;

    public function __construct(){
        $this->dao = new BaseDao();
    }




public function getUsers() {
        return $this->dao->getUsers();
       
}
public function getUserById($userID) {
    return $this->dao->getUserById($userID); 
}
public function add_user($user){
    return $this->dao->add_user($user);
}

public function updateUser($userID, $data) {
    if (empty($data['name']) || empty($data['surname']) || empty($data['username']) || empty($data['role'])) {
        throw new Exception('Some fields are missing');
    }

    return $this->dao->updateUser($userID, $data);
}
public function deleteUser($userID) {
    return $this->dao->deleteUser($userID);
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

public function addItem($item) {
    return $this->dao->addItem($item);
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

public function getCategoryById($categoryid) {
    return $this->dao->getCategoryById($categoryid);
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

public function deleteCategory($categoryid) {
    return $this->dao->deleteCategory($categoryid);
}





//supplier services 
public function getAllSuppliers() {
    return $this->dao->getAllSuppliers();
}
public function getSupplierById($supplierid) {
    return $this->dao->getSupplierById($supplierid); 
}
public function addSupplier($supplier) {
    return $this->dao->addSupplier($supplier);

}
public function deleteSupplier($supplierid) {
    return $this->dao->deleteSupplier($supplierid);
}
}

?>