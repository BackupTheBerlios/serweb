<?
/*
 * $Id: method.add_whitelist_entry.php,v 1.2 2006/03/09 11:51:52 kozlik Exp $
 */

/*
 *  Function add new entry to whitelist of $user with $values
 *
 *  Keys of associative array $values:
 *    uri
 *
 *
 *  Possible options parameters:
 *
 *    none
 *
 */ 

class CData_Layer_add_whitelist_entry {
	var $required_methods = array();

	function add_whitelist_entry($user, $values, $opt, &$errors){
		global $config, $lang_str;
		
		if (!$this->connect_to_db($errors)) return false;

		$att=$this->get_indexing_sql_insert_attribs($user);

		$q="insert into ".$config->data_sql->table_whitelist." (
		           ".$att['attributes'].", 
				   uri
		    ) 
			values (
			       ".$att['values'].", 
				   ".$this->sql_format($values['uri'], "s")."
			 )";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
				$errors[]=$lang_str['err_whitelist_already_exists'];
			else log_errors($res, $errors); 
			return false;
		}
		return true;

	}

}
?>
