<?
/*
 * $Id: method.del_contact.php,v 1.3 2006/03/29 14:53:12 kozlik Exp $
 */

class CData_Layer_del_contact {
	var $required_methods = array();
	
	/**
	 *	delete contact from USRLOC
	 *
	 *	@param string $uid		UID of the owner of the contact
	 *	@param string $contact	contact which should be removed
	 *	@param array $errors	
	 *	@return bool			TRUE on success, FALSE on failure
	 */
	
	function del_contact($uid, $contact, &$errors){
		global $config;

		if ($config->use_rpc){
			if (!$this->connect_to_xml_rpc(null, $errors)) return false;

			$params = array(new XML_RPC_Value($config->ul_table, 'string'),
			                new XML_RPC_Value($uid, 'string'),
							new XML_RPC_Value($contact, 'string'));
			                
			$msg = new XML_RPC_Message('usrloc.delete_contact', $params);
			$res = $this->rpc->send($msg);
	
			if ($this->rpc_is_error($res)){
				log_errors($res, $errors); return false;
			}
	
			return true;			
		}
		else{	

			/* construct FIFO command */
			$fifo_cmd=":usrloc.delete_contact:".$config->reply_fifo_filename."\n".
				$config->ul_table."\n".		//table
				$uid."\n".
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
