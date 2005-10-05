<?php
/*
 * $Id: method.get_domains.php,v 1.3 2005/10/05 11:22:52 kozlik Exp $
 */

class CData_Layer_get_domains {
	var $required_methods = array('get_domain');
	
	/**
	 *  return array of associtive arrays containig domains
	 *
	 *  Keys of associative arrays:
	 *    id
	 *    names - array returned by method get_domain
	 *    customer
	 *
	 *  Possible options:
	 *
	 *    filter	(array)     default: array()
	 *      associative array of pairs (column, value) which should be returned
	 *     
	 *	  get_domain_names (bool) default: false
	 *		if true, function return aliases of domain in 'names' keys of returned array.
	 *		Otherwise the keys 'names' are empty
	 *
	 *	  return_all		(bool)	default: false
	 *		if true, the result isn't limited by LIMIT sql phrase
	 *
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return array			array of domains or FALSE on error
	 */ 
	function get_domains($opt, &$errors){
		global $config, $serweb_auth, $sess;

		if (!$this->connect_to_db($errors)) return false;

		$cd = &$config->data_sql->domain;
		$cp = &$config->data_sql->dom_pref;
		$cc = &$config->data_sql->customer;

	    $o_filter = (isset($opt['filter'])) ? $opt['filter'] : array();
	    $o_get_names = (isset($opt['get_domain_names'])) ? (bool)$opt['get_domain_names'] : false;
	    $o_return_all = (isset($opt['return_all'])) ? (bool)$opt['return_all'] : false;

		$qw=" true ";
		if (!empty($o_filter['id']))          $qw .= "and d.".$cd->id." LIKE '%".$o_filter['id']."%' ";
		if (!empty($o_filter['name']))        $qw .= "and d.".$cd->name." LIKE '%".$o_filter['name']."%' ";
		if (!empty($o_filter['customer']))    $qw .= "and c.".$cc->name." LIKE '%".$o_filter['customer']."%' ";
		if (!empty($o_filter['customer_id'])) $qw .= "and c.".$cc->id." = '".$o_filter['customer_id']."' ";

		/* get num rows */		
		$q="select count(distinct d.".$cd->id.") 
		    from (".$config->data_sql->table_domain." d left outer join ".$config->data_sql->table_dom_preferences." dp 
			       on d.".$cd->id." = dp.".$cp->id." and dp.".$cp->att_name." = 'owner') 
				   left outer join ".$config->data_sql->table_customer." c
			       on (dp.".$cp->att_value." = c.".$cc->id." and dp.".$cp->att_name." = 'owner')
			where ".$qw;
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$this->set_num_rows($row[0]);
		$res->free();

		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();
	
		$q="select d.".$cd->id.", c.".$cc->name.", dpd.".$cp->att_value." as disabled
		    from (".$config->data_sql->table_domain." d left outer join ".$config->data_sql->table_dom_preferences." dp 
			       on d.".$cd->id." = dp.".$cp->id." and dp.".$cp->att_name." = 'owner')
				   left outer join ".$config->data_sql->table_customer." c
			       on (dp.".$cp->att_value." = c.".$cc->id." and dp.".$cp->att_name." = 'owner')
			       left outer join ".$config->data_sql->table_dom_preferences." dpd 
			       on (d.".$cd->id." = dpd.".$cp->id." and dpd.".$cp->att_name." = 'disabled')
			where ".$qw." 
			group by d.".$cd->id."
			order by d.".$cd->id.
			($o_return_all ? "" : $this->get_sql_limit_phrase());

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]['id']		   = $row[$cd->id];
			$out[$i]['customer']   = $row[$cc->name];
			$out[$i]['disabled']   = $row['disabled'];

			$o = array('filter' => array('id' => $row[$cd->id]));
			if ($o_get_names){
				if (false === $out[$i]['names'] = $this->get_domain($o, $errors)) return false;
			}

			$out[$i]['primary_key']  = array('id' => &$out[$i]['id']);
		}
		$res->free();

		return $out;			
	}
	
}
?>
