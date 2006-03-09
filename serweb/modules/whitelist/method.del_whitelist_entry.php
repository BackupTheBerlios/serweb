<?
/*
 * $Id: method.del_whitelist_entry.php,v 1.2 2006/03/09 11:51:52 kozlik Exp $
 */

/*
 *  Function delete entry from whitelist of $user
 *
 *
 *  Possible options parameters:
 *
 *    primary_key	(array) required
 *      contain primary key (without user specification) of record which should be updated
 *      The array contain the same keys as functon get_whitelist returned in entry 'primary_key'
 *
 */ 

class CData_Layer_del_whitelist_entry {
	var $required_methods = array();

	function del_whitelist_entry($user, $opt, &$errors){
		global $config, $lang_str;
		
		if (!$this->connect_to_db($errors)) return false;

		if (!isset($opt['primary_key']) or !is_array($opt['primary_key']) or empty($opt['primary_key'])){
			log_errors(PEAR::raiseError('primary key is missing'), $errors); return false;
		}


		$q="delete from ".$config->data_sql->table_whitelist." 
			where uri=".$this->sql_format($opt['primary_key']['uri'], "s")." and 
				  ".$this->get_indexing_sql_where_phrase($user);

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}
		return true;

	}

}
?>
