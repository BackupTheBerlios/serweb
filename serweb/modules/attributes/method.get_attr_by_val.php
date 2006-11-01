<?php
/*
 * $Id: method.get_attr_by_val.php,v 1.5 2006/11/01 13:47:49 kozlik Exp $
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
	 *	  count_only
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

		$id = $columns = null;
		if ($type == 'uri'){
			$t_name = &$config->data_sql->uri_attrs->table_name;	/* table's name */
			$c = &$config->data_sql->uri_attrs->cols;				/* col names */
			$f = &$config->data_sql->uri_attrs->flag_values;		/* flags */
			$columns = $c->username." as username, ".$c->did." as did, ";
		}
		elseif ($type == 'user'){
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
		if (isset($opt['name']))  $qw .= " and ".$c->name."  = ".$this->sql_format($opt['name'],  "s");
		if (isset($opt['value'])) $qw .= " and ".$c->value." = ".$this->sql_format($opt['value'], "s");


		/*
		 *	get global_attrs
		 */
		$flags_val = $f['DB_FOR_SERWEB'];

		if (!empty($opt['count_only'])){
			$q="select count(*)
			    from ".$t_name."
				where (".$c->flags." & ".$flags_val.") = ".$flags_val.$qw;
		}
		else{
			$q="select ".($id ? $id." as id, ": "").
			             ($columns ? $columns: "")." 
			           ".$c->name." as name,
			           ".$c->value." as value
			    from ".$t_name."
				where (".$c->flags." & ".$flags_val.") = ".$flags_val.$qw;
		}
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			ErrorHandler::add_error($errors);
			return false;
		}

		if (!empty($opt['count_only'])){
			$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
			$out = $row[0];
		}
		else{
			$out = array();
			for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
				$out[$i] = $row;
			}
		}

		$res->free();

		return $out;
	}
}
?>
