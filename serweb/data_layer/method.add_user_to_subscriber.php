<?
/*
 * $Id: method.add_user_to_subscriber.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_add_user_to_subscriber {
	var $required_methods = array();
	
	/*
	 *	add new user to table subscriber (or pending)
	 */

	function add_user_to_subscriber($uname, $domain, $passwd, $fname, $lname, $phone, $email, $timezone, $confirm, $table, &$errors){
	 	global $config;

		$ha1=md5($uname.":".$domain.":".$passwd);
		$ha1b=md5($uname."@".$config->domainname.":".$domain.":".$passwd);

		if (!$this->connect_to_db($errors)) return false;

		$attributes="";
		$values="";
		$uuid=md5(uniqid('fvkiore'));

		$attributes.=", email_address";
		$values.=", '".addslashes($email)."'";

		if ($config->users_indexed_by=='uuid') {
			$attributes.=", uuid";
			$values.=", '$uuid'";
		}
		
		$q="insert into ".$table." (username, password, first_name, last_name, phone".$attributes.", 
				datetime_created, datetime_modified, confirmation, ha1, ha1b, domain, phplib_id, timezone) 
			values ('".addslashes($uname)."', '".addslashes($passwd)."', '".addslashes($fname)."', '".addslashes($lname)."', 
			    '".addslashes($phone)."' ".$values.", now(), now(), '".$confirm."', 
				'".$ha1."', '".$ha1b."','".addslashes($domain)."', '".$uuid."', '".addslashes($timezone)."')";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;

	}
	
}
?>
