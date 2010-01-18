<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_uris.php,v 1.2 2010/01/18 15:02:14 kozlik Exp $
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
		$t_name =  &$config->data_sql->uri->table_name;
		$td_name = &$config->data_sql->domain->table_name;
		/* col names */
		$c  = &$config->data_sql->uri->cols;
		$cd = &$config->data_sql->domain->cols;
		/* flags */
		$f = &$config->data_sql->uri->flag_values;
		$fd = &$config->data_sql->domain->flag_values;


	    $o_order_by = (isset($opt['order_by'])) ? $opt['order_by'] : "uri";
	    $o_order_desc = (!empty($opt['order_desc'])) ? "desc" : "";

        $qw = array();
        if (!is_null($uid))                      $qw[] = "u.".$c->uid." = ".$this->sql_format($uid, "s");

        if (isset($opt['filter']['did']))        $qw[] = $opt['filter']['did']->to_sql("u.".$c->did);
        if (isset($opt['filter']['username']))   $qw[] = $opt['filter']['username']->to_sql("u.".$c->username);
        if (isset($opt['filter']['scheme']))     $qw[] = $opt['filter']['scheme']->to_sql("u.".$c->scheme);
        if (isset($opt['filter']['flags']))      $qw[] = $opt['filter']['flags']->to_sql("u.".$c->flags);

        if ($qw) $qw = " where ".implode(' and ', $qw);
        else $qw = "";

        $cols = "";
        if ($o_order_by == 'uri'){
            $dom_q = "(select d.".$cd->name." 
                       from ".$td_name." d 
                       where u.".$c->did." = d.".$cd->did." 
                       order by (d.".$cd->flags." & ".$fd['DB_CANON'].") desc 
                       limit 1)"; 
        
            $cols .= ", concat(u.".$c->scheme.", ':', u.".$c->username.", '@', ".$dom_q.") as uri"; 
        }

        if (!empty($opt['use_pager']) or !empty($opt['count_only'])){
            $q="select count(*)
                from ".$t_name." u ".$qw;
            
            $res=$this->db->query($q);
            if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
            $row=$res->fetchRow(DB_FETCHMODE_ORDERED);
            $this->set_num_rows($row[0]);
            $res->free();

            if (!empty($opt['count_only'])) return $row[0];
            
            /* if act_row is bigger then num_rows, correct it */
            $this->correct_act_row();
        }


		$q="select u.".$c->scheme." as scheme, 
		           u.".$c->uid." as uid, 
				   u.".$c->username." as username, 
				   u.".$c->did." as did,
				   u.".$c->flags." as flags ".
                   $cols."
		    from ".$t_name." u ". 
			$qw;

        if ($o_order_by) {
            if (isset($c->$o_order_by)) $q .= " order by ".$c->$o_order_by." ".$o_order_desc;
            else $q .= " order by ".$o_order_by." ".$o_order_desc;
        }

        $q .= !empty($opt['use_pager']) ? $this->get_sql_limit_phrase() : "";

		
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
