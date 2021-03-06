<?
/*
 * $Id: method.get_phonebook_entries.php,v 1.8 2007/02/06 10:36:10 kozlik Exp $
 */

/*
 *  Function return array of associtive arrays containig phonebook of $user
 *
 *  Keys of associative arrays:
 *    l_name
 *    f_name
 *    name
 *    status
 *    sip_uri
 *    url_ctd
 *    url_dele
 *    url_edit
 *    aliases
 *
 *  Possible options parameters:
 *
 *    pbid	(string)	default: null
 *      
 *    get_user_status	  	(bool) default: true
 *      should returned status of users?
 *      Could be disabled from performance reasons.
 *
 *    get_user_aliases	  		(bool) default: true
 *      should returned aliases of users?
 *      Could be disabled from performance reasons.
 *  
 */ 

class CData_Layer_get_phonebook_entries {
	var $required_methods = array('get_aliases_by_uri', 'get_status');
	
	function get_phonebook_entries($user, $opt, &$errors){
		global $config, $sess;

		if (!$this->connect_to_db($errors)) return false;

		/* backward compatibility */
		if (!is_array($opt)){
			 $pbid = $opt;
			 $opt_get_user_status = true;
			 $opt_get_aliases = true;
			 
			 $o_order_by = "";
			 $o_order_desc = "";
		}
		else{
		    $pbid = (isset($opt['pbid'])) ? $opt['pbid'] : null;
		    $opt_get_user_status = (isset($opt['get_user_status'])) ? (bool)$opt['get_user_status'] : true;
		    $opt_get_aliases = (isset($opt['get_user_aliases'])) ? (bool)$opt['get_user_aliases'] : true;
					
		    $o_order_by = (isset($opt['order_by'])) ? $opt['order_by'] : "";
		    $o_order_desc = (!empty($opt['order_desc'])) ? "desc" : "";
		}
		
		if (!is_null($pbid)) $qw=" and id!=".$this->sql_format($pbid, "n")." "; 
		else $qw="";

		/* get num rows */		
		$q="select count(*) from ".$config->data_sql->table_phonebook.
			" where ".$this->get_indexing_sql_where_phrase($user).$qw;
	
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$this->set_num_rows($row[0]);
		$res->free();

		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();
	
		$q="select id, fname, lname, sip_uri,
		           trim(concat(lname, ' ', fname)) as name
		    from ".$config->data_sql->table_phonebook." 
			where ".$this->get_indexing_sql_where_phrase($user).$qw;

		if ($o_order_by) {
			$q .= " order by ".$o_order_by." ".$o_order_desc;
		}

		$q .= $this->get_sql_limit_phrase();

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
			
			$out[$row->id]['id'] = $row->id;
			$out[$row->id]['l_name'] = $row->lname;
			$out[$row->id]['f_name'] = $row->fname;
			$out[$row->id]['name']   = $row->name;
			$out[$row->id]['sip_uri'] = $row->sip_uri;
			$out[$row->id]['url_ctd'] = "javascript: open_ctd_win('".rawURLEncode($row->sip_uri)."');";
			$out[$row->id]['url_dele'] = $sess->url("phonebook.php?kvrk=".uniqID("")."&dele_id=".$row->id);
			$out[$row->id]['url_edit'] = $sess->url("phonebook.php?kvrk=".uniqID("")."&edit_id=".$row->id);

			if ($opt_get_user_status)
				$out[$row->id]['status'] = $this->get_status($row->sip_uri, null);

			if ($opt_get_aliases){
				$out[$row->id]['aliases']='';
				if (false === ($aliases = $this->get_aliases_by_uri($row->sip_uri, null))) continue;

				$alias_arr=array();
				foreach($aliases as $val) $alias_arr[] = $val->username;
				$out[$row->id]['aliases'] = implode(", ", $alias_arr);
				$out[$row->id]['uris'] = $aliases;
			}
		}
		$res->free();

		return $out;			
	}
	
}
?>
