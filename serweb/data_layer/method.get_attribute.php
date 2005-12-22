<?php
/*
 * $Id: method.get_attribute.php,v 1.4 2005/12/22 13:24:40 kozlik Exp $
 */

class CData_Layer_get_attribute {
	var $required_methods = array();

	/**
	 *  Get value of attribute
	 *
	 *	This method successively searching attribute in:
	 *	 - user_attrs   (if option uid is specified)
	 *	 - domain_attrs (if option uid is specified)
	 *	 - global_attrs 
	 *	and return its value as associative array. The aray has two fields
	 *	 - value
	 *	 - origin - may contain 'user' or 'domain' or 'global' and specifying 
	 *	            where the attribute has been found
	 *	If the attribute is not found in any table, NULL is returned. On error
	 *	this method returning FALSE.
	 *
	 *  Possible options:
	 *    uid	(string)     default: null
	 *		user id
	 *    did	(string)     default: null
	 *		domain id
	 *
	 *	@param	string	$name		name of attribute
	 *	@param	array	$opt		associative array of options
	 *	@param	array	$errors		error messages
	 *	@return mixed				see above
	 */ 
	function get_attribute($name, $opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		/* tables' names */
		$t_ua = &$config->data_sql->user_attrs->table_name;
		$t_da = &$config->data_sql->domain_attrs->table_name;
		$t_ga = &$config->data_sql->global_attrs->table_name;
		/* col names */
		$cu = &$config->data_sql->user_attrs->cols;
		$cd = &$config->data_sql->domain_attrs->cols;
		$cg = &$config->data_sql->global_attrs->cols;
		/* flags */
		$fu = &$config->data_sql->user_attrs->flag_values;
		$fd = &$config->data_sql->domain_attrs->flag_values;
		$fg = &$config->data_sql->global_attrs->flag_values;

		/* set default values for options */
		$opt_uid = isset($opt["uid"]) ? $opt["uid"] : null;
		$opt_did = isset($opt["did"]) ? $opt["did"] : null;

		
		/*
		 *	look up the attribute among user_attrs
		 */
		if ($opt_uid){
			$flags_val = $fu['DB_FOR_SERWEB'];

			$q="select ".$cu->value." as value from ".$t_ua."
				where ".$cu->name." = '".$name."' and 
				      ".$cu->uid." = '".$opt_uid."' and 
					  (".$cu->flags." & ".$flags_val.") = ".$flags_val;
			
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$out = $this->get_attrib_format_output($res, 'user');
			if (is_array($out)) return $out;
		}


		/*
		 *	look up the attribute among domain_attrs
		 */
		if ($opt_did){
			$flags_val = $fd['DB_FOR_SERWEB'];

			$q="select ".$cd->value." as value from ".$t_da."
				where ".$cd->name." = '".$name."' and 
				      ".$cd->did." = '".$opt_did."' and 
					  (".$cd->flags." & ".$flags_val.") = ".$flags_val;
			
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$out = $this->get_attrib_format_output($res, 'domain');
			if (is_array($out)) return $out;
		}


		/*
		 *	look up the attribute among global_attrs
		 */
		$flags_val = $fg['DB_FOR_SERWEB'];

		$q="select ".$cg->value." as value from ".$t_ga."
			where ".$cg->name." = '".$name."' and (".$cg->flags." & ".$flags_val.") = ".$flags_val;
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out = $this->get_attrib_format_output($res, 'global');
		if (is_array($out)) return $out;

		return null;

	}

	/**
	 *  Format the result of DB query into array
	 *	
	 *	(@see get_attribute)
	 *	
	 *	@private
	 *	@param	DB_Result	$res		result of DB query
	 *	@param	string		$origin		
	 *	@return mixed				see above
	 */ 
	function get_attrib_format_output(&$res, $origin){

		if ($res->numRows() > 1){
			$out = array('value' => array(),
			             'origin' => $origin);

			while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
				$out['value'][] = $row['value'];
			}
			return $out;
		}

		if ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			return array('value' => $row['value'],
			             'origin' => $origin);
		}

		return null;
	}
	
}
?>
