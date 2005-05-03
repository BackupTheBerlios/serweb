<?
/*
 * $Id: method.delete_alias.php,v 1.5 2005/05/03 09:10:05 kozlik Exp $
 */

class CData_Layer_delete_alias {
	
	function _get_required_methods(){
		global $config;
		
		return $config->enable_XXL?
					array('clear_proxy_xxl'):
					array();
	}
	
	/**
	 *	delete alias of user
	 *
	 *	@param Cserweb_auth $user	owner of the contact 
	 *	@param string $alias_u		username part from alias
	 *	@param string $alias_d		domain part from alias
	 *	@param array $errors	
	 *	@return bool				TRUE on success, FALSE on failure
	 */

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

			if ($config->use_table_uri) {
				$q="delete from ".$config->data_sql->table_uri."
					 where user_domain='".$alias_u."@".$alias_d."' and uuid='".$user->uuid."'";

				$res=$this->db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors); return false;}
			}
		}
		else{
		    if ($config->ul_replication) $replication="0\n";
		    else $replication="";
	
			$sip_address='sip:'.$user->uname.'@'.$user->domain;
			
			$ul_name=$alias_u."@".$alias_d;

			if ($config->use_rpc){
				if (!$this->connect_to_xml_rpc(null, $errors)) return false;

				$params = array(new XML_RPC_Value($config->fifo_aliases_table, 'string'),
				                new XML_RPC_Value($ul_name, 'string'));
				                
				$msg = new XML_RPC_Message_patched('ul_rm', $params);
				$res = $this->rpc->send($msg);
		
				if ($this->rpc_is_error($res)){
					log_errors($res, $errors); return false;
				}
		
			}
			else{	
				/* construct FIFO command */
				$fifo_cmd=":ul_rm:".$config->reply_fifo_filename."\n".
					$config->fifo_aliases_table."\n".	//table
					$ul_name."\n";						//user
		
				$message=write2fifo($fifo_cmd, $errors, $status);
				if ($errors) return false;
				if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
		
			}
		}


		if ($config->enable_XXL){
			$alias_uri = "sip:".$alias_u."@".$alias_d;

			if (false === $this->clear_proxy_xxl($alias_uri, null, $errors)) return false;
		}

		return true;		
	}
	
}
?>
