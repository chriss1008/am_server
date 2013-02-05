<?php

Class Authcheck {
	
	private $db = null;
	
	
	function __construct() {
		include "./config/config.php";
		include_once "./lib/pdodb.php";
		
		$this->db = new PDODB();
		$res = $this->db->connect( $_DB['host'], $_DB['dbname'], $_DB['username'], $_DB['password'] );
		
		if( !$res )
			self::exceptionResponse(500, "Server maintenance");
	}
	
	
	function userLogIn() {
	
	}
	
	
	function userLogOut() {
		
	}
	
	
	function isAdmin() {
	
	}
	
	
	function isUserLoggedIn() {
	
	}
	
	
	function isRegistered() {
		
	}
	
	
	function isValidedUser() {
		
	}
	
	
	function checkEmailExist($email)
  {
  	$sql = "SELECT 
  						id, 
  						email 
  					FROM 
  						agent_metrics.account 
  					WHERE 
  						email=?";
  	 
  	$result = $this->db->query($sql, array($email));
  	
  	if( $result )
  		return TRUE;
  	else
  		return FALSE;
  }
  
  
  function updateUserSession($id) {
  	
  }
	
	
	
}


?>