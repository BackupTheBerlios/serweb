<?
/*
 * $Id: method.get_cdr_entries.php,v 1.5 2006/09/08 12:27:33 kozlik Exp $
 */

class CData_Layer_get_cdr_entries {
	var $required_methods = array('get_status', 'get_user_name_from_phonebook');
	
	
	/* prepare parts of SQL queries */
	function cdr_prepare_SQL(){
		global $config;
			/*
				select calls from cdr table
			*/

			$this->cdr_sql['select_out'] = 
				"select to_uri, 
						sip_to, 
						sip_callid, 
						sip_status,
						stop,
						start as ttime ";

			$this->cdr_sql['select_in'] = 
				"select from_uri as to_uri, 
						sip_from as sip_to, 
						sip_callid, 
						sip_status,
						stop,
						start as ttime ";
						
			$this->cdr_sql['from'] = 
				"from ".$config->data_sql->table_cdr." ";

			$this->cdr_sql['order'] = 
				"order by ttime desc ";

	}

	/* return SQL query for get outgoing calls */
	function cdr_get_SQL_select_outgoing($user){
		global $config;

		$q = "(".$this->cdr_sql['select_out'].", 'outgoing' as call_type ".
		         $this->cdr_sql['from'].
		         " where ".$this->get_indexing_sql_where_phrase($user, 'caller_UUID', 'username', 'domain').
			 ")";

		return $q;
	
	}

	/* return SQL query for get number of outgoing calls */
	function cdr_get_SQL_select_outgoing_count($user){
		global $config;

		$q=array();
		$q[] = "select count(*) ".
				$this->cdr_sql['from'].
				" where ".$this->get_indexing_sql_where_phrase($user, 'caller_UUID', 'username', 'domain');
		return $q;	
	}

	/* return SQL query for get incoming calls */
	function cdr_get_SQL_select_incoming($user){
		global $config;

		if ($config->users_indexed_by=='uuid'){
			$q = "(".$this->cdr_sql['select_in'].", 'incoming' as call_type ".
			         $this->cdr_sql['from'].
			         " where callee_UUID = '".$user->get_uid()."'".
				 ")";
		} 
		else{
			$q = ""; 
			/*
			 * I can't implement this, because cdr table contains fields username/domain only for caller, not for callee!
			 */
			die('SORRY! displaying incoming calls (option: opt_filter_incoming or display_incoming) is avaible only if users are indexed by uuid ($config->users_indexed_by)');
		}
		return $q;
	
	}

	/* return SQL query for get number of incoming calls */
	function cdr_get_SQL_select_incoming_count($user){
		global $config;

		$q=array();
		if ($config->users_indexed_by=='uuid'){
			$q[] = "select count(*) ".
					$this->cdr_sql['from'].
					" where callee_UUID = '".$user->get_uid()."'";
		}
		else{
		}
		return $q;	
	}

	
	
	 /*
	  * get calls from accounting
	  * 
	  * param $opt is reserved for further use
	  */

	function get_cdr_entries($user, $opt, &$errors){
		global $config, $sip_status_messages_array;

		if (!$this->connect_to_db($errors)) return false;

	    $opt_filter_outgoing = (isset($opt['filter_outgoing'])) ? (bool)$opt['filter_outgoing'] : true;
	    $opt_filter_incoming = (isset($opt['filter_incoming'])) ? (bool)$opt['filter_incoming'] : true;

		$this->cdr_prepare_SQL();
		
		/* get num rows */
		$num_rows=0;

		//compose get number of calls queries
		$q = array();
		if ($opt_filter_outgoing) $q=array_merge($q, $this->cdr_get_SQL_select_outgoing_count($user));
		if ($opt_filter_incoming) $q=array_merge($q, $this->cdr_get_SQL_select_incoming_count($user));

		//query DB
		foreach($q as $row){
			$res=$this->db->query($row);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
			$res->free();
			$num_rows+=$row[0];
		}
		$this->set_num_rows($num_rows);
		
		/* if act_row is bigger then num_rows, correct it */
		$this->correct_act_row();


		//compose final query
		$q = array();
		if ($opt_filter_outgoing) $q[] = $this->cdr_get_SQL_select_outgoing($user);
		if ($opt_filter_incoming) $q[] = $this->cdr_get_SQL_select_incoming($user);

		$q = implode(' union ', $q);
		$q .= $this->cdr_sql['order'].
				$this->get_sql_limit_phrase();
		
		//query DB
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		//prepare output
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
			$o=array();

			$timestamp=gmmktime(substr($row->ttime,11,2), 	//hour
								substr($row->ttime,14,2), 	//minute
								substr($row->ttime,17,2), 	//second
								substr($row->ttime,5,2), 	//month
								substr($row->ttime,8,2), 	//day
								substr($row->ttime,0,4));	//year

			$ts_stop = gmmktime(substr($row->stop,11,2), 	//hour
								substr($row->stop,14,2), 	//minute
								substr($row->stop,17,2), 	//second
								substr($row->stop,5,2), 	//month
								substr($row->stop,8,2), 	//day
								substr($row->stop,0,4));	//year

			if ($timestamp <=0 ) $o['time'] = "";
			else {
				if (date('Y-m-d',$timestamp)==date('Y-m-d')) $o['time'] = "today ".date('H:i',$timestamp);
				else $o['time'] = date('Y-m-d H:i',$timestamp);
			}

			$o['sip_callid']=$row->sip_callid;
			$o['to_uri']=$row->to_uri;
			$o['sip_to']=$row->sip_to;

/*			if ($row->inv_fromtag==$row->bye_fromtag) $o['hangup']="caller";
			else if ($row->inv_fromtag==$row->bye_totag) $o['hangup']="callee";
			else $o['hangup']="n/a"; */
			$o['hangup']="n/a";

			$o['length'] = date ('H:i:s', $ts_stop - $timestamp);

			$o['url_ctd'] = "javascript: open_ctd_win('".rawURLEncode($o['to_uri'])."');";
			$o['sip_to']  = htmlspecialchars(ereg_replace("(.*)(;tag=.*)","\\1",$o['sip_to']));
			$o['status']  = $this->get_status($o['to_uri'], null);
			$o['name']    = $this->get_user_name_from_phonebook($user, $o['to_uri'], $errors);
			$o['call_dir']= $row->call_type=='outgoing'?'outgoing':'incoming';

			$o['call_type']= $row->call_type;
			$o['sip_status_code'] = $row->sip_status;
			$o['sip_status'] = isset($sip_status_messages_array[$row->sip_status]) ?
									$sip_status_messages_array[$row->sip_status] :
									$row->sip_status;
			$out[]=$o;

		} //while
		$res->free();

		return $out;
	}
}
?>
