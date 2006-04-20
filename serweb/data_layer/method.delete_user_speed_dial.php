<?
/*
 * $Id: method.delete_user_speed_dial.php,v 1.3 2006/04/20 07:38:29 kozlik Exp $
 */

class CData_Layer_delete_user_speed_dial {
	var $required_methods = array();
	
	/*
	 * delete all records of user from speed_dial table
	 */
	
	function delete_user_speed_dial($uid){
	 	global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$t_name  = &$config->data_sql->speed_dial->table_name;
		$ta_name = &$config->data_sql->sd_attrs->table_name;
		/* col names */
		$c  = &$config->data_sql->speed_dial->cols;
		$ca = &$config->data_sql->sd_attrs->cols;


		$q="select ".$c->id." as id from ".$t_name." where ".$c->uid." = '".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) return true;  //expected, table mayn't exist in installed version
			else { ErrorHandler::log_errors($res); return false; }
		}

		$ids = array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$ids[] = $row['id'];
		}

		if (count($ids)){
			$q = "delete from ".$ta_name." where ".$this->get_sql_in($ca->id, $ids);

			$res=$this->db->query($q);
			if (DB::isError($res)) {
				if ($res->getCode()==DB_ERROR_NOSUCHTABLE) {}  //expected, table mayn't exist in installed version
				else { ErrorHandler::log_errors($res); return false; }
			}
		}


		$q="delete from ".$t_name." where ".$c->uid." = '".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }
		return true;
	}
}
?>
