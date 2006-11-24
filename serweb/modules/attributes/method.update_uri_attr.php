<?php
/*
 * $Id: method.update_uri_attr.php,v 1.2 2006/11/24 13:33:02 kozlik Exp $
 */

class CData_Layer_update_uri_attr {
	var $required_methods = array();
	
	/**
	 *  Update value of uri attribute
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *		old_value
	 *
	 *	@return bool
	 */ 
	 
	function update_uri_attr($scheme, $username, $did, $name, $value, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->uri_attrs->table_name;
		/* col names */
		$c = &$config->data_sql->uri_attrs->cols;
		/* flags */
		$f = &$config->data_sql->uri_attrs->flag_values;



		$ats = &Attr_types::singleton();
		if (false === $at = &$ats->get_attr_type($name)) return false;

		$type =	$flags = 0;
		if (is_object($at)){
			$type  = $at->get_raw_type();
			$flags = $at->get_default_flags();
		}


		if (is_object($at) and $at->is_multivalue()){
			if (!isset($opt['old_value'])) $opt['old_value'] = array();
		
			$insert = array_diff($value, $opt['old_value']);
			$delete = array_diff($opt['old_value'], $value);

			foreach($insert as $v){
				$q = "insert into ".$t_name."(
				             ".$c->scheme.", ".$c->username.", ".$c->did.", ".$c->name.", ".$c->value.", ".$c->type.", ".$c->flags.")
				      values (".$this->sql_format($scheme,   "s").", 
					          ".$this->sql_format($username, "s").", 
							  ".$this->sql_format($did,      "s").", 
					          ".$this->sql_format($name,     "s").", 
							  ".$this->sql_format($v,        "s").", 
							  ".$this->sql_format($type,     "n").", 
							  ".$this->sql_format($flags,    "n").")";
				$res=$this->db->query($q);
				if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
			}

			foreach($delete as $v){
				$q = "delete from ".$t_name." 
				      where ".$c->scheme."   = ".$this->sql_format($scheme,   "s")." and 
					        ".$c->name."     = ".$this->sql_format($name,     "s")." and 
							".$c->value."    = ".$this->sql_format($v,        "s")." and 
					        ".$c->username." = ".$this->sql_format($username, "s")." and 
							".$c->did."      = ".$this->sql_format($did,      "s");
				$res=$this->db->query($q);
				if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
			}
		
		}
		
		else{
			if (!isset($opt['old_value'])){
				
				$q = "insert into ".$t_name."(
				             ".$c->scheme.", ".$c->username.", ".$c->did.", ".$c->name.", ".$c->value.", ".$c->type.", ".$c->flags.")
				      values (".$this->sql_format($scheme,   "s").", 
					          ".$this->sql_format($username, "s").", 
							  ".$this->sql_format($did,      "s").", 
					          ".$this->sql_format($name,     "s").", 
							  ".$this->sql_format($value,    "s").", 
							  ".$this->sql_format($type,     "n").", 
							  ".$this->sql_format($flags,    "n").")";
			}
			elseif($opt['old_value'] == $value) {
				/* don't need update DB */
				return true;
			}
			else{
				if ($value === ""){
					$q = "delete from ".$t_name." 
					      where ".$c->scheme."   = ".$this->sql_format($scheme,   "s")." and 
					            ".$c->name."     = ".$this->sql_format($name,     "s")." and 
								".$c->username." = ".$this->sql_format($username, "s")." and 
							    ".$c->did."      = ".$this->sql_format($did,      "s");
				}
				else{
					$q = "update ".$t_name." 
					      set ".$c->value."      = ".$this->sql_format($value,    "s")."
					      where ".$c->scheme."   = ".$this->sql_format($scheme,   "s")." and 
					            ".$c->name."     = ".$this->sql_format($name,     "s")." and 
								".$c->username." = ".$this->sql_format($username, "s")." and 
						        ".$c->did."      = ".$this->sql_format($did,      "s");
				}
			}
			
			$res=$this->db->query($q);
			if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
		}

		return true;
	}
}
?>
