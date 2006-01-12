<?php
/*
 * $Id: method.reload_domains.php,v 1.2 2006/01/12 12:58:59 kozlik Exp $
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
//			if (!$this->connect_to_xml_rpc(null, $errors)) return false;

			$params = array();
			                
			$msg = new XML_RPC_Message_patched('domain.reload', $params);
			$res = $this->rpc_send_to_all($msg, array('break_on_error'=>false));

			if (!$res->ok){
				foreach($res->results as $v){
					if (PEAR::isError($v)) ErrorHandler::log_errors($v);
				}
				return false;
			}
			return true;

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
