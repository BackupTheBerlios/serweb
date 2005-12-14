<?
/*
 * $Id: method.get_aliases.php,v 1.2 2005/12/14 16:30:19 kozlik Exp $
 */

class CData_Layer_get_aliases {
	var $required_methods = array();
	
	/*
	 *	return array of aliases of user
	 */
	  
	function get_aliases($user, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		/* table's name */
		$t_name = &$config->data_sql->uri->table_name;
		/* col names */
		$c = &$config->data_sql->uri->cols;
		/* flags */
		$f = &$config->data_sql->uri->flag_values;

		$flags_val = $f['DB_IS_TO'];

		$q="select ".$c->username." as username, 
		           ".$c->did." as did 
		    from ".$t_name." 
			where ".$c->uid." = '".$user->uuid."' and 
			      (".$c->flags." & ".$flags_val.") = ".$flags_val."
			order by ".$c->username;

		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)){
			$out[]=$row;
		}
		$res->free();
		return $out;
	}
	
}
?>
