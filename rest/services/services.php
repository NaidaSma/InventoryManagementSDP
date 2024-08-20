<?php
require_once __DIR__."./../dao/BaseDao.php";

class Service {
    protected $dao;

    public function __construct(){
        $this->dao = new BaseDao();
    }

public function create_user($user){
    return $this->dao->create_user($user);
}
}
?>