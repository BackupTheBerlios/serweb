<?
/*
 * $Id: method.move_user_from_pending_to_subscriber.php,v 1.2 2004/08/26 09:23:33 kozlik Exp $
 */

/**
 * move user's entry from table pending to table subscriber
 *
 * @param $confirmation - confirmation string
 * @param $errors
 * @return Cserweb_auth of user
 *         TRUE if entry already has been moved
 *	       FALSE on error
 */ 

class CData_Layer_move_user_from_pending_to_subscriber {
	var $required_methods = array();
	
	function move_user_from_pending_to_subscriber($confirmation, &$errors){
		global $config, $lang_str;

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
			if (!$res1->numRows()){ $errors[] = $lang_str['err_reg_conf_not_exists_conf_num']; return false;}
			else { $errors[] = $lang_str['err_reg_conf_already_created']; return true; }
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
