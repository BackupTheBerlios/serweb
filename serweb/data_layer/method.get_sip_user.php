<?
/*
 * $Id: method.get_sip_user.php,v 1.2 2005/04/21 15:09:45 kozlik Exp $
 */

class CData_Layer_get_sip_user {
	var $required_methods = array();
	
	/*
	 * get uuid and email address of user
	 * return: associative array with fields 'uuid' and 'email'
	 */

	function get_sip_user($user, $domain, &$errors){
		global $auth, $config, $lang_str;

		if (!$this->connect_to_db($errors)) return false;
	
		//which attributes will be selected
		if ($config->users_indexed_by=='uuid') 	$attributes="uuid";
		else $attributes="phplib_id as uuid";
		
		$attributes.=", email_address";

		//formulate query
		$q="select ".$attributes." from ".$config->data_sql->table_subscriber.
			" where username='".$user."' and domain='".$domain."'";

		//query db
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		if (!$res->numRows()) {$errors[] = $lang_str['err_no_user']; return false;}

		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();

		//format output array
		$out=array('uuid'  => $row['uuid'],
		           'email' => $row['email_address']);

		return $out;
	}
	
}
?>
