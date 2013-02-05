<?php

class PDODB
{
	public $_dbConn = null;
	
	function PDODB() {
		//do nothing

	}
	
	
	function connect($host, $dbname, $user, $pwd) {
		try {
			$dsn = "mysql:host=$host;dbname=$dbname";
			$init = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
			
			$db = new PDO( $dsn, $user, $pwd, $init );
			
			//set ERROR reminder
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$this->_dbConn = $db;			
			return TRUE;
			
		}	catch(PDOException $e) {
			//error message
			error_log("FUNC: pdodb.connect(), ".$e->getMessage());
			return FALSE;
		}
	}

	
	function query($sql, $params=null) {
		$stmt = null;

		try {
			if( isset($params) ) {
				$stmt = $this->_dbConn->prepare($sql);
				$stmt->execute($params);
	
			}
			else {
				$stmt = $this->_dbConn->query($sql);
	
			}
				
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
				
			return $stmt->fetch();
				
		} catch(PDOException $e) {
			//error message
			error_log("FUNC: pdodb.query(), ".$e->getMessage());
			return FALSE;
		}
	}
	
	
	function queryAll($sql, $params=null, $fetch_attr=null) {
		$stmt = null;
	
		try {
			if( isset($params) ) {
				$stmt = $this->_dbConn->prepare($sql);
				$stmt->execute($params);
			}
			else {
				$stmt = $this->_dbConn->query($sql);
			}
	
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
	
			return $stmt->fetchAll($fetch_attr);
	
		} catch(PDOException $e) {
			//error message
			error_log("FUNC: pdodb.queryAll(), ".$e->getMessage());
			return FALSE;
		}
	}
	

	
	function insert($table, $data) {
		$arr_columns = array();
		$arr_values = array();
		
		foreach($data as $key => $value) {
			array_push($arr_columns, $key);
			array_push($arr_values, ":$key");
		}
		
		$sql = "INSERT INTO $table(" . implode(",", $arr_columns) . ") VALUES(" . implode(",", $arr_values) . ")";
		
		try {
			$stmt = $this->_dbConn->prepare($sql);
			
			//bind value
			foreach($data as $key => $value) {
				$stmt->bindValue(":$key", $value);
			}
			
			$res = $stmt->execute();
			
			return $this->_dbConn->lastInsertId();
			
		} catch(PDOException $e) {
			//error message
			error_log("FUNC: pdodb.insert(), ".$e->getMessage());
			return FALSE;
		}
	}
	
	
	
	function update($table, $data, $condition)
	{
		$arr_columns = array();
		$arr_conditions = array();
		
		$sql = "UPDATE $table ";
		
		foreach($data as $key => $value) {
			array_push($arr_columns, "$key=:$key");
		}
		
		$sql .= " SET ".implode(", ", $arr_columns);
		
		foreach($condition as $key => $value) {
			array_push($arr_conditions, "$key=:$key");
		}
		
		$sql .= " WHERE ".implode(" AND ", $arr_conditions);
		
		try {
			$this->_dbConn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$stmt = $this->_dbConn->prepare($sql);
			
			foreach($data as $key => $value) {
				$stmt->bindValue(":$key", $value);
			}
			
			foreach($condition as $key => $value) {
				$stmt->bindValue(":$key", $value);
			}
				
			$res = $stmt->execute();
			return TRUE;
			
		} catch(PDOException $e) {
			//error message
			error_log("FUNC: pdodb.update(), ".$e->getMessage());
			return FALSE;
		}
	}
	
	
	
	function delete($table, $condition=null) {
		$res = FALSE;
		$sql = "DELETE FROM $table ";
		
		try {
			if( isset($condition) ) {
			
				$arr_conditions = array();
				$arr_values = array();
			
				foreach($condition as $key => $value) {
					array_push($arr_conditions, "$key=:$key");
				}
			
				$sql .= " WHERE ".implode(" AND ", $arr_conditions);
			
				$stmt = $this->_dbConn->prepare($sql);
			
				foreach($condition as $key => $value) {
					$stmt->bindValue(":$key", $value);
				}
			
				$res = $stmt->execute();
			
			}
			else {
				$stmt = $this->_dbConn->prepare($sql);
				$res = $stmt->execute();
			}
			
			return $res;
			
		} catch(PDOException $e) {
			//error message
			error_log("FUNC: pdodb.delete(), ".$e->getMessage());
			return FALSE;
		}
		
	}
	
	
	
	
	
	function beginTransaction()
	{
		$this->_dbConn->beginTransaction();
	}
	
	function commit()
	{
		$this->_dbConn->commit();
	}
	
	function rollback()
	{
		$this->_dbConn->rollBack();
	}
	
	
}



?>
