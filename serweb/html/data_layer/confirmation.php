<?
/*
 * $Id: confirmation.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	function move_user_from_pending_to_subscriber($confirmation, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':

			$q="select username from ".$config->data_sql->table_pending." where confirmation='".$confirmation."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
			if (!$res->numRows()){
				$q="select username from ".$config->data_sql->table_subscriber." where confirmation='".$confirmation."'";
				$res1=$this->db->query($q);
				if (DB::isError($res1)) {log_errors($res1, $errors); return false;}
				if (!$res1->numRows()){ $errors[]="Sorry. No such a confirmation number exists."; return false;}
				else { $ok=1; $errors[]="Your account has already been created."; return false; }
			}
	
			$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
			$username=$row->username; 
			$res->free();
	
			$q="insert into ".$config->data_sql->table_subscriber." select * from ".$config->data_sql->table_pending." where confirmation='".$confirmation."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			return $username;

		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}

	function del_user_from_pending($confirmation, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':

			$q="delete from ".$config->data_sql->table_pending." where confirmation='".$confirmation."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}

	function del_user_from_subscriber($confirmation, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':

			$q="delete from ".$config->data_sql->table_subscriber." where confirmation='".$confirmation."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}
	
}

?>