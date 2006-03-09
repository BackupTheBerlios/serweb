<?
/*
 * $Id: method.del_im.php,v 1.3 2006/03/09 11:51:52 kozlik Exp $
 */

class CData_Layer_del_im {
	var $required_methods = array();
	
	function del_im($uid, $mid){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		$t_name = &$config->data_sql->msg_silo->table_name;	/* table's name */
		$c = &$config->data_sql->msg_silo->cols;				/* col names */

		if (!is_numeric($mid)) {
			ErrorHandler::log_errors(PEAR::raiseError("Wrong message ID: ".$mid)); 
			return false;
		}

		$q="delete from ".$t_name." 
		    where ".$c->mid." = ".$this->sql_format($mid, "n")." and 
			      ".$c->uid." = ".$this->sql_format($uid, "s");
		    
		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
		return true;
	}
	
}
?>
