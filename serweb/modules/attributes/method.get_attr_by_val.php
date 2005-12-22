<?php
/*
 * $Id: method.get_attr_by_val.php,v 1.1 2005/12/22 13:51:23 kozlik Exp $
 */

class CData_Layer_get_attr_by_val {
	var $required_methods = array();
	
	/**
	 *  Get values of global attributes
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *	  name
	 *		name of atribute	
	 *
	 *	  value
	 *		value of atribute	
	 *
	 *	@return array
	 */ 
	 
	function get_attr_by_val($type, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		if ($type == 'user'){
			$t_name = &$config->data_sql->user_attrs->table_name;	/* table's name */
			$c = &$config->data_sql->user_attrs->cols;				/* col names */
			$f = &$config->data_sql->user_attrs->flag_values;		/* flags */
			$id = $c->uid;
		}
		elseif ($type == 'domain') {
			$t_name = &$config->data_sql->domain_attrs->table_name;	/* table's name */
			$c = &$config->data_sql->domain_attrs->cols;			/* col names */
			$f = &$config->data_sql->domain_attrs->flag_values;		/* flags */
			$id = $c->did;
		}
		else{ //type == global
			$t_name = &$config->data_sql->global_attrs->table_name;	/* table's name */
			$c = &$config->data_sql->global_attrs->cols;			/* col names */
			$f = &$config->data_sql->global_attrs->flag_values;		/* flags */
			$id = "";
		}


		$qw = "";
		if (isset($opt['name']))  $qw .= " and ".$c->name." = '".$opt['name']."'";
		if (isset($opt['value'])) $qw .= " and ".$c->value." = '".$opt['value']."'";


		/*
		 *	get global_attrs
		 */
		$flags_val = $f['DB_FOR_SERWEB'];

		$q="select ".($id ? $id." as id, ": "")." 
		           ".$c->name." as name,
		           ".$c->value." as value
		    from ".$t_name."
			where (".$c->flags." & ".$flags_val.") = ".$flags_val.$qw;
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			ErrorHandler::add_error($errors);
			return false;
		}

		$out = array();

		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i] = $row;
		}

		$res->free();

		return $out;
	}
}
?>
