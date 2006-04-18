<?php
/*
 * $Id: method.update_user_attr.php,v 1.3 2006/04/18 10:36:09 kozlik Exp $
 */

class CData_Layer_update_user_attr {
	var $required_methods = array();
	
	/**
	 *  Update value of user attribute
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *		old_value
	 *
	 *	@return bool
	 */ 
	 
	function update_user_attr($uid, $name, $value, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->user_attrs->table_name;
		/* col names */
		$c = &$config->data_sql->user_attrs->cols;
		/* flags */
		$f = &$config->data_sql->user_attrs->flag_values;



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
				             ".$c->uid.", ".$c->name.", ".$c->value.", ".$c->type.", ".$c->flags.")
				      values (".$this->sql_format($uid,   "s").", 
					          ".$this->sql_format($name,  "s").", 
							  ".$this->sql_format($v,     "s").", 
							  ".$this->sql_format($type,  "n").", 
							  ".$this->sql_format($flags, "n").")";
				$res=$this->db->query($q);
				if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
			}

			foreach($delete as $v){
				$q = "delete from ".$t_name." 
				      where ".$c->name."  = ".$this->sql_format($name, "s")." and 
					        ".$c->value." = ".$this->sql_format($v,    "s")." and 
							".$c->uid."   = ".$this->sql_format($uid,  "s");
				$res=$this->db->query($q);
				if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
			}
		
		}
		
		else{
			if (!isset($opt['old_value'])){
				
				$q = "insert into ".$t_name."(
				             ".$c->uid.", ".$c->name.", ".$c->value.", ".$c->type.", ".$c->flags.")
				      values (".$this->sql_format($uid,   "s").", 
					          ".$this->sql_format($name,  "s").", 
							  ".$this->sql_format($value, "s").", 
							  ".$this->sql_format($type,  "n").", 
							  ".$this->sql_format($flags, "n").")";
			}
			elseif($opt['old_value'] == $value) {
				/* don't need update DB */
				return true;
			}
			else{
				if ($value === ""){
					$q = "delete from ".$t_name." 
					      where ".$c->name." = ".$this->sql_format($name, "s")." and 
						        ".$c->uid."  = ".$this->sql_format($uid,  "s");
				}
				else{
					$q = "update ".$t_name." 
					      set ".$c->value."  = ".$this->sql_format($value, "s")."
					      where ".$c->name." = ".$this->sql_format($name,  "s")." and 
						        ".$c->uid."  = ".$this->sql_format($uid,   "s");
				}
			}
			
			$res=$this->db->query($q);
			if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
		}

		return true;
	}
}
?>
