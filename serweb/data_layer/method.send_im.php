<?php
/*
 * $Id: method.send_im.php,v 1.2 2005/04/26 14:34:26 kozlik Exp $
 */

class CData_Layer_send_im {
	var $required_methods = array();
	
	function send_im($user, $recipient, $message, $opt, &$errors){
		global $config, $lang_set;

		$sip_msg = 
		    "MESSAGE\n".
			$recipient."\n".
			".\n".
			"From: sip:".$user->uname."@".$user->domain."\n".
			"To: <".$recipient.">\n".
		    "p-version: ".$config->psignature."\n".
		    "Contact: <".$config->web_contact.">\n".
		    "Content-Type: text/plain; charset=".$lang_set['charset']."\n.\n".
		    str_Replace("\n.\n","\n. \n",$message)."\n.\n\n";



		if ($config->use_rpc){
			if (!$this->connect_to_xml_rpc(null, $errors)) return false;
			
			$params = array(new XML_RPC_Value($sip_msg, 'string'));
			$msg = new XML_RPC_Message_patched('t_uac_dlg', $params);
			$res = $this->rpc->send($msg);
	
			if ($this->rpc_is_error($res)){
				log_errors($res, $errors); return false;
			}
		}
		else{
			/* construct FIFO command */
			$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
						$sip_msg;
	
			if (false === write2fifo($fifo_cmd, $errors, $status)) {
				return false;
			}
	
			/* we accept any status code beginning with 2 as ok */
			if (substr($status,0,1)!="2") {
				$errors[]=$status; 
				return false; 
			}
		}

		return true;
	}
	
}
?>
