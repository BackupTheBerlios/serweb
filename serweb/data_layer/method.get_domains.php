<?php
/*
 * $Id: method.get_domains.php,v 1.6 2006/04/10 13:03:35 kozlik Exp $
 */

class CData_Layer_get_domains {
	var $required_methods = array('get_domain', 'get_domain_flags');
	
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
	 *	  get_domain_flags	(bool)	default: false
	 *		if true, function return flags of domain in 'deleted' and 'disabled' keys of returned array.
	 *
	 *	  return_all		(bool)	default: false
	 *		if true, the result isn't limited by LIMIT sql phrase
	 *
	 *	  only_domains		(array)	default:null
	 *		Array of domain IDs. if is set, only domains from this array are returned
	 *
	 *	  check_deleted_flag	(bool)	default:true
	 *		If true, domains marked as deleted are not returned
	 *
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return array			array of domains or FALSE on error
	 */ 
	function get_domains($opt, &$errors){
		global $config, $serweb_auth, $sess;

		if (!$this->connect_to_db($errors)) return false;


		/* table's name */
		$td_name = &$config->data_sql->domain->table_name;
		$ta_name = &$config->data_sql->domain_attrs->table_name;
		$tc_name = &$config->data_sql->customers->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		$ca = &$config->data_sql->domain_attrs->cols;
		$cc = &$config->data_sql->customers->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;
		$fa = &$config->data_sql->domain_attrs->flag_values;
		/* attribute names */
		$an = &$config->attr_names;


	    $o_filter =      (isset($opt['filter'])) ? $opt['filter'] : array();
	    $o_get_names =   (isset($opt['get_domain_names'])) ? (bool)$opt['get_domain_names'] : false;
	    $o_get_flags =   (isset($opt['get_domain_flags'])) ? (bool)$opt['get_domain_flags'] : false;
	    $o_order_names = (isset($opt['order_names'])) ? (bool)$opt['order_names'] : true;
	    $o_return_all =  (isset($opt['return_all'])) ? (bool)$opt['return_all'] : false;
	    $o_did_filter =  (isset($opt['only_domains'])) ? $opt['only_domains'] : null;
	    $o_check_deleted =  (isset($opt['check_deleted_flag'])) ? $opt['check_deleted_flag'] : true;
	    

		$qw="";
		if (!empty($o_filter['id']))          $qw .= "d.".$cd->did."  LIKE ".$this->sql_format("%".$o_filter['id']."%",       "s")." and ";
		if (!empty($o_filter['name']))        $qw .= "d.".$cd->name." LIKE ".$this->sql_format("%".$o_filter['name']."%",     "s")." and ";
		if (!empty($o_filter['customer']))    $qw .= "c.".$cc->name." LIKE ".$this->sql_format("%".$o_filter['customer']."%", "s")." and ";
		if ( isset($o_filter['customer_id'])) $qw .= "c.".$cc->cid." = ".$this->sql_format($o_filter['customer_id'], "s")." and ";

		/* prepare SQL query */

		$q_did_filter = "";
		if (!is_null($o_did_filter)){
			$q_did_filter = $this->get_sql_in("d.".$cd->did, $o_did_filter, true)." and ";
		}

		$q1_deleted = $q2_deleted = $this->sql_format(true, "b");
		if ($o_check_deleted){
			$q1_deleted = " (d.".$cd->flags." & ".$fd['DB_DELETED'].") = 0";
			$q2_deleted = " (d.".$ca->flags." & ".$fa['DB_DELETED'].") = 0";
		}


		if ($this->db_host['parsed']['phptype'] == 'mysql') {
			$q_dist1 = $q_dist2 = "";
			$q_grp1 = " group by d.".$cd->did;
			$q_grp2 = " group by d.".$ca->did;
		}
		else {
			$q_dist1 = " distinct on (d.".$cd->did.") ";
			$q_dist2 = " distinct on (d.".$ca->did.") ";
			$q_grp1 = $q_grp2 = "";
		}


		/* second select is necessary to get domains without aliases 
		   both selects are same except the table from which is obtained list of domains.
		   table_domain in first select and table_dom_preferences in second select 
		  
		   Statement 'not COALESCE((dpx.".$cp->att_value." > 0), 0)' is used for skip deleted domains 
		*/


		$q1="select ".$q_dist1."
		            d.".$cd->did." as did, 
		            c.".$cc->name."
		    from (".$td_name." d left outer join ".$ta_name." dac 
			           on d.".$cd->did." = dac.".$ca->did." and dac.".$ca->name." = '".$an['dom_owner']."')
				   left outer join ".$tc_name." c
			           on (dac.".$ca->value." = c.".$cc->cid." and dac.".$ca->name." = '".$an['dom_owner']."')
			where ".$qw.$q_did_filter.$q1_deleted.$q_grp1;


		$q2="select ".$q_dist2."
		            d.".$ca->did." as did, 
		            c.".$cc->name."
		    from (".$ta_name." d left outer join ".$ta_name." dac 
			           on d.".$ca->did." = dac.".$ca->did." and dac.".$ca->name." = '".$an['dom_owner']."')
				   left outer join ".$tc_name." c
			           on (dac.".$ca->value." = c.".$cc->cid." and dac.".$ca->name." = '".$an['dom_owner']."')
			where ".$qw.$q_did_filter.$q2_deleted.$q_grp2;



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

		$q.=" order by did";
		$q.=($o_return_all ? "" : $this->get_sql_limit_phrase());

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		if ($o_return_all){
			$this->set_num_rows($res->numRows());
		}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]['id']		   = $row['did'];
			$out[$i]['customer']   = $row[$cc->name];

			if ($o_get_flags){
				if (false === $flags = $this->get_domain_flags($row['did'], null)) return false;
				$out[$i]['disabled']   = $flags['disabled'];
				$out[$i]['deleted']    = $flags['deleted'];
			}

			if ($o_get_names){
				$o = array('filter' => array('did' => $row['did']),
				           'check_deleted_flag' => $o_check_deleted,
						   'order_by' => $o_order_names ? "name" : "");
				if (false === $out[$i]['names'] = $this->get_domain($o)) return false;
			}

			$out[$i]['primary_key']  = array('id' => &$out[$i]['id']);
		}
		$res->free();

		return $out;			
	}
	
}
?>
