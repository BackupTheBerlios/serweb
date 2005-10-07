<?
/*
 * $Id: method.get_users.php,v 1.4 2005/10/07 14:01:14 kozlik Exp $
 */

/*
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
 *    get_param_d
 *    aliases
 *
 *  Possible options parameters:
 *
 *    only_domain	  		(string) default: null
 *      if is set, only subscribers from selected domain alias are returned
 *  
 *    from_domains			(array) default:null
 *  	array of domain IDs from which are returned subscribers. By default are 
 *		returned all subscribers. Multidomain support must be enabled.
 *  
 *    get_user_aliases	  	(bool) default: true
 *      should returned aliases of users?
 *      Could be disabled from performance reasons.
 */ 

class CData_Layer_get_users {
	var $required_methods = array('get_aliases');
	
	function get_users($filter, $opt, &$errors){
		global $config, $sess;

		if (!$this->connect_to_db($errors)) return false;

		$cd = &$config->data_sql->domain;
		$cp = &$config->data_sql->dom_pref;
	
	    $opt_only_domain = (isset($opt['only_domain'])) ? $opt['only_domain'] : null;
	    $opt_from_domains = (isset($opt['from_domains'])) ? $opt['from_domains'] : null;
	    $opt_get_aliases = (isset($opt['get_user_aliases'])) ? (bool)$opt['get_user_aliases'] : true;


		if($filter['adminsonly']){
			$q_admins_join = " left join ".$config->data_sql->table_admin_privileges." p on ".
					(($config->users_indexed_by=='uuid')?
						"(s.uuid=p.uuid and p.priv_name='is_admin') ":
						"(s.username=p.username and s.domain=p.domain and p.priv_name='is_admin') ");
			$q_admins_where = " p.priv_value='1' and ";
		}
		else{
			$q_admins_join = "";
			$q_admins_where = "";
		}

		if ($filter['onlineonly']){
			$q_online_from = " ".$config->data_sql->table_location." l, ";
			$q_online_where = ($config->users_indexed_by=='uuid')?
								" s.uuid=l.uuid and ":
								" s.username=l.username and s.domain=l.domain and ";
		}
		else{
			$q_online_from = "";
			$q_online_where = "";
		}

		$q_domains_from = $q_domains_where = $q_domains_cols = "";
		if ($config->multidomain){
			$q_domains_cols = 
				" dpd.".$cp->att_value." as domain_disabled ";
			$q_domains_from = 
				" left outer join ".$config->data_sql->table_domain." d on s.domain = d.".$cd->name."
			      left outer join ".$config->data_sql->table_dom_preferences." dpd 
			        on (d.".$cd->id." = dpd.".$cp->id." and dpd.".$cp->att_name." = 'disabled')
			      left outer join ".$config->data_sql->table_dom_preferences." dpx 
			        on (d.".$cd->id." = dpx.".$cp->id." and dpx.".$cp->att_name." = 'deleted')";
			$q_domains_where = /* skip subscribers from deleted domains */
				" not (COALESCE(dpx.".$cp->att_value.", 0) > 0) and ";    

			if (is_array($opt_from_domains)){
				/* create a list of domains from which subscribers should be */
				$q_dom_tmp = "";
				foreach($opt_from_domains as $v) $q_dom_tmp .= " d.".$cd->id." = '".$v."' or ";
				$q_domains_where .= " (".$q_dom_tmp." 0) and ";
				unset($q_dom_tmp);
			}
		}


		$query_c="";
		if ($filter['usrnm'])  $query_c .= "s.username like '%".$filter['usrnm']."%' and ";
		if ($filter['fname'])  $query_c .= "s.first_name like '%".$filter['fname']."%' and ";
		if ($filter['lname'])  $query_c .= "s.last_name like '%".$filter['lname']."%' and ";
		if ($filter['email'])  $query_c .= "s.email_address like '%".$filter['email']."%' and ";
	
		if ($opt_only_domain){
			$query_c .= "s.domain = '".$opt_only_domain."' and ";
		}
		else if ($filter['domain']) 
			$query_c .= "s.domain like '%".$filter['domain']."%' and ";
		$query_c.="true ";



		/* get num rows */		
		$q="select s.username ".
			" from ".$q_online_from.$config->data_sql->table_subscriber." s ".
				$q_admins_join.$q_domains_from.
			" where ".$q_domains_where.$q_admins_where.$q_online_where.$query_c;


		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$this->set_num_rows($res->numRows());
		$res->free();
	
		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();

		if ($config->users_indexed_by=='uuid') $attribute='s.uuid';
		else $attribute='s.phplib_id';
		
		/* get users */
		$q="select s.username, s.domain, s.first_name, s.last_name, s.phone, s.email_address, ".$attribute." as uuid, ".$q_domains_cols.
			" from ".$q_online_from.$config->data_sql->table_subscriber." s ".
				$q_admins_join.$q_domains_from.
			" where ".$q_domains_where.$q_admins_where.$q_online_where.$query_c.
			" order by s.username ".$this->get_sql_limit_phrase();

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
			
	
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_OBJECT); $i++){
			$out[$i]['username']       = $row->username;
			$out[$i]['domain']         = $row->domain;
			$out[$i]['name']           = implode(' ', array($row->last_name, $row->first_name));
			$out[$i]['fname']          = $row->first_name;
			$out[$i]['lname']          = $row->last_name;
			$out[$i]['phone']          = $row->phone;
			$out[$i]['email_address']  = $row->email_address;
			$out[$i]['get_param']      = user_to_get_param($row->uuid, $row->username, $row->domain, 'u');
			$out[$i]['get_param_d']    = user_to_get_param($row->uuid, $row->username, $row->domain, 'd');

			if ($opt_get_aliases){
				$out[$i]['aliases']='';
				if (false === ($aliases = $this->get_aliases(new Cserweb_auth($row->uuid, $row->username, $row->domain), $errors))) continue;

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
