<?
/*
 * $Id: method.play_greeting.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_play_greeting {
	var $required_methods = array();
	
	function play_greeting($user, &$errors){
		global $config;
		
		if ($config->users_indexed_by=='uuid') {
			@$fp=fopen($config->greetings_spool_dir.$user->uuid.".wav", 'rb');
		}
		else{
			@$fp=fopen($config->greetings_spool_dir.$user->domain."/".$user->uname.".wav", 'rb');
		}

		if (!$fp){
			//try open default greeting
			@$fp=fopen($config->greetings_spool_dir."default.wav", 'rb');
			if (!$fp){
				log_errors(PEAR::raiseError("Can't open greeting"), $errors); return false;
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
