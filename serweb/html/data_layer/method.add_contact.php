<?
/*
 * $Id: method.add_contact.php,v 1.1 2004/08/09 11:40:58 kozlik Exp $
 */

class CData_Layer_add_contact {
	var $required_methods = array();
	
	/*
	 * add contact to USRLOC
	 */
	
	function add_contact($user, $domain, $contact, $expires, &$errors){
		global $config;
		
		if ($config->ul_replication) $replication="0\n";
		else $replication="";

		/* construct FIFO command */
		$ul_name=$user."@".$domain."\n";
		$fifo_cmd=":ul_add:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".			//table
			$ul_name.
			$contact."\n".				//contact
			$expires."\n".					//expires
			$config->ul_priority."\n".	// priority
			$replication."\n";

		$message=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) return false;
		/* we accept any 2xx as ok */
		if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
		
		return $status;

	}	
}
?>
