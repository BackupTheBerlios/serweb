<?php
/*
 * $Id: method.reload_domains.php,v 1.3 2006/03/29 14:49:33 kozlik Exp $
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

		/* If SER does not caches domain table, the reload is not needed
		 * (and also is not possible) */
		if (empty($config->ser_domain_cache)) return true;

		if ($config->use_rpc){
//			if (!$this->connect_to_xml_rpc(null, $errors)) return false;

			$params = array();
			                
			$msg = new XML_RPC_Message('domain.reload', $params);
			$res = $this->rpc_send_to_all($msg, array('break_on_error'=>false));

			if (!$res->ok){
				$cache_varning = false;
				foreach($res->results as $v){
					if (PEAR::isError($v)) {
						ErrorHandler::log_errors($v);
						if ($v->getCode() == 400) $cache_varning = true;
					}
				}

				if ($cache_varning){
					sw_log("Domain reload failed. May be the domain cache in SER is disabled. ".
					       "Try either enable the cache by set modparam(\"domain\", \"db_mode\", 1) ".
						   "in your ser.cfg or disable reloading domains in serweb by setting ".
						   "\$config->ser_domain_cache = false in config_data_layer.php", PEAR_LOG_ERR);
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
