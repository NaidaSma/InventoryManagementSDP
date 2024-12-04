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

     //user profile
     public function updateUserProfile($userID, $user) {
        $query = "UPDATE user SET name = :name, surname = :surname, username = :username WHERE userID = :userID";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);  
        $stmt->bindParam(':name', $user['name']);
        $stmt->bindParam(':surname', $user['surname']);
        $stmt->bindParam(':username', $user['username']);
    
        return $stmt->execute(); 
    }
    //making orders
    public function addOrder($data) {
        $query = "INSERT INTO shipments (shipmentid, userid, address, status, date) 
                  VALUES (:shipmentid, :userid, :address, :status, NOW())";
                   $stmt = $this->conn->prepare($query);
                   $stmt->bindParam(':shipmentid', $data['shipmentid']);
                   $stmt->bindParam(':userid', $data['userid']);
                   $stmt->bindParam(':address', $data['address']);
                   $stmt->bindParam(':status', $data['status']);
                   $stmt->execute();
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            
      }
}

?>