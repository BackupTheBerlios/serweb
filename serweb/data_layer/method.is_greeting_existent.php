<?
/*
 * $Id: method.is_greeting_existent.php,v 1.1 2004/09/02 11:27:03 kozlik Exp $
 */

class CData_Layer_is_greeting_existent {
	var $required_methods = array();
	
	function is_greeting_existent($user, &$errors){
		global $config, $lang_str;
		
		if ($config->users_indexed_by=='uuid') {
			$filename = $config->greetings_spool_dir.$user->uuid.".wav";
		}
		else{
			$filename = $config->greetings_spool_dir.$user->domain."/".$user->uname.".wav";
		}

		//is this needed?
		//clearstatcache();
		
		return file_exists($filename);
	}
	
}
?>
