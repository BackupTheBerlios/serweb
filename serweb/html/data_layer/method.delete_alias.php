<?
/*
 * $Id: method.delete_alias.php,v 1.1 2004/08/09 11:40:58 kozlik Exp $
 */

class CData_Layer_delete_alias {
	var $required_methods = array();
	
	function delete_alias($user, $alias_u, $alias_d, &$errors){
	 	global $config;

		if ($config->users_indexed_by=='uuid'){
			if (!$this->connect_to_db($errors)) return false;

			$q="delete from ".$config->data_sql->table_uuidaliases."
				 where username='".$alias_u."' and domain='".$alias_d."' and uuid='".$user->uuid."'";
			
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			if (!$this->db->affectedRows()){
				log_errors(PEAR::RaiseError("Can't delete alias ".$alias_u."@".$alias_d." of user ".$user->uuid), $errors); 
				return false;
			} 
			return '200 OK';
		}
		
		else{
		    if ($config->ul_replication) $replication="0\n";
		    else $replication="";
	
			$sip_address='sip:'.$user->uname.'@'.$user->domain;
			
			$ul_name=$alias_u."@".$alias_d."\n";
	
			/* construct FIFO command */
			$fifo_cmd=":ul_rm:".$config->reply_fifo_filename."\n".
				$config->fifo_aliases_table."\n".	//table
				$ul_name;							//user
	
			$message=write2fifo($fifo_cmd, $errors, $status);
			if ($errors) return false;
			if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
	
			return $message;
		}
		
	}
	
}
?>
