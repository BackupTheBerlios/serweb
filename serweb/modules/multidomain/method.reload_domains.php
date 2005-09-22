<?php
/*
 * $Id: method.reload_domains.php,v 1.1 2005/09/22 14:29:16 kozlik Exp $
 */

class CData_Layer_reload_domains {
	var $required_methods = array();
	
	/**
	 *  reload domains table of SER from DB
	 *
	 *  Possible options parameters:
	 *	 none
	 *
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function reload_domains($opt, &$errors){
		global $config;

		if ($config->use_rpc){
			if (!$this->connect_to_xml_rpc(null, $errors)) return false;

			$params = array();
			                
			$msg = new XML_RPC_Message_patched('domain_reload', $params);
			$res = $this->rpc->send($msg);
	
			if ($this->rpc_is_error($res)){
				log_errors($res, $errors); return false;
			}
		}
		else{	
			/* construct FIFO command */
			$fifo_cmd=":domain_reload:".$config->reply_fifo_filename."\n";
	
			$message=write2fifo($fifo_cmd, $errors, $status);
			if ($errors) return false;
			if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
		}

		return true;			
	}
}

?>
