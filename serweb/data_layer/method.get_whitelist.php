<?
/*
 * $Id: method.get_whitelist.php,v 1.1 2004/09/16 17:19:46 kozlik Exp $
 */

/*
 *  Function return array of associtive arrays containig whitelist of $user
 *
 *  Keys of associative arrays:
 *    uri
 *    primary_key            - array - reflect primary key without user identification
 *
 *
 *  Possible options parameters:
 *
 *		none
 *  
 *  
 */ 

class CData_Layer_get_whitelist {
	var $required_methods = array();

	function get_whitelist($user, $opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select uri from ".$config->data_sql->table_whitelist.
			" where ".$this->get_indexing_sql_where_phrase($user)." order by uri";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]   = $row;
			$out[$i]['primary_key']  = array('uri' => &$out[$i]['uri']);
		}
		
		$res->free();
		return $out;
	}
	
}
?>
