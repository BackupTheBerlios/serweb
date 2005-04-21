<?php
/*
 * $Id: method.send_im.php,v 1.1 2005/04/21 15:09:46 kozlik Exp $
 */

class CData_Layer_send_im {
	var $required_methods = array();
	
	function send_im($user, $recipient, $message, $opt, &$errors){
		global $config, $lang_set;

		/* construct FIFO command */
		$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		    "MESSAGE\n".
			$recipient."\n".
			".\n".
			"From: sip:".$user->uname."@".$user->domain."\n".
			"To: <".$recipient.">\n".
		    "p-version: ".$config->psignature."\n".
		    "Contact: <".$config->web_contact.">\n".
		    "Content-Type: text/plain; charset=".$lang_set['charset']."\n.\n".
		    str_Replace("\n.\n","\n. \n",$message)."\n.\n\n";


		if (false === write2fifo($fifo_cmd, $errors, $status)) {
			return false;
		}

		/* we accept any status code beginning with 2 as ok */
		if (substr($status,0,1)!="2") {
			$errors[]=$status; 
			return false; 
		}

		return true;
	}
	
}
?>
