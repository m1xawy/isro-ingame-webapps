<?php
require_once('defines.php');
require_once('libs/adodb5/adodb.inc.php');
require_once('functions.php');

class db extends functions{
	function connect_mssql($dbName){
		$dbo=ADONewConnection('mssqlnative');
		if (!$dbo->Connect(RDBHOST0, RDBUSER0, RDBPASS0, $dbName)){
			$this->writelog("[Connection Error]\t".$dbo->errorMsg(), 'db_errors.log');
			$dbo->Close();
			die();
		}
		return $dbo;
	}
	function mssqlexec($query,$value=null,$fmode=null){
		$dbo=$this->connect_mssql(RDBNAME0);
		if ($fmode==null){
			$dbo->SetFetchMode(3);
		}else{
			$dbo->SetFetchMode($fmode);
		}
		if ($value==null){
			$dbq=$dbo->Execute($query);
		}else{
			$dbq=$dbo->Execute($query, $value);
		}	
		if (!$dbq){
			$this->writelog("[Query Error]\t".$dbo->errorMsg()."Query: ".$query." Values(".($value==null ? 'null' : $value).")", 'db_errors.log');
			$dbo->Close();
			die();
		}
		$dbo->Close();
		return $dbq;
	}
	function connect_mysql($dbName){
		$dbo=ADONewConnection('mysqli');
		if (!$dbo->Connect(RDBHOST1, RDBUSER1, RDBPASS1, $dbName)){
			$this->writelog("[Connection Error]\t".$dbo->errorMsg(), 'db_errors.log');
			$dbo->Close();
			die();
		}
		return $dbo;
	}
	function mysqlexec($query,$value=null,$fmode=null){
		$dbo=$this->connect_mysql(RDBNAME1);
		if ($fmode==null){
			$dbo->SetFetchMode(3);
		}else{
			$dbo->SetFetchMode($fmode);
		}
		if ($value==null){
			$dbq=$dbo->Execute($query);
		}else{
			$dbq=$dbo->Execute($query, $value);
		}	
		if (!$dbq){
			$this->writelog("[Query Error]\t".$dbo->errorMsg()."Query: ".$query." Values(".($value==null ? 'null' : $value).")", 'db_errors.log');
			$dbo->Close();
			die();
		}
		$dbo->Close();
		return $dbq;
	}
}
