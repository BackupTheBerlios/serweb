<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_aliases.php,v 1.11 2009/09/30 16:11:44 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for get URIs of user
 * 
 *	@package    serweb
 */ 
class CData_Layer_get_aliases {
	var $required_methods = array();
	
	/**
	 *  return array of URI
	 *
	 *	return array of instances of class URI
	 *
	 *  Possible options:
	 *	 - filter (array) - filter URI by 'did' or by 'username' (default: null)
	 *
	 *	@param	string	$uid	uid of user - if is null return all URIs
	 *	@param	array	$opt	array of options
	 *	@return array			array of URI or FALSE on error
	 */ 
	  
	function get_aliases($uid, $opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->uri->table_name;
		/* col names */
		$c = &$config->data_sql->uri->cols;
		/* flags */
		$f = &$config->data_sql->uri->flag_values;

		$qw = "";
		if (!is_null($uid)) 
			$qw .= $c->uid." = ".$this->sql_format($uid, "s")." and ";

		if (isset($opt['filter']['did'])) 
			$qw .= $c->did." = ".$this->sql_format($opt['filter']['did'], "s")." and ";

		if (isset($opt['filter']['username'])) 
			$qw .= "lower(".$c->username.") = lower(".$this->sql_format($opt['filter']['username'], "s").") and ";

		if (isset($opt['filter']['scheme']))
			$qw .= $c->scheme." = ".$this->sql_format($opt['filter']['scheme'], "s")." and ";

		$qw .= $this->get_sql_bool(true);

		$q="select ".$c->scheme." as scheme, 
		           ".$c->uid." as uid, 
				   ".$c->username." as username, 
				   ".$c->did." as did,
				   ".$c->flags." as flags
		    from ".$t_name." 
			where ".$qw."
			order by ".$c->did.", ".$c->username;

		
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}

		$out=array();
		for ($i=0; $row = $res->fetchRow(DB_FETCHMODE_OBJECT); $i++){
			$out[$i] = new URI($row->uid, $row->did, $row->username, $row->flags);
			$out[$i]->set_scheme($row->scheme);
		}
		$res->free();
		return $out;
	}
	
}
?>
