<?php

class Account extends Control implements RESTfulInterface 
{
	private $db = null;
	private $table = "agent_metrics.account";
	
	function __construct()
	{
		require "./config/config.php";
		require "./lib/pdodb.php";
		
		$this->db = new PDODB();
		$res = $this->db->connect( $_DB['host'], $_DB['dbname'], $_DB['username'], $_DB['password'] );
		
		if( !$res )
			self::exceptionResponse(500, "Server maintenance");
	}
	
	
	function restGet( $segments )
	{
		$id = $segments[0];

		$sql = "SELECT * FROM ".$this->table." WHERE id=?";
  	
  	$result = $this->db->query($sql, array($id));
		
		if( !$result )
			self::exceptionResponse(404, 'Not found');
		
		//account info
		$data['id'] = $result['id'];
		$data['account'] = $result['account'];
		$data['name'] = $result['name'];
		$data['title'] = $result['title'];
		$data['email'] = $result['email'];
		$data['permission'] = $result['permission'];
		$data['team_id'] = $result['team_id'];
		$data['group_id'] = $result['group_id'];
		
		print_r( json_encode($data) );
		
	}
	
	
	
	function restPost($segments) 
	{
		$data = json_decode( file_get_contents('php://input'), true );
		
		if( !is_array($data) )
			self::exceptionResponse(400, "Request is not a valid json format. ");
		
		//check account and password exist
		$is_register = $this->check_email_exist($data["email"]);
		if($is_register) 
			self::exceptionResponse(400, "This email is already registered. ");
		
		
		$account['account'] = $data["account"];
		$account['password'] = $data["password"];
		$account['name'] = $data["name"];
		$account['title'] = $data["title"];
		$account['email'] = $data["email"];
		$account['permission'] = $data["permission"];
		$account['team_id'] = $data["team_id"];
		$account['group_id'] = $data["group_id"];
		$account['create_time'] = time();
		
		$result = $this->db->insert($this->table, $account);
		
		if( $result ) {
			$res['id'] = $result;

			print_r(json_encode( array("id"=>$result) ));
		}
		else {
			self::exceptionResponse(500, "can not insert data into DB!");
		}
		
  }
  
  
  function restPut($segments)
  {
  	$data = json_decode( file_get_contents('php://input'), true );
  	//var_dump($data);
  	 
  	if( !is_array($data) )
  		self::exceptionResponse(400, "Request is not a valid json format:" . file_get_contents('php://input'));
  	 
  	if( !isset($data["id"]) or empty($data["id"]) )
  		self::exceptionResponse(400, "Request is not a valid json format:" . file_get_contents('php://input'));
  	
  	
  	$account['account'] = $data["account"];
		$account['password'] = $data["password"];
		$account['name'] = $data["name"];
		$account['title'] = $data["title"];
		$account['permission'] = $data["permission"];
		$account['team_id'] = $data["team_id"];
		$account['group_id'] = $data["group_id"];
		$account['modify_time'] = time();
  	
  	$result = $this->db->update($this->table, $account, array("id"=>$data["id"]));
  	
  	if( $result ) {
  		echo TRUE;
  	}
  	else {
  		self::exceptionResponse(500, "can not insert data into DB!");
  	}
  }
  
  
  function restDelete($segments)
  {
  	$result = $this->db->delete($this->table, array("id"=>$segments[0]));
  	
  	if( !$result )
  		self::exceptionResponse(500, "can not insert data into DB!");
  	else
  		echo TRUE;
  }
  
  
  function check_email_exist($email)
  {
  	$sql = "SELECT id, email FROM ".$this->table." WHERE email=?";
  	 
  	$result = $this->db->query($sql, array($email));
  	
  	if( $result )
  		return TRUE;
  	else
  		return FALSE;
  }
 
	
}
?>