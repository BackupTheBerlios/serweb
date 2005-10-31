<?php
/*
 * $Id: method.get_domains.php,v 1.1 2005/10/31 16:07:41 kozlik Exp $
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
	 *	  administrated_by	(Cserweb_auth)	default:null
	 *		if is set, only domains administrated by given admin is returned
	 *
	 *	  deleted_before (int)	default:false
	 *		if is set, only domains marked as deleted before given timestamp are returned
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
	    $o_admin = (isset($opt['administrated_by'])) ? $opt['administrated_by'] : null;
	    $o_deleted_before = (isset($opt['deleted_before'])) ? $opt['deleted_before'] : false;

		$qw="";
		if (!empty($o_filter['id']))          $qw .= "d.".$cd->id." LIKE '%".$o_filter['id']."%' and ";
		if (!empty($o_filter['name']))        $qw .= "d.".$cd->name." LIKE '%".$o_filter['name']."%' and ";
		if (!empty($o_filter['customer']))    $qw .= "c.".$cc->name." LIKE '%".$o_filter['customer']."%' and ";
		if (!empty($o_filter['customer_id'])) $qw .= "c.".$cc->id." = '".$o_filter['customer_id']."' and ";

		/* prepare SQL query */

		$q_admin_from = "";
		$q_admin_where = "";
		if (!is_null($o_admin)){
			$u = ($config->users_indexed_by=='uuid') ?
					$o_admin->uuid :
					($o_admin->uname."@".$o_admin->domain);

			$q_admin_from = 
				" left outer join ".$config->data_sql->table_dom_preferences." dpa 
				  on (d.".$cd->id." = dpa.".$cp->id." and dpa.".$cp->att_name." = 'admin') ";

			$q_admin_where = "dpa.".$cp->att_value." = '".$u."' and ";
		}
	
		if ($o_deleted_before)
			$q_deleted_where = " dpx.".$cp->att_value." < ".$o_deleted_before." and dpx.".$cp->att_value." > 0 ";
		else
			$q_deleted_where = " not COALESCE((dpx.".$cp->att_value." > 0), 0)";


		/* second select is necessary to get domains without aliases 
		   both selects are same except the table from which is obtained list of domains.
		   table_domain in first select and table_dom_preferences in second select 
		  
		   Statement 'not COALESCE((dpx.".$cp->att_value." > 0), 0)' is used for skip deleted domains 
		*/
		$q1="select d.".$cd->id." as dom_id, c.".$cc->name.", dpd.".$cp->att_value." as disabled
		    from (".$config->data_sql->table_domain." d left outer join ".$config->data_sql->table_dom_preferences." dp 
			       on d.".$cd->id." = dp.".$cp->id." and dp.".$cp->att_name." = 'owner')
				   left outer join ".$config->data_sql->table_customer." c
			       on (dp.".$cp->att_value." = c.".$cc->id." and dp.".$cp->att_name." = 'owner')
			       left outer join ".$config->data_sql->table_dom_preferences." dpd 
			       on (d.".$cd->id." = dpd.".$cp->id." and dpd.".$cp->att_name." = 'disabled')
			       left outer join ".$config->data_sql->table_dom_preferences." dpx 
			       on (d.".$cd->id." = dpx.".$cp->id." and dpx.".$cp->att_name." = 'deleted')
			       ".$q_admin_from."
			where ".$qw.$q_admin_where.$q_deleted_where."
			group by d.".$cd->id;
			
		$q2="select d.".$cp->id." as dom_id, c.".$cc->name.", dpd.".$cp->att_value." as disabled
		    from (".$config->data_sql->table_dom_preferences." d left outer join ".$config->data_sql->table_dom_preferences." dp 
			       on d.".$cp->id." = dp.".$cp->id." and dp.".$cp->att_name." = 'owner')
				   left outer join ".$config->data_sql->table_customer." c
			       on (dp.".$cp->att_value." = c.".$cc->id." and dp.".$cp->att_name." = 'owner')
			       left outer join ".$config->data_sql->table_dom_preferences." dpd 
			       on (d.".$cp->id." = dpd.".$cp->id." and dpd.".$cp->att_name." = 'disabled')
			       left outer join ".$config->data_sql->table_dom_preferences." dpx 
			       on (d.".$cp->id." = dpx.".$cp->id." and dpx.".$cp->att_name." = 'deleted')
			       ".$q_admin_from."
			where ".$qw.$q_admin_where.$q_deleted_where."
			group by d.".$cp->id;

		if (empty($o_filter['name']))			
			$q = "(".$q1.") union (".$q2.")";
		else $q = &$q1;	
		
		if (!$o_return_all){
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			$this->set_num_rows($res->numRows());
			$res->free();

			/* if act_row is bigger then num_rows, correct it */
			$this->correct_act_row();
		}

		$q.=" order by dom_id";
		$q.=($o_return_all ? "" : $this->get_sql_limit_phrase());

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		if ($o_return_all){
			$this->set_num_rows($res->numRows());
		}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]['id']		   = $row['dom_id'];
			$out[$i]['customer']   = $row[$cc->name];
			$out[$i]['disabled']   = $row['disabled'];

			$o = array('filter' => array('id' => $row['dom_id']));
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
