<?php
require_once __DIR__."/../dao/auth_dao.php";

class AuthService {
    protected $auth_dao;

    public function __construct(){
        $this->auth_dao = new AuthDao();
    }

    public function get_user_by_username($username){
        return $this->auth_dao->get_user_by_username($username);
    }
}