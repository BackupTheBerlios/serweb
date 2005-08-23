<?
/*
 * $Id: method.del_contact.php,v 1.1 2005/08/23 12:58:13 kozlik Exp $
 */

class CData_Layer_del_contact {
	var $required_methods = array();
	
	/**
	 *	delete contact from USRLOC
	 *
	 *	@param string $user		username of the owner of the contact
	 *	@param string $domain	domain of the owner of the contact.
	 *	@param string $contact	contact which should be removed
	 *	@param array $errors	
	 *	@return bool			TRUE on success, FALSE on failure
	 */
	
	function del_contact($user, $domain, $contact, &$errors){
		global $config;

		$ul_name=$user."@".$domain;

		if ($config->use_rpc){
			if (!$this->connect_to_xml_rpc(null, $errors)) return false;

			$params = array(new XML_RPC_Value($config->ul_table, 'string'),
			                new XML_RPC_Value($ul_name, 'string'),
							new XML_RPC_Value($contact, 'string'));
			                
			$msg = new XML_RPC_Message_patched('ul_rm_contact', $params);
			$res = $this->rpc->send($msg);
	
			if ($this->rpc_is_error($res)){
				log_errors($res, $errors); return false;
			}
	
			return true;			
		}
		else{	

			/* construct FIFO command */
			$fifo_cmd=":ul_rm_contact:".$config->reply_fifo_filename."\n".
				$config->ul_table."\n".		//table
				$ul_name."\n".
				$contact."\n\n";			//contact
	
			$message=write2fifo($fifo_cmd, $errors, $status);
			if ($errors) return false;
			/* we accept any 2xx as ok */
			if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
		}
		
		return true;
	}
	
}
?>
