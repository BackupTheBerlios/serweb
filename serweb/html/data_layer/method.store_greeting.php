<?
/*
 * $Id: method.store_greeting.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_store_greeting {
	var $required_methods = array();
	
	function store_greeting($user, $greeting_file, &$errors){
		global $config;
		
		if ($config->users_indexed_by=='uuid') {
			if (!copy ($greeting_file, $config->greetings_spool_dir.$user->uuid.".wav")){
				log_errors(PEAR::raiseError("store greeting failed"), $errors); return false;
			}
		}
		else{
			if (!copy ($greeting_file, $config->greetings_spool_dir.$user->domain."/".$user->uname.".wav")){
				log_errors(PEAR::raiseError("store greeting failed"), $errors); return false;
			}
		}
		return true;	
	}
	
}
?>
