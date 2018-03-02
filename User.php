<?php
require_once('config.php');

class User {
	private $username;
	private $hashed;
	private $isValid;
	private $conn;
    
	function __construct ($username, $password, &$connection) {
		$this->conn = $connection;
		$this->username = $username;
		$this->hashed = password_hash($password, PASSWORD_DEFAULT);
		$this->isValid = false;
        $this->_validate($password);
	}
	
	public function _validate($passw) {
    
            $this->conn->beginTransaction();
			$query = "SELECT password FROM users WHERE username = '".$this->username."' and status='approved';";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
            $this->conn->commit();
            
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if($result!=null){
				if(password_verify($passw, $result['password'])){
                    $this->isValid = true;
				}
			}
		} 
	
	function isValid(){
		return $this->isValid;
	}
		
	function getUsername(){
		return $this->username;
	}
	
	function getHashed(){
		return $this->hashed;
	}
};
?>
