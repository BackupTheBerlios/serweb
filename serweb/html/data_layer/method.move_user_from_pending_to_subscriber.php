<?
/*
 * $Id: method.move_user_from_pending_to_subscriber.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_move_user_from_pending_to_subscriber {
	var $required_methods = array();
	
	function move_user_from_pending_to_subscriber($confirmation, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid') $a=", uuid";
		else $a="";
		
		$q="select username, domain".$a." from ".$config->data_sql->table_pending." where confirmation='".$confirmation."'";
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

		if ($config->users_indexed_by!='uuid') $row->uuid=null;;
		$user=new Cserweb_auth($row->uuid, $row->username, $row->domain);
		$res->free();

		$q="insert into ".$config->data_sql->table_subscriber." select * from ".$config->data_sql->table_pending." where confirmation='".$confirmation."'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return $user;
	}
	
}
?>
