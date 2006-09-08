<?
/*
 * $Id: method.play_greeting.php,v 1.2 2006/09/08 12:27:35 kozlik Exp $
 */

class CData_Layer_play_greeting {
	var $required_methods = array();
	
	function play_greeting($user, &$errors){
		global $config, $lang_str;
		
		if ($config->users_indexed_by=='uuid') {
			@$fp=fopen($config->greetings_spool_dir.$user->get_uid().".wav", 'rb');
		}
		else{
			@$fp=fopen($config->greetings_spool_dir.$user->get_domainname()."/".$user->get_username().".wav", 'rb');
		}

		if (!$fp){
			//try open default greeting
			@$fp=fopen($config->greetings_spool_dir."default.wav", 'rb');
			if (!$fp){
				log_errors(PEAR::raiseError($lang_str['err_can_not_open_greeting']), $errors); return false;
			}
		}
	
		Header("Content-Disposition: attachment;filename=".RawURLEncode("greeting.wav"));
		Header("Content-type: audio/wav");
	
		@fpassthru($fp);
		@fclose($fp);
		
		return true;
	}
	
}
?>
