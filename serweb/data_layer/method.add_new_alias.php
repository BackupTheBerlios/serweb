<?
/*
 * $Id: method.add_new_alias.php,v 1.4 2005/04/26 14:34:25 kozlik Exp $
 */

class CData_Layer_add_new_alias {
	var $required_methods = array('set_proxy_xxl');
	
	/**
	 *	add new alias to user
	 *
	 *	@param Cserweb_auth $user	owner of the contact 
	 *	@param string $alias_u		username part from alias
	 *	@param string $alias_d		domain part from alias
	 *	@param array $errors	
	 *	@return bool				TRUE on success, FALSE on failure
	 */

	function add_new_alias($user, $alias_u, $alias_d, &$errors){
	 	global $config;

		if ($config->enable_XXL){
			$alias_uri = "sip:".$alias_u."@".$alias_d;
			if (false === $proxy_uri = $this->get_home_proxy($errors)) return false;

			if (false === $this->set_proxy_xxl($alias_uri, $proxy_uri, null, $errors)) return false;
		}


		if ($config->users_indexed_by=='uuid'){

			if (!$this->connect_to_db($errors)) return false;

			$q="insert into ".$config->data_sql->table_uuidaliases."(username, domain, uuid) 
				  values ('".$alias_u."', '".$alias_d."', '".$user->uuid."')";
			
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			
			if ($config->use_table_uri) {
				$q="insert into ".$config->data_sql->table_uri." (user_domain, uuid) 
					  values ('".$alias_u."@".$alias_d."', '".$user->uuid."')";

				$res=$this->db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors);
					/* we should delete inserted alias in order to DB stay consistent*/

					$q="delete from ".$config->data_sql->table_uuidaliases."
						 where username='".$alias_u."' and domain='".$alias_d."' and uuid='".$user->uuid."'";
					$res=$this->db->query($q);
					if (DB::isError($res)) log_errors($res, $errors);

					return false;
				}
			}
		}
		else {

			$replication="0";
			$flags="0";

			$expires = $config->new_alias_expires;
			
			/* if flags is supported by FIFO */
			if ($config->ul_flags){
				if ($expires > 567640000 or $expires <= 0){	//contact that should expire later than 18 year, never expire
					$expires=0;
					$flags="128";
				}
			}
			else $flags="";

	
			$sip_address='sip:'.$user->uname.'@'.$user->domain;
			
			$ul_name=$alias_u."@".$alias_d;

			if ($config->use_rpc){
				if (!$this->connect_to_xml_rpc(null, $errors)) return false;
				
				$params = array(new XML_RPC_Value($config->fifo_aliases_table, 'string'),
				                new XML_RPC_Value($ul_name, 'string'),
				                new XML_RPC_Value($sip_address, 'string'),
				                new XML_RPC_Value($expires, 'string'),
				                new XML_RPC_Value($config->new_alias_q, 'string'),
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
					$config->fifo_aliases_table."\n".	//table
					$ul_name."\n".							//user
					$sip_address."\n".					//contact
					$expires."\n".						//expires
					$config->new_alias_q."\n". 			//priority
					
		    		($config->ul_replication ? 			// if replication is supported by FIFO 
						$replication."\n":
						"").

					($config->ul_flags ?				// if flags is supported by FIFO
						$flags."\n":
						"").

					"\n";
		
				$message=write2fifo($fifo_cmd, $errors, $status);
				if ($errors) return false;
				if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
			}
		}
		return true;
	}	
	
}
?>
