<?php


class Authcheck
{
	public $dev_list = array();
	
	function __construct()
	{
		//load the CAS lib
		require('./lib/CAS.php');
		require('./config/config.php');
		$this->dev_list = $dev_list;
		
		
		//enable debugging
		phpCAS::setDebug();
		
		//initialize phpCAS
		phpCAS::client(CAS_VERSION_2_0, 'sso.htc.com', 443, 'sso');
		
		//disable checking sso server
		phpCAS::setNoCasServerValidation();
	}
	
	
	function isAuthenticated()
	{
		return phpCAS::isAuthenticated();
	}
	
	function login()
	{
		// force CAS authentication
		phpCAS::forceAuthentication();
		
		$eid = phpCAS::getUser();
		
		if( !empty($eid) )
		{
			$_SESSION['eid'] = $eid;
		}
	}
	
	
	function getUser()
	{
		return phpCAS::getUser();
	}
	
	function logout()
	{
		// logout if desired
		phpCAS::logout();
		session_destroy();
	}
	
	
	function isDeveloper($eid)
	{	
		if( in_array($eid, $this->dev_list) ) 
		{
			$_SESSION['dev'] = true;
		}
	}
}



