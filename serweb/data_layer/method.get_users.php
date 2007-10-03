<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_users.php,v 1.22 2007/10/03 15:21:10 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for get users
 * 
 *	@package    serweb
 */ 
class CData_Layer_get_users {
	var $required_methods = array('get_credentials');
	

	/**
	 *  Function return array of associtive arrays containig subscribers
	 *
	 *  Keys of associative arrays:
	 *   - username
	 *   - domain
	 *   - name
	 *   - fname
	 *   - lname
	 *   - phone
	 *   - email_address
	 *   - get_param
	 *   - aliases
	 *   - disabled
	 *
	 *  Possible options parameters:
	 *    - from_domains    (array) - array of domain IDs from which are 
	 *                                returned subscribers. By default are 
	 *                                returned all subscribers. (default:null)
	 *    - get_user_aliases (bool) - should return aliases of users? Could be 
	 *                                disabled from performance reasons. 
	 *                                (default: true)
	 *    - get_sip_uri      (bool) - return sip address of user (default: false)
	 *    - get_timezones    (bool) - return timezone of users
	 *    - only_users      (array)	- Array of user IDs. if is set, only users 
	 *                                from this array are returned (default:null)
	 *    - return_all       (bool)	- if true, the result isn't limited by LIMIT
	 *                                sql phrase (default: false)
	 *    - only_agreeing    (bool)	- if true, only subscribers agreeing to look
	 *                                up for them are returned (default: false)
	 *    - get_credentials  (bool) - return credentials of users in output 
	 *                                array (default: false)
 	 *	
	 *	@return array	array of users or FALSE on error
	 */ 
	 
	function get_users($filter, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$ta_name = &$config->data_sql->user_attrs->table_name;
		$tc_name = &$config->data_sql->credentials->table_name;
		$tu_name = &$config->data_sql->uri->table_name;
		$tl_name = &$config->data_sql->location->table_name;
		$td_name = &$config->data_sql->domain->table_name;
		$tda_name = &$config->data_sql->domain_attrs->table_name;
		/* col names */
		$ca = &$config->data_sql->user_attrs->cols;
		$cc = &$config->data_sql->credentials->cols;
		$cu = &$config->data_sql->uri->cols;
		$cl = &$config->data_sql->location->cols;
		$cd = &$config->data_sql->domain->cols;
		$cda = &$config->data_sql->domain_attrs->cols;
		/* flags */
		$fa = &$config->data_sql->user_attrs->flag_values;
		$fc = &$config->data_sql->credentials->flag_values;
		$fu = &$config->data_sql->uri->flag_values;

		$an = &$config->attr_names;

	    $opt_from_domains = (isset($opt['from_domains'])) ? $opt['from_domains'] : null;
	    $opt_get_aliases = (isset($opt['get_user_aliases'])) ? (bool)$opt['get_user_aliases'] : true;
	    $opt_get_sip_uri = (isset($opt['get_sip_uri'])) ? (bool)$opt['get_sip_uri'] : false;
	    $opt_get_timezones = (isset($opt['get_timezones'])) ? (bool)$opt['get_timezones'] : false;
	    $opt_uid_filter =  (isset($opt['only_users'])) ? $opt['only_users'] : null;
	    $opt_return_all = (isset($opt['return_all'])) ? (bool)$opt['return_all'] : false;
	    $opt_agreeing = (isset($opt['only_agreeing'])) ? (bool)$opt['only_agreeing'] : false;
	    $opt_get_disabled = (isset($opt['get_disabled'])) ? (bool)$opt['get_disabled'] : true;
	    $opt_get_credentials = (isset($opt['get_credentials'])) ? (bool)$opt['get_credentials'] : false;


	    $o_order_by = (isset($opt['order_by'])) ? $opt['order_by'] : "";
	    $o_order_desc = (!empty($opt['order_desc'])) ? "desc" : "";


		/* get users */

        $qw = array();
        if (!empty($filter['username']))  $qw[] = $filter['username']->to_sql("cr.".$cc->uname);
        if (!empty($filter['realm']))     $qw[] = $filter['realm']->to_sql("cr.".$cc->realm);
        if (!empty($filter['fname']))     $qw[] = $filter['fname']->to_sql("afn.".$ca->value);
        if (!empty($filter['lname']))     $qw[] = $filter['lname']->to_sql("aln.".$ca->value);
        if (!empty($filter['email']))     $qw[] = $filter['email']->to_sql("aem.".$ca->value);
        if (!empty($filter['uid']))       $qw[] = $filter['uid']->to_sql("cr.".$cc->uid);

        if (!$opt_get_disabled)           $qw[] = "(cr.".$cc->flags." & ".$fc['DB_DISABLED'].") = 0";

		if(!empty($filter['sipuri'])){
            $q_uri = "select ".$cu->uid." 
                      from ".$tu_name." u join ".$td_name." d
                            on u.".$cu->did." = d.".$cd->did."
                      where ".$filter['sipuri']->to_sql("concat('sip:', ".$cu->username.", '@', ".$cd->name.")");

            $qw[] = "(cr.".$cc->uid." IN (".$q_uri."))";
		}

		$query_c="";
		if ($qw) $query_c = implode(" and ", $qw)." and ";

		$q_online = "";
		if (!empty($filter['onlineonly']) and $filter['onlineonly']->value){
    		$q_online  = " join ".$tl_name." loc on (cr.".$cc->uid." = loc.".$cl->uid.") ";
		}

		$q_admins = "";
		if(!empty($filter['adminsonly']) and $filter['adminsonly']->value){
			$q_admins = " join ".$ta_name." adm 
			            on (cr.".$cc->uid." = adm.".$ca->uid." and 
						    adm.".$ca->name."='".$an['is_admin']."' and
							adm.".$ca->value."='1') ";
		}

		$q_agree = "";
		if($opt_agreeing){
			$q_agree = " join ".$ta_name." aag 
			            on (cr.".$cc->uid." = aag.".$ca->uid." and 
						    aag.".$ca->name."='".$an['allow_find']."' and
							aag.".$ca->value."='1') ";
		}

		$q_uri = "";
		if(!empty($filter['alias'])){
			$q_uri = " join ".$tu_name." uri 
			            on (cr.".$cc->uid." = uri.".$cu->uid." and ".
                            $filter['alias']->to_sql("uri.".$cu->username).") ";
		}

		$q_dom_filter = "";
		if(!empty($filter['domain'])){
			if ($config->auth['use_did']){
				$q_dom_filter = " join ".$td_name." dom 
				            on (cr.".$cc->did." = dom.".$cd->did." and ".
                                $filter['domain']->to_sql("dom.".$cd->name)." ) ";
			}
			else{
				$q_dom_filter = " join ".$tda_name." doa 
				            on (cr.".$cc->realm." = doa.".$cda->value." and 
				                doa.".$cda->name." = '".$an['digest_realm']."')
				            join ".$td_name." dom 
				            on (doa.".$cda->did." = dom.".$cd->did." and ".
                                $filter['domain']->to_sql("dom.".$cd->name)." ) ";
			}
		}

		$q_domains = "";
		if (!is_null($opt_from_domains)){
			
			$q_domains = " join ".$tu_name." uri 
			                 on (cr.".$cc->uid." = uri.".$cu->uid." and
			                    ".$this->get_sql_in("uri.".$cu->did, $opt_from_domains, true)." and 
								 uri.".$cu->flags." & ".$fu['DB_DELETED']." = 0) ";
		}

		$q_uid_filter = "";
		if (!is_null($opt_uid_filter)){
			$q_uid_filter = $this->get_sql_in("cr.".$cc->uid, $opt_uid_filter, true)." and ";
		}

		$q_tz_cols = $q_tz_from = "";
		if ($opt_get_timezones){
			$q_tz_from = " left outer join ".$ta_name." atz 
			            on (cr.".$cc->uid." = atz.".$ca->uid." and 
						    atz.".$ca->name."='".$an['timezone']."') ";
			$q_tz_cols = ", atz.".$ca->value." as timezone ";
		}

		if (!$opt_return_all){
			/* get num rows */		
			$q = "select cr.".$cc->uid." as uid
				  from ".$tc_name." cr ".$q_online.$q_admins.$q_dom_filter.$q_domains.$q_uri.$q_agree."
				        left outer join ".$ta_name." afn
				            on (cr.".$cc->uid." = afn.".$ca->uid." and afn.".$ca->name."='".$an['fname']."')
				        left outer join ".$ta_name." aln
				            on (cr.".$cc->uid." = aln.".$ca->uid." and aln.".$ca->name."='".$an['lname']."')
				        left outer join ".$ta_name." aph
				            on (cr.".$cc->uid." = aph.".$ca->uid." and aph.".$ca->name."='".$an['phone']."')
				        left outer join ".$ta_name." aem
				            on (cr.".$cc->uid." = aem.".$ca->uid." and aem.".$ca->name."='".$an['email']."')
				  where ".$query_c.$q_uid_filter." 
				       (cr.".$cc->flags." & ".$fc['DB_DELETED'].") = 0";
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
			$this->set_num_rows($res->numRows());
			$res->free();
	
			/* if act_row is bigger then num_rows, correct it */
			$this->correct_act_row();
		}


		$q = "select cr.".$cc->uid." as uid,
			         cr.".$cc->uname." as username,
		             cr.".$cc->did." as did,
			         cr.".$cc->realm." as realm,
					 afn.".$ca->value." as fname,
					 aln.".$ca->value." as lname,
					 aph.".$ca->value." as phone,
					 aem.".$ca->value." as email,
					 cr.".$cc->flags." & ".$fc['DB_DISABLED']." as disabled,
					 trim(concat(afn.".$ca->value.", ' ', aln.".$ca->value.")) as name
					 ".$q_tz_cols."
			  from ".$tc_name." cr ".$q_online.$q_admins.$q_dom_filter.$q_domains.$q_uri.$q_agree.$q_tz_from."
			        left outer join ".$ta_name." afn
			            on (cr.".$cc->uid." = afn.".$ca->uid." and afn.".$ca->name."='".$an['fname']."')
			        left outer join ".$ta_name." aln
			            on (cr.".$cc->uid." = aln.".$ca->uid." and aln.".$ca->name."='".$an['lname']."')
			        left outer join ".$ta_name." aph
			            on (cr.".$cc->uid." = aph.".$ca->uid." and aph.".$ca->name."='".$an['phone']."')
			        left outer join ".$ta_name." aem
			            on (cr.".$cc->uid." = aem.".$ca->uid." and aem.".$ca->name."='".$an['email']."')
			  where ".$query_c.$q_uid_filter." 
			       (cr.".$cc->flags." & ".$fc['DB_DELETED'].") = 0";

		if ($o_order_by) {
			$q .= " order by ".$o_order_by." ".$o_order_desc;
		}

		$q.=($opt_return_all ? "" : $this->get_sql_limit_phrase());


		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }


		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$i = $row['uid'];
			$out[$i]['uid']            = $row['uid'];
			$out[$i]['username']       = $row['username'];
			$out[$i]['realm']          = $row['realm'];
			$out[$i]['serweb_auth']    = &SerwebUser::instance($row['uid'],
		                                                       $row['username'],
		                                                       $config->auth['use_did'] ? $row['did'] : null,
		                                                       $row['realm']);
			$out[$i]['domain']         = $out[$i]['serweb_auth'] -> get_domainname();
			$out[$i]['name']           = $row['name'];
			$out[$i]['fname']          = $row['fname'];
			$out[$i]['lname']          = $row['lname'];
			$out[$i]['phone']          = $row['phone'];
			$out[$i]['email_address']  = $row['email'];
			$out[$i]['get_param']      = $out[$i]['serweb_auth']->to_get_param();
			$out[$i]['disabled']       = (bool)$row['disabled'];

			if ($opt_get_timezones){
				$out[$i]['timezone']   = $row['timezone'];
			}

			if ($opt_get_aliases or $opt_get_sip_uri){
				$out[$i]['aliases']='';
				$out[$i]['sip_uri']='';

				$uri_handler = &URIs::singleton($row['uid']);
				if (false === $uris = $uri_handler->get_URIs()) return false;

				if ($opt_get_aliases){
					$alias_arr=array();
					foreach($uris as $val) $alias_arr[] = $val->get_username();
				
					$out[$i]['aliases'] = implode(", ", $alias_arr);
					$out[$i]['uris'] = $uris;
				}

				if ($opt_get_sip_uri){
					if (false === $uri = $uri_handler->get_URI()) return false;
					if (!is_null($uri)){
						if (false === $out[$i]['sip_uri'] = $uri->to_string()) return false;
					}
				}
			}

			if ($opt_get_credentials){
				if (false === $credentials = $this->get_credentials($row['uid'], null)) return false;
				$out[$i]['credentials'] = array();
				
				foreach ($credentials as $k=>$v){
					if (false === $out[$i]['credentials'][] = $v->to_smarty()) return false;
				}
			}

		}
		$res->free();
	
		return $out;
	}
}
?>
