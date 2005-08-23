<?
/*
 * $Id: method.remove_greeting.php,v 1.1 2005/08/23 12:54:58 kozlik Exp $
 */

class CData_Layer_remove_greeting {
	var $required_methods = array();
	
	function remove_greeting($user, &$errors){
		global $config, $lang_str;
		
		if ($config->users_indexed_by=='uuid') {
			$filename = $config->greetings_spool_dir.$user->uuid.".wav";
		}
		else{
			$filename = $config->greetings_spool_dir.$user->domain."/".$user->uname.".wav";
		}

		if (!file_exists($filename)) 
			/* nothing to remove */
			return true;
		
		if (unlink($filename)) return true;
		else {
			log_errors(PEAR::raiseError("remove greeting failed"), $errors);
			return false;
		}
		
	}
	
}
?>
