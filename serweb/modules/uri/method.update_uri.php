<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.update_uri.php,v 1.4 2007/02/14 16:46:31 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_uri
 */ 

/**
 *	Data layer container holding the method for update uri table
 * 
 *	@package    serweb
 *	@subpackage mod_uri
 */ 
class CData_Layer_update_uri {
	var $required_methods = array();
	
	/**
	 *  Update URI table
	 *
	 *  Possible options:
	 *	 - none
	 *	
	 *	@param	array	$values
	 *	@param	array	$filter
	 *	@param	array	$opt
	 *	@return bool			FALSE on error, TRUE otherwise
	 */ 
	 
	function update_uri($values, $filter, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table name */
		$t_name = &$config->data_sql->uri->table_name;
		/* col names */
		$c = &$config->data_sql->uri->cols;
		/* flags */
		$f = &$config->data_sql->uri->flag_values;

		$an = &$config->attr_names;

		$qw = "";
		if (isset($filter['scheme']))   $qw .= $c->scheme."   = ".$this->sql_format($filter['scheme'],   "s")." and ";
		if (isset($filter['uid']))      $qw .= $c->uid."      = ".$this->sql_format($filter['uid'],      "s")." and ";
		if (isset($filter['did']))      $qw .= $c->did."      = ".$this->sql_format($filter['did'],      "s")." and ";
		if (isset($filter['username'])) $qw .= $c->username." = ".$this->sql_format($filter['username'], "s")." and ";
		if (isset($filter['flags']))    $qw .= $c->flags."    = ".$this->sql_format($filter['flags'],    "s")." and ";
		$qw .= $this->get_sql_bool(true);

		$qs = array();
		if (isset($values['scheme']))   $qs[] = $c->scheme."   = ".$this->sql_format($values['scheme'],   "s");
		if (isset($values['uid']))      $qs[] = $c->uid."      = ".$this->sql_format($values['uid'],      "s");
		if (isset($values['did']))      $qs[] = $c->did."      = ".$this->sql_format($values['did'],      "s");
		if (isset($values['username'])) $qs[] = $c->username." = ".$this->sql_format($values['username'], "s");
		if (isset($values['flags']))    $qs[] = $c->flags."    = ".$this->sql_format($values['flags'],    "n");

		$qs = implode(", ", $qs);


		$q = "update ".$t_name." 
		      set ".$qs."
		      where ".$qw;

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
