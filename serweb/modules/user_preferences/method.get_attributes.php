<?
/*
 * $Id: method.get_attributes.php,v 1.5 2005/12/02 12:07:03 kozlik Exp $
 */

class CData_Layer_get_attributes {
	var $required_methods = array();
	
	/**
	 *  Get values of attributes
	 *
	 *	This method successively searching attributes in:
	 *	 - user_attrs   (if option uid is specified)
	 *	 - domain_attrs (if option uid is specified)
	 *	 - global_attrs 
	 *	and return them as array of associative arrays. The assoc. array has 
	 *	two fields:
	 *	 - value
	 *	 - origin - may contain 'user' or 'domain' or 'global' and specifying 
	 *	            where the attribute has been found
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *    uid	(string)     default: null
	 *		user id
	 *    did	(string)     default: null
	 *		domain id
	 *
	 *	@param	array	$opt		associative array of options
	 *	@param	array	$errors		error messages
	 *	@return array
	 */ 
	 
	function get_attributes($opt, &$errors){
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

		$out = array();

		/*
		 *	get global_attrs
		 */
		$flags_val = $fg['DB_FOR_SERWEB'];

		$q="select ".$cg->name." as name,
		           ".$cg->value." as value 
		    from ".$t_ga."
			where (".$cg->flags." & ".$flags_val.") = ".$flags_val;
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		if ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			return array('value' => $row['value'],
			             'origin' => 'global');
		}

		$this->get_attribs_format_output($out, $res, 'global');

		$res->free();

		/*
		 *	get domain_attrs
		 */
		if ($opt_did){
			$flags_val = $fd['DB_FOR_SERWEB'];

			$q="select ".$cd->name." as name, 
			           ".$cd->value." as value 
				from ".$t_da."
				where  ".$cd->did." = '".$opt_did."' and 
					  (".$cd->flags." & ".$flags_val.") = ".$flags_val;
			
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$this->get_attribs_format_output($out, $res, 'domain');

			$res->free();
		}

		/*
		 *	get user_attrs
		 */
		if ($opt_uid){
			$flags_val = $fu['DB_FOR_SERWEB'];

			$q="select ".$cu->name." as name, 
			           ".$cu->value." as value 
			    from ".$t_ua."
				where  ".$cu->uid." = '".$opt_uid."' and 
					  (".$cu->flags." & ".$flags_val.") = ".$flags_val;
			
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$this->get_attribs_format_output($out, $res, 'user');

			$res->free();
		}

		return $out;

	}

	/**
	 *  Format the result of DB query into array
	 *	
	 *	(@see get_attributes)
	 *	
	 *	@private
	 *	@param	array		$out		output array
	 *	@param	DB_Result	$res		result of DB query
	 *	@param	string		$origin		
	 */ 
	function get_attribs_format_output(&$out, &$res, $origin){

		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			if (!isset($out[$row['name']]) or $out[$row['name']]['origin'] != $origin){
				$out[$row['name']] = array('value' => $row['value'],
				                           'origin' => $origin);
			}
			else{
				$out[$row['name']]['value'] = array_merge(
				                               (array)$out[$row['name']]['value'],
				                                array($row['value']));
			}
		}
	}
}
?>
