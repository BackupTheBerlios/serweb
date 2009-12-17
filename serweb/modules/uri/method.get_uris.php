<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_uris.php,v 1.1 2009/12/17 12:11:56 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for get URIs of user
 * 
 *	@package    serweb
 */ 
class CData_Layer_get_uris {
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
	  
	function get_uris($uid, $opt){
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


        $qw = array();
        if (!is_null($uid))                      $qw[] = $c->uid." = ".$this->sql_format($uid, "s");

        if (isset($opt['filter']['did']))        $qw[] = $opt['filter']['did']->to_sql($c->did);
        if (isset($opt['filter']['username']))   $qw[] = $opt['filter']['username']->to_sql($c->username);
        if (isset($opt['filter']['scheme']))     $qw[] = $opt['filter']['scheme']->to_sql($c->scheme);

        if ($qw) $qw = " where ".implode(' and ', $qw);
        else $qw = "";



		$q="select ".$c->scheme." as scheme, 
		           ".$c->uid." as uid, 
				   ".$c->username." as username, 
				   ".$c->did." as did,
				   ".$c->flags." as flags
		    from ".$t_name. 
			$qw."
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
