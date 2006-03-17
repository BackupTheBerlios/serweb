<?
/*
 * $Id: method.get_credentials.php,v 1.1 2006/03/17 14:26:50 kozlik Exp $
 */

class CData_Layer_get_credentials {
	var $required_methods = array();
	
	/**
	 *  return array of credentials of user
	 *
	 *  Possible options:
	 *	  none
	 *    	
	 *	@param	string	$uid	uid of user
	 *	@param	array	$opt	array of options
	 *	@return array			array of credentials
	 */ 
	  
	function get_credentials($uid, $opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->credentials->table_name;
		/* col names */
		$c = &$config->data_sql->credentials->cols;
		/* flags */
		$f = &$config->data_sql->credentials->flag_values;


		$q="select ".$c->uname.", 
		           ".$c->realm.",
		           ".$c->flags."
		    from ".$t_name." 
			where ".$c->uid." = ".$this->sql_format($uid, "s")."
			order by ".$c->realm.", ".$c->uname;

		
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}

		$out=array();
		for ($i=0; $row = $res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i] = new Credential($uid, $row[$c->uname], $row[$c->realm], $row[$c->flags]);
		}
		$res->free();
		return $out;
	}
	
}
?>