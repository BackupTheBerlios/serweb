<?php
/*
 * $Id: method.set_db_collation.php,v 1.1 2005/05/24 12:22:37 kozlik Exp $
 */

class CData_Layer_set_db_collation {
	var $required_methods = array();
	
	/*
	 * set collation - for MySQL >= 4.1
	 */

	function set_db_collation($collation, $opt, &$errors){
	 	global $config;

		$this->db_collation = $collation;

		/* if connection to db is estabilished run sql query setting the collation */		
		if ($this->db){
			$q="set collation_connection='".$this->db_collation."'";
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {
				log_errors($res, $errors); return false;
			}
		}
		
		/* otherwise do nothing, collation will be set after connect to DB */

		return true;
	}
}

?>
