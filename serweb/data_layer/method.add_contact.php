<?
/*
 * $Id: method.add_contact.php,v 1.3 2005/04/26 14:34:25 kozlik Exp $
 */

class CData_Layer_add_contact {
	var $required_methods = array();
	
	/**
	 *	add contact to USRLOC
	 *
	 *	@param string $user		username of the owner of the contact
	 *	@param string $domain	domain of the owner of the contact.
	 *	@param string $contact	contact which should be added
	 *	@param string $expires	when should the contact expire
	 *	@param array $errors	
	 *	@return bool			TRUE on success, FALSE on failure
	 */
	
	function add_contact($user, $domain, $contact, $expires, &$errors){
		global $config;

		$replication="0";
		$flags="0";
		
		/* if flags is supported by FIFO */
		if ($config->ul_flags){
			if ($expires > 567640000 or $expires <= 0){	//contact that should expire later than 18 year, never expire
				$expires=0;
				$flags="128";
			}
		}
		else $flags="";

		$ul_name=$user."@".$domain;

		if ($config->use_rpc){
			if (!$this->connect_to_xml_rpc(null, $errors)) return false;
			
			$params = array(new XML_RPC_Value($config->ul_table, 'string'),
			                new XML_RPC_Value($ul_name, 'string'),
			                new XML_RPC_Value($contact, 'string'),
			                new XML_RPC_Value($expires, 'string'),
			                new XML_RPC_Value($config->ul_priority, 'string'),
			                new XML_RPC_Value($replication, 'string'),
			                new XML_RPC_Value($flags, 'string'));
			                
			$msg = new XML_RPC_Message_patched('ul_add', $params);
			$res = $this->rpc->send($msg);
	
			if ($this->rpc_is_error($res)){
				log_errors($res, $errors); return false;
			}
		}
		else{
		
			/* construct FIFO command */
			$fifo_cmd=":ul_add:".$config->reply_fifo_filename."\n".
				$config->ul_table."\n".			//table
				$ul_name."\n".
				$contact."\n".				//contact
				$expires."\n".					//expires
				$config->ul_priority."\n".	// priority

	    		($config->ul_replication ? 			// if replication is supported by FIFO 
					$replication."\n":
					"").

				($config->ul_flags ?				// if flags is supported by FIFO
					$flags."\n":
					"").

				"\n";
	
			$message=write2fifo($fifo_cmd, $errors, $status);
			if ($errors) return false;
			/* we accept any 2xx as ok */
			if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
		}

		return true;
	}	
}
?>
