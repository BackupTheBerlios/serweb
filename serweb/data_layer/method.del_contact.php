<?
/*
 * $Id: method.del_contact.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_del_contact {
	var $required_methods = array();
	
	/*
	 * delete contact from USRLOC
	 */
	
	function del_contact($user, $domain, $contact, &$errors){
		global $config;

		/* construct FIFO command */
		$ul_name=$user."@".$domain."\n";
		$fifo_cmd=":ul_rm_contact:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".		//table
			$ul_name.
			$contact."\n\n";			//contact

		$message=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) return false;
		/* we accept any 2xx as ok */
		if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
		
		return $status;
	}
	
}
?>
