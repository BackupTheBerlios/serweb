<?
/*
 * $Id: method.store_greeting.php,v 1.2 2006/09/08 12:27:35 kozlik Exp $
 */

class CData_Layer_store_greeting {
	var $required_methods = array();
	
	function store_greeting($user, $greeting_file, &$errors){
		global $config;
		
		if ($config->users_indexed_by=='uuid') {
			if (!copy ($greeting_file, $config->greetings_spool_dir.$user->get_uid().".wav")){
				log_errors(PEAR::raiseError("store greeting failed"), $errors); return false;
			}
		}
		else{
			if (!copy ($greeting_file, $config->greetings_spool_dir.$user->get_domainname()."/".$user->get_username().".wav")){
				log_errors(PEAR::raiseError("store greeting failed"), $errors); return false;
			}
		}
		return true;	
	}
	
}
?>
