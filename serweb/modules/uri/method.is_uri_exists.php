<?
/*
 * $Id: method.is_uri_exists.php,v 1.1 2005/12/22 13:48:46 kozlik Exp $
 */

class CData_Layer_is_uri_exists {
	var $required_methods = array();
	
	/*
	 *	check if user exists
	 */

	function is_uri_exists($uname, $did){
	 	global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return 0;
		}

		/* table's name */
		$tu_name = &$config->data_sql->uri->table_name;
		/* col names */
		$cu = &$config->data_sql->uri->cols;
		/* flags */
		$fu = &$config->data_sql->uri->flag_values;


		$q="select count(*) from ".$tu_name." 
		    where lower(".$cu->username.")=lower('".$uname."') and 
			      lower(".$cu->did.")=lower('".$did."')";
		$res=$this->db->query($q);

		if (DB::isError($res)) {ErrorHandler::log_errors($res); return 0;}

		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();
		if ($row[0]) return -1;

		return 1;
	}
}
?>
