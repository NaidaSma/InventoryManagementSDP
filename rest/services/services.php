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

public function updateUser($id, $data) {
    if (empty($data['name']) || empty($data['surname']) || empty($data['username']) || empty($data['role'])) {
        throw new Exception('Some fields are missing');
    }

    return $this->dao->updateUser($id, $data);
}
public function deleteUser($userID) {
    return $this->dao->deleteUser($userID);
}






//item services
public function getInventory() {
    return $this->dao->getInventory();
}
public function getItemById($itemID) {
    return $this->dao->getItemById($itemID);
    if ($item) {
        Flight::json($item);
    } else {
        Flight::halt(404, "Item not found");
    }
}
public function addItem($item) {
    return $this->dao->addItem($item);
   
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