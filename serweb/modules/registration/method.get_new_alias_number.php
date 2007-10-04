<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_new_alias_number.php,v 1.5 2007/10/04 19:59:22 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_registration
 */ 

/**
 *	Data layer container holding the method for generate new (numerical) alias
 * 
 *	@package    serweb
 *	@subpackage mod_registration
 */ 
class CData_Layer_get_new_alias_number {
	var $required_methods = array();
	
	 /**
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
					where ".$c->did."      = ".$this->sql_format($did,   "s")." and 
					      ".$c->username." = ".$this->sql_format($alias, "s");

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

            // get value for new alias
            $o = array('did' => $did);
            if (false === $alias = Attributes::get_Attribute($config->attr_names['highest_alias_number'], $o)) return false;

            $alias = (int)$alias;

            // if value is not set, use the config value
            if (!$alias or $alias < $config->first_alias_number) $alias = $config->first_alias_number;


            do{
                // check if the username is aready used
                $q="select count(*) 
                    from ".$t_name." 
                    where ".$c->did." = ".$this->sql_format($did, "s")." and 
                        ".$c->username." = ".$this->sql_format($alias, "n");
                
                $res=$this->db->query($q);
                if (DB::isError($res)) {ErrorHandler::log_errors($res);return false;}
                $row=$res->fetchRow(DB_FETCHMODE_ORDERED);
                $res->free();
            
                // if is used, increment it and try again
                if ($row[0]) $alias++;
            
            }while($row[0]);

            $da_h = &Domain_Attrs::singleton($did);
            if (false === $da_h->set_attribute($config->attr_names['highest_alias_number'], $alias+1)) return false;

			return $alias;
		}
	}
	
}
?>
