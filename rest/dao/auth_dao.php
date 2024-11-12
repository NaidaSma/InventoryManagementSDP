<?php
require_once __DIR__ . '/BaseDao.php';
require_once __DIR__ . '/../config.php';
class AuthDao extends BaseDao{
    public function __construct(){
        parent::__construct('user');
    }
    public function get_user_by_username($username){
        $query="SELECT * FROM user WHERE username=:username";
        return $this->query_unique($query, ['username'=>$username]);
    }
}

?>