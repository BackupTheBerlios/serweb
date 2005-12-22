<?
/*
 * $Id: method.get_new_alias_number.php,v 1.1 2005/12/22 13:49:49 kozlik Exp $
 */

class CData_Layer_get_new_alias_number {
	var $required_methods = array();
	
	 /*
	  *	generate new alias number
	  */

	 function get_new_alias_number($did, $opt){
	 	global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return 0;
		}

		/* table name */
		$t_name = &$config->data_sql->uri->table_name;
		/* col names */
		$c = &$config->data_sql->uri->cols;
		/* flags */
		$f = &$config->data_sql->uri->flag_values;


		if ($config->alias_generation=='rand' or
		    isModuleLoaded('xxl')){ //random alias generation
		    
			$retries=0;
			do{
				//create alias	
				$alias=$config->alias_prefix;
				for($i=0; $i<$config->alias_lenght; $i++) $alias.=mt_rand(0, 9);
				$alias.=$config->alias_postfix;
				
				//check if alias isn't used
				$q="select count(username) 
				    from ".$t_name." 
					where ".$c->did." = '".$did."' and 
					      ".$c->username." = '".$alias."'";

				$res=$this->db->query($q);
				if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
				$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
				$res->free();
				
				if ($row[0] == 0) break;

				$retries++;
			}while ($retries < $config->alias_generation_retries);
			
			if ($retries < $config->alias_generation_retries) return $alias;
			else {
				ErrorHandler::log_errors(PEAR::raiseError("can't find any unused alias number"));
				return false;
			}
		
		}
		else{ //incremental alias generation

			$q="select max(".$this->get_sql_cast_to_int_funct($c->username).") 
			    from ".$t_name." 
				where ".$c->did." = '".$did."' and 
				      ".$c->username." REGEXP \"^[0-9]+$\"";


			$res=$this->db->query($q);
			if (DB::isError($res)) {ErrorHandler::log_errors($res);return false;}
			$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
			$res->free();
			$alias=is_null($row[0])?$config->first_alias_number:($row[0]+1);
			$alias=($alias<$config->first_alias_number)?$config->first_alias_number:$alias;
			return $alias;
		}
	}
	
}
?>
