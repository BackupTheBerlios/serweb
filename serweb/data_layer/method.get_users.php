<?php
/*
 * $Id: method.get_users.php,v 1.6 2005/12/22 12:45:00 kozlik Exp $
 */

class CData_Layer_get_users {
	var $required_methods = array('get_aliases');
	

	/**
	 *  Function return array of associtive arrays containig subscribers
	 *
	 *  Keys of associative arrays:
	 *    username
	 *    domain
	 *	  name
	 *    fname
	 *    lname
	 *    phone
	 *    email_address
	 *    get_param
	 *    aliases
	 *	  disabled
	 *
	 *  Possible options parameters:
	 *
	 *    from_domains			(array) default:null
	 *  	array of domain IDs from which are returned subscribers. By default are 
	 *		returned all subscribers. 
	 *  
	 *    get_user_aliases	  	(bool) default: true
	 *      should returned aliases of users?
	 *      Could be disabled from performance reasons.
	 *  
	 *	  only_users			(array)	default:null
	 *		Array of user IDs. if is set, only users from this array are returned
	 *
	 *	  return_all			(bool)	default: false
 	 *		if true, the result isn't limited by LIMIT sql phrase
 	 *
	 *  
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
		/* col names */
		$ca = &$config->data_sql->user_attrs->cols;
		$cc = &$config->data_sql->credentials->cols;
		$cu = &$config->data_sql->uri->cols;
		$cl = &$config->data_sql->location->cols;
		/* flags */
		$fa = &$config->data_sql->user_attrs->flag_values;
		$fc = &$config->data_sql->credentials->flag_values;
		$fu = &$config->data_sql->uri->flag_values;

		$an = &$config->attr_names;

	    $opt_from_domains = (isset($opt['from_domains'])) ? $opt['from_domains'] : null;
	    $opt_get_aliases = (isset($opt['get_user_aliases'])) ? (bool)$opt['get_user_aliases'] : true;
	    $opt_uid_filter =  (isset($opt['only_users'])) ? $opt['only_users'] : null;
	    $opt_return_all = (isset($opt['return_all'])) ? (bool)$opt['return_all'] : false;



		/* get users */

		$query_c="";
		if (!empty($filter['usrnm']))  $query_c .= "cr.".$cc->uname." like '%".$filter['usrnm']."%' and ";
		if (!empty($filter['realm']))  $query_c .= "cr.".$cc->realm." like '%".$filter['realm']."%' and ";
		if (!empty($filter['fname']))  $query_c .= "afn.".$ca->value." like '%".$filter['fname']."%' and ";
		if (!empty($filter['lname']))  $query_c .= "aln.".$ca->value." like '%".$filter['lname']."%' and ";
		if (!empty($filter['email']))  $query_c .= "aem.".$ca->value." like '%".$filter['email']."%' and ";

		$q_online = "";
		if (!empty($filter['onlineonly'])){
			$q_online  = " join ".$tl_name." loc on (cr.".$cc->uid." = loc.".$cl->uid.") ";
		}

		$q_admins = "";
		if(!empty($filter['adminsonly'])){
			$q_admins = " join ".$ta_name." adm 
			            on (cr.".$cc->uid." = adm.".$ca->uid." and 
						    adm.".$ca->name."='".$an['is_admin']."' and
							adm.".$ca->value."='1') ";
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


		if (!$opt_return_all){
			/* get num rows */		
			$q = "select cr.".$cc->uid." as uid
				  from ".$tc_name." cr ".$q_online.$q_admins.$q_domains."
				        left outer join ".$ta_name." afn
				            on (cr.".$cc->uid." = afn.".$ca->uid." and afn.".$ca->name."='".$an['fname']."')
				        left outer join ".$ta_name." aln
				            on (cr.".$cc->uid." = aln.".$ca->uid." and aln.".$ca->name."='".$an['lname']."')
				        left outer join ".$ta_name." aph
				            on (cr.".$cc->uid." = aph.".$ca->uid." and aph.".$ca->name."='".$an['phone']."')
				        left outer join ".$ta_name." aem
				            on (cr.".$cc->uid." = aem.".$ca->uid." and aem.".$ca->name."='".$an['email']."')
				  where ".$query_c.$q_uid_filter." 
				       (cr.".$cc->flags." & ".$fc['DB_DELETED'].") = 0
				  group by cr.".$cc->uid;
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			$this->set_num_rows($res->numRows());
			$res->free();
	
			/* if act_row is bigger then num_rows, correct it */
			$this->correct_act_row();
		}



		$q = "select cr.".$cc->uid." as uid,
			         cr.".$cc->uname." as username,
			         cr.".$cc->realm." as realm,
					 afn.".$ca->value." as fname,
					 aln.".$ca->value." as lname,
					 aph.".$ca->value." as phone,
					 aem.".$ca->value." as email,
					 cr.".$cc->flags." & ".$fc['DB_DISABLED']." as disabled
			  from ".$tc_name." cr ".$q_online.$q_admins.$q_domains."
			        left outer join ".$ta_name." afn
			            on (cr.".$cc->uid." = afn.".$ca->uid." and afn.".$ca->name."='".$an['fname']."')
			        left outer join ".$ta_name." aln
			            on (cr.".$cc->uid." = aln.".$ca->uid." and aln.".$ca->name."='".$an['lname']."')
			        left outer join ".$ta_name." aph
			            on (cr.".$cc->uid." = aph.".$ca->uid." and aph.".$ca->name."='".$an['phone']."')
			        left outer join ".$ta_name." aem
			            on (cr.".$cc->uid." = aem.".$ca->uid." and aem.".$ca->name."='".$an['email']."')
			  where ".$query_c.$q_uid_filter." 
			       (cr.".$cc->flags." & ".$fc['DB_DELETED'].") = 0
			  group by cr.".$cc->uid;

		$q.=($opt_return_all ? "" : $this->get_sql_limit_phrase());


		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }


		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$i = $row['uid'];
			$out[$i]['uid']            = $row['uid'];
			$out[$i]['username']       = $row['username'];
			$out[$i]['domain']         = $row['realm'];
			$out[$i]['serweb_auth']    = new Cserweb_auth($row['uid'], $row['username'], $row['realm']);
			$out[$i]['name']           = implode(' ', array($row['lname'], $row['fname']));
			$out[$i]['fname']          = $row['fname'];
			$out[$i]['lname']          = $row['lname'];
			$out[$i]['phone']          = $row['phone'];
			$out[$i]['email_address']  = $row['email'];
			$out[$i]['get_param']      = user_to_get_param($row['uid'], $row['username'], $row['realm'], 'u');
			$out[$i]['disabled']       = (bool)$row['disabled'];

			if ($opt_get_aliases){
				$out[$i]['aliases']='';
				if (false === ($aliases = $this->get_aliases(new Cserweb_auth($row['uid'], null, null), $errors))) return false;

				$alias_arr=array();
				foreach($aliases as $val) $alias_arr[] = $val->username;
			
				$out[$i]['aliases'] = implode(", ", $alias_arr);
			}

		}
		$res->free();
	
		return $out;
	}
}
?>