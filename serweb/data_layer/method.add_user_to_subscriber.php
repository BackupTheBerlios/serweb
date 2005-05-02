<?
/*
 * $Id: method.add_user_to_subscriber.php,v 1.3 2005/05/02 11:18:41 kozlik Exp $
 */

class CData_Layer_add_user_to_subscriber {
	var $required_methods = array();
	
	/*
	 *	add new user to table subscriber (or pending)
	 */

	function add_user_to_subscriber($user_info, $opt, &$errors){
	 	global $config;

		if (!isset($user_info['uname'])) {
			log_errors(PEAR::raiseError("internal error", null, null, null,
				"add_user_to_subscriber: missing uname"), $errors); 
			return false;
		}
		if (!isset($user_info['domain']))	$user_info['domain'] = $config->domain;
		if (!isset($user_info['password']))	$user_info['password'] = '';
		if (!isset($user_info['fname']))	$user_info['fname'] = '';
		if (!isset($user_info['lname']))	$user_info['lname'] = '';
		if (!isset($user_info['phone']))	$user_info['phone'] = '';
		if (!isset($user_info['email']))	$user_info['email'] = '';
		if (!isset($user_info['timezone']))	$user_info['timezone'] = $config->default_timezone;
		if (!isset($user_info['confirm']))	$user_info['confirm'] = '';
		if (!isset($user_info['uuid'])) 	$user_info['uuid'] = md5(uniqid($_SERVER["SERVER_ADDR"]));

	    $table = (isset($opt['pending']) and $opt['pending']) ? 
						$config->data_sql->table_pending : 
						$config->data_sql->table_subscriber;


		$ha1=md5($user_info['uname'].":".$user_info['domain'].":".$user_info['password']);
		$ha1b=md5($user_info['uname']."@".$config->domain.":".$user_info['domain'].":".$user_info['password']);

		if (!$this->connect_to_db($errors)) return false;

		$attributes="";
		$values="";

		$attributes.=", email_address";
		$values.=", '".addslashes($user_info['email'])."'";

		if ($config->users_indexed_by=='uuid') {
			$attributes.=", uuid";
			$values.=", '".$user_info['uuid']."'";
		}
		else{
			$attributes.=", phplib_id";
			$values.=", '".$user_info['uuid']."'";
		}
		
		$q="insert into ".$table." (username, password, first_name, last_name, phone".$attributes.", 
				datetime_created, datetime_modified, confirmation, ha1, ha1b, domain, timezone) 
			values ('".addslashes($user_info['uname'])."', '".addslashes($user_info['password'])."', 
				'".addslashes($user_info['fname'])."', '".addslashes($user_info['lname'])."', 
			    '".addslashes($user_info['phone'])."' ".$values.", now(), now(), '".$user_info['confirm']."', 
				'".$ha1."', '".$ha1b."','".addslashes($user_info['domain'])."', '".addslashes($user_info['timezone'])."')";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;

	}
	
}
?>
