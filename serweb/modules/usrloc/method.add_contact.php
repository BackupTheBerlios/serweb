<?
/*
 * $Id: method.add_contact.php,v 1.4 2006/04/10 15:32:58 kozlik Exp $
 */

class CData_Layer_add_contact {
	var $required_methods = array();
	
	/**
	 *	add contact to USRLOC
	 *
	 *	@param string $uid		uid of the owner of the contact
	 *	@param string $contact	contact which should be added
	 *	@param string $expires	when should the contact expire
	 *	@param array $errors	
	 *	@return bool			TRUE on success, FALSE on failure
	 */
	
	function add_contact($uid, $contact, $expires, &$errors){
		global $config;

		$replication="0";
		$flags="0";
		
		if ($expires > 567640000 or $expires <= 0){	//contact that should expire later than 18 year, never expire
			$expires=0;
			$flags="128";
		}

		if ($config->use_rpc){
			if (!$this->connect_to_xml_rpc(null, $errors)) return false;
			
			$params = array(new XML_RPC_Value($config->ul_table, 'string'),
			                new XML_RPC_Value($uid, 'string'),
			                new XML_RPC_Value($contact, 'string'),
			                new XML_RPC_Value($expires, 'int'),
			                new XML_RPC_Value($config->ul_priority, 'double'),
			                new XML_RPC_Value($flags, 'int'));
			                
			$msg = new XML_RPC_Message('usrloc.add_contact', $params);
			$res = $this->rpc->send($msg);
	
			if ($this->rpc_is_error($res)){
				log_errors($res, $errors); return false;
			}
		}
		else{
		
			/* construct FIFO command */
			$fifo_cmd=":usrloc.add_contact:".$config->reply_fifo_filename."\n".
				$config->ul_table."\n".			//table
				$uid."\n".
				$contact."\n".				//contact
				$expires."\n".					//expires
				$config->ul_priority."\n".	// priority
				$flags."\n".
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
