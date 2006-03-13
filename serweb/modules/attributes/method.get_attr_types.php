<?php
/*
 * $Id: method.get_attr_types.php,v 1.2 2006/03/13 15:34:06 kozlik Exp $
 */

class CData_Layer_get_attr_types{
	var $required_methods = array();
	
	/* 
	 * return list of all attributes without atribute named as $att_edit
	 * $att_edit may be null
	 */
	 
	function get_attr_types($opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$t_at = &$config->data_sql->attr_types->table_name;
		/* col names */
		$c = &$config->data_sql->attr_types->cols;


		$q="select ".$c->name.", ".$c->raw_type.", ".$c->rich_type.", ".$c->type_spec.", 
		           ".$c->desc.", ".$c->default_flags.", ".$c->flags.", ".$c->priority.", 
		           ".$c->order."
		    from ".$t_at." 
			order by ".$c->order;
			
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
	
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out[$row[$c->name]] = &Attr_type::factory($row[$c->name],
			                                           $row[$c->raw_type],
												       $row[$c->rich_type],
												       is_string($row[$c->type_spec])? unserialize($row[$c->type_spec]) : null,
												       $row[$c->desc],
												       $row[$c->default_flags],
												       $row[$c->flags],
													   $row[$c->priority],
													   $row[$c->order]);
		}
		$res->free();
		return $out;

	}
	
}
?>
