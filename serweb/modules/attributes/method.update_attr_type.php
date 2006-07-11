<?php
/*
 * $Id: method.update_attr_type.php,v 1.2 2006/07/11 12:14:59 kozlik Exp $
 */

class CData_Layer_update_attr_type {
	var $required_methods = array();
	
	/**
	 *  Update attribute type
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *		none
	 *	
	 *	@param	Attr_type	$at
	 *	@param	string		$old_name	Old name of attribute. If is set, attr. type is updated otherwise is created new
	 *	@param	array		$opt		Array of options
	 *	@return bool
	 */ 
	 
	function update_attr_type($at, $old_name, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->attr_types->table_name;
		/* col names */
		$c = &$config->data_sql->attr_types->cols;

		if (is_null($old_name)){
			
			$q = "insert into ".$t_name."(
			             ".$c->name.", 
						 ".$c->rich_type.", 
						 ".$c->raw_type.", 
						 ".$c->type_spec.", 
						 ".$c->desc.", 
						 ".$c->default_flags.", 
						 ".$c->flags.", 
						 ".$c->priority.", 
						 ".$c->access.", 
						 ".$c->order.")
			      values (".$this->sql_format($at->get_name(),                 "s").", 
				          ".$this->sql_format($at->get_type(),                 "s").", 
						  ".$this->sql_format($at->get_raw_type(),             "n").", 
						  ".$this->sql_format(serialize($at->get_type_spec()), "s").", 
						  ".$this->sql_format($at->get_raw_description(),      "s").", 
						  ".$this->sql_format($at->get_default_flags(),        "n").", 
						  ".$this->sql_format($at->get_flags(),                "n").", 
						  ".$this->sql_format($at->get_priority(),             "n").", 
						  ".$this->sql_format($at->get_access(),               "n").", 
						  ".$this->sql_format($at->get_order(),                "n").")";
		}
		else{
			$q = "update ".$t_name." 
			      set ".$c->name."          = ".$this->sql_format($at->get_name(),                 "s").",
			          ".$c->rich_type."     = ".$this->sql_format($at->get_type(),                 "s").",
			          ".$c->raw_type."      = ".$this->sql_format($at->get_raw_type(),             "n").",
			          ".$c->type_spec."     = ".$this->sql_format(serialize($at->get_type_spec()), "s").",
			          ".$c->desc."          = ".$this->sql_format($at->get_raw_description(),      "s").",
			          ".$c->default_flags." = ".$this->sql_format($at->get_default_flags(),        "n").",
			          ".$c->flags."         = ".$this->sql_format($at->get_flags(),                "n").",
			          ".$c->priority."      = ".$this->sql_format($at->get_priority(),             "n").",
			          ".$c->access."        = ".$this->sql_format($at->get_access(),               "n").",
			          ".$c->order."         = ".$this->sql_format($at->get_order(),                "n")."
			      where ".$c->name." = ".$this->sql_format($old_name,  "s");
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
