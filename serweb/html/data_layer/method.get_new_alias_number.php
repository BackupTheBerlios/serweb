<?
/*
 * $Id: method.get_new_alias_number.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_new_alias_number {
	var $required_methods = array();
	
	 /*
	  *	generate new alias number
	  */

	 function get_new_alias_number($domain, &$errors){
	 	global $config;

		if (!$this->connect_to_db($errors)) return false;

		if ($config->alias_generation=='rand'){ //random alias generation
			$retries=0;
			do{
				//create alias	
				$alias=$config->alias_prefix;
				for($i=0; $i<$config->alias_lenght; $i++) $alias.=mt_rand(0, 9);
				$alias.=$config->alias_postfix;
				
				//check if alias isn't used
				$q="select username from ".($config->users_indexed_by=='uuid'?
											$config->data_sql->table_uuidaliases:
											$config->data_sql->table_aliases).
					" where domain='".$domain."' and username='".$alias."'";
				$res=$this->db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors); return false;}
				
				if ($res->numRows() == 0) break;

				$retries++;
			}while ($retries < $config->alias_generation_retries);
			
			if ($retries < $config->alias_generation_retries) return $alias;
			else {
				$errors[]="sorry, can't find any unused alias number";
				return false;
			}
		
		}
		else{ //incremental alias generation
			if ($config->users_indexed_by=='uuid'){
				// abs() converts string to number
				$q="select max(abs(username)) from ".$config->data_sql->table_uuidaliases." where domain='".$domain."' and username REGEXP \"^[0-9]+$\"";
			}
			else{
				$q="select max(abs(username)) from ".$config->data_sql->table_aliases." where domain='".$domain."' and username REGEXP \"^[0-9]+$\"";
			}

			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
			$res->free();
			$alias=is_null($row[0])?$config->first_alias_number:($row[0]+1);
			$alias=($alias<$config->first_alias_number)?$config->first_alias_number:$alias;
			return $alias;
		}
	}
	
}
?>
