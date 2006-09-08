<?
/*
 * $Id: method.is_greeting_existent.php,v 1.2 2006/09/08 12:27:34 kozlik Exp $
 */

class CData_Layer_is_greeting_existent {
	var $required_methods = array();
	
	function is_greeting_existent($user, &$errors){
		global $config, $lang_str;
		
		if ($config->users_indexed_by=='uuid') {
			$filename = $config->greetings_spool_dir.$user->get_uid().".wav";
		}
		else{
			$filename = $config->greetings_spool_dir.$user->get_domainname()."/".$user->get_username().".wav";
		}

		//is this needed?
		//clearstatcache();
		
		return file_exists($filename);
	}
	
}
?>
