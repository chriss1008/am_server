<?php

class Schedule extends Control implements RESTfulInterface 
{
	private $db = null;
	
	function __construct() {
		
		require "./config/config.php";
		require "./lib/pdodb.php";
		
		$this->db = new PDODB();
		$res = $this->db->connect( $_DB['host'], $_DB['dbname'], $_DB['username'], $_DB['password'] );
		
		if( !$res )
			self::exceptionResponse(500, "Server maintenance");
			
	}
	
	
	function restGet($segments) {
		
		//check segment's lenth for choosing function
		$result = null;
		$function = $segments[0];
		
		switch($function) {
			case "partial":		//get partial info
				$result = $this->getCustomerPartial();
				break;
				
			default:
				$result = $this->getCustomer($segments[0]);
				break;
		}
		
		if( !$result )
			self::exceptionResponse(404, 'Not found');
		else 
			print_r(json_encode($result));
		
	}
	
	
	function restPost($segments) {
		$data = json_decode( file_get_contents('php://input'), true );
		
		if( !is_array($data) )
			self::exceptionResponse(400, "Request is not a valid json format:" . file_get_contents('php://input'));
		
		$customer['name'] = $data["name"];
		$customer['address'] = $data["address"];
		$customer['identify_no'] = $data["identify_no"];
		$customer['birthday'] = $data["birthday"];
		$customer['cellphone'] = $data["cellphone"];
		$customer['email'] = $data["email"];
		$customer['gender'] = $data["gender"];
		$customer['marriage'] = $data["marriage"];
		$customer['telephone'] = $data["telephone"];
		//$customer['thumbnail'] = $data["thumbnail"];
		//$customer['fb_id'] = $data["fb_id"];
		$customer['agent_id'] = $data['agent_id'];
		$customer['child_boy'] = $data["child"]['boy'];
		$customer['child_girl'] = $data["child"]['girl'];
		$customer['company_name'] = $data["company"]['name'];
		$customer['company_address'] = $data["company"]["address"];
		$customer['company_phone'] = $data["company"]["phone"];
		$customer['company_fax'] = $data["company"]["fax"];
		$customer['company_job_desc'] = $data["company"]["job_desc"];
		$customer['company_category'] = $data["company"]["category"];
		$customer['company_title'] = $data["company"]["title"];
		$customer['company_worktime_start'] = $data["company"]["worktime_start"];
		$customer['company_worktime_end'] = $data["company"]["worktime_end"];
		$customer['create_time'] = time();
		
		$result = $this->db->insert("agent_metrics.customer", $customer);

		if( $result ) {
			$res['id'] = $result;
			
			$this->insertEvaluation($res['id'], $data["evaluation"]);
			$this->insertTags($res['id'], $data["tags"]);
			
			print_r(json_encode( array("id"=>$result) ));
		}
		else {
			self::exceptionResponse(500, "can not insert data into DB!");
		}
  }
	
 
  function restPut($segments) {
  	$data = json_decode( file_get_contents('php://input'), true );
  	//var_dump($data);
  	
  	if( !is_array($data) )
			self::exceptionResponse(400, "Request is not a valid json format:" . file_get_contents('php://input'));
  	
  	if( !isset($data["id"]) or empty($data["id"]) )
  		self::exceptionResponse(400, "Request is not a valid json format:" . file_get_contents('php://input'));
  	
		$customer['name'] = $data["name"];
		$customer['address'] = $data["address"];
		$customer['identify_no'] = $data["identify_no"];
		$customer['birthday'] = $data["birthday"];
		$customer['cellphone'] = $data["cellphone"];
		$customer['email'] = $data["email"];
		$customer['gender'] = $data["gender"];
		$customer['marriage'] = $data["marriage"];
		$customer['telephone'] = $data["telephone"];
		//$customer['thumbnail'] = $data["thumbnail"];
		//$customer['fb_id'] = $data["fb_id"];
		$customer['agent_id'] = $data['agent_id'];
		$customer['child_boy'] = $data["child"]['boy'];
		$customer['child_girl'] = $data["child"]['girl'];
		$customer['company_name'] = $data["company"]['name'];
		$customer['company_address'] = $data["company"]["address"];
		$customer['company_phone'] = $data["company"]["phone"];
		$customer['company_fax'] = $data["company"]["fax"];
		$customer['company_job_desc'] = $data["company"]["job_desc"];
		$customer['company_category'] = $data["company"]["category"];
		$customer['company_title'] = $data["company"]["title"];
		$customer['company_worktime_start'] = $data["company"]["worktime_start"];
		$customer['company_worktime_end'] = $data["company"]["worktime_end"];
		$customer['modify_time'] = time();
		
		$result = $this->db->update("agent_metrics.customer", $customer, array("id"=>$data["id"]));
		
		if( $result ) {
			$this->insertEvaluation($data["id"], $data["evaluation"]);
			$this->insertTags($data["id"], $data["tags"]);
			
			echo TRUE;
		}
		else {
			self::exceptionResponse(500, "can not insert data into DB!");
		}
  }
 
  
	function restDelete($segments) {
		$result = $this->db->delete("agent_metrics.customer", array("customer_id"=>$customer_id));
		
		if( !$result )
			self::exceptionResponse(500, "can not insert data into DB!");
		else
			echo TRUE;
  }
  
  
  
  

}
?>