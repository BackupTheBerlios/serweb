<?
/*
 * $Id: method.add_new_alias.php,v 1.3 2004/12/10 14:08:17 kozlik Exp $
 */

class CData_Layer_add_new_alias {
	var $required_methods = array();
	
	 /*
	  *	add new alias to user
	  */

	function add_new_alias($user, $alias_u, $alias_d, &$errors){
	 	global $config;

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
			
			return '200 OK';
		}
		else {

			/* if replication is supported by FIFO */
		    if ($config->ul_replication) $replication="0\n";
		    else $replication="";

			$expires = $config->new_alias_expires;
			
			/* if flags is supported by FIFO */
			if ($config->ul_flags){
				if ($expires > 567640000 or $expires <= 0){	//contact that should expire later than 18 year, never expire
					$expires=0;
					$flags="128\n";
				}
				else{
					$flags="0\n";
				}
			}
			else $flags="";

	
			$sip_address='sip:'.$user->uname.'@'.$user->domain;
			
			$ul_name=$alias_u."@".$alias_d."\n";
	
			/* construct FIFO command */
			$fifo_cmd=":ul_add:".$config->reply_fifo_filename."\n".
				$config->fifo_aliases_table."\n".	//table
				$ul_name.							//user
				$sip_address."\n".					//contact
				$expires."\n".						//expires
				$config->new_alias_q."\n". 			//priority
				$replication.
				$flags.
				"\n";
	
			$message=write2fifo($fifo_cmd, $errors, $status);
			if ($errors) return false;
			if (substr($status,0,1)!="2") {$errors[]=$status; return false; }
	
			return $message;
		}
	}	
	
}
?>
