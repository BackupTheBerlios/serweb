<?
/*
 * $Id: method.get_acc_entries.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_acc_entries {
	var $required_methods = array('get_status', 'get_user_name_from_phonebook');
	
	
	/* prepare parts of SQL queries */
	function acc_prepare_SQL(){
		global $config;
			/*
				select calls from accounting table
				first SELECT selects pairs INVITE,BYE and unpaired INVITE records
				second SELECT selects unpaired BYE records
			*/

			$this->acc_sql['select_out'] = 
				"select t1.to_uri as inv_to_uri, 
						t1.sip_to as inv_sip_to, 
						t1.sip_callid as inv_callid, 
						t1.time as inv_time, 
						t1.fromtag as inv_fromtag,
						t1.sip_status as inv_status,
						t2.to_uri as bye_to_uri, 
						t2.sip_to as bye_sip_to, 
						t2.sip_callid as bye_callid, 
						t2.time as bye_time, 
						t2.fromtag as bye_fromtag, 
						t2.totag as bye_totag,
						t2.from_uri as bye_from_uri, 
						t2.sip_from as bye_sip_from,
			 			sec_to_time(unix_timestamp(t2.time)-unix_timestamp(t1.time)) as length, 
						ifnull(t1.time, t2.time) as ttime ";

			$this->acc_sql['select_in'] = 
				"select t1.from_uri as inv_to_uri, 
						t1.sip_from as inv_sip_to, 
						t1.sip_callid as inv_callid, 
						t1.time as inv_time, 
						t1.fromtag as inv_fromtag,
						t1.sip_status as inv_status,
						t2.from_uri as bye_to_uri, 
						t2.sip_from as bye_sip_to, 
						t2.sip_callid as bye_callid, 
						t2.time as bye_time, 
						t2.fromtag as bye_fromtag, 
						t2.totag as bye_totag,
						t2.from_uri as bye_from_uri, 
						t2.sip_from as bye_sip_from,
			 			sec_to_time(unix_timestamp(t2.time)-unix_timestamp(t1.time)) as length, 
						ifnull(t1.time, t2.time) as ttime ";

			$this->acc_sql['select_missed'] = 
				"select t1.from_uri as inv_to_uri, 
						t1.sip_from as inv_sip_to, 
						t1.sip_callid as inv_callid, 
						t1.time as inv_time, 
						t1.fromtag as inv_fromtag,
						t1.sip_status as inv_status,
						null as bye_to_uri, 
						null as bye_sip_to, 
						null as bye_callid, 
						null as bye_time, 
						null as bye_fromtag, 
						null as bye_totag,
						null as bye_from_uri, 
						null as bye_sip_from,
			 			null as length, 
						t1.time as ttime ";
						
			$this->acc_sql['from_1'] = 
				"from ".$config->data_sql->table_accounting." t1 left outer join ".$config->data_sql->table_accounting." t2 on
							t1.sip_callid=t2.sip_callid and
							((t1.totag=t2.totag and t1.fromtag=t2.fromtag) or
							 (t1.totag=t2.fromtag and t1.fromtag=t2.totag)) and
							t2.sip_method='BYE' ";

			$this->acc_sql['from_2'] = 
				"from ".$config->data_sql->table_accounting." t1 right outer join ".$config->data_sql->table_accounting." t2 on
							t1.sip_callid=t2.sip_callid and
							((t1.totag=t2.totag and t1.fromtag=t2.fromtag) or
							 (t1.totag=t2.fromtag and t1.fromtag=t2.totag)) and
							t1.sip_method='INVITE' ";

			$this->acc_sql['where_1'] = 
				"where t1.sip_method='INVITE' ";

			$this->acc_sql['where_2'] = 
				"where t2.sip_method='BYE' and isnull(t1.username) ";

			$this->acc_sql['order'] = 
				"order by ttime desc ";

//where clauses before UUIDzation:
//first select			where t1.username='".$user."' and t1.domain='".$domain."' and t1.sip_method='INVITE' )
//second select			where t2.username='".$user."' and t2.domain='".$domain."' and t2.sip_method='BYE' and isnull(t1.username) )
							
	}

	/* return SQL query for get outgoing calls */
	function acc_get_SQL_select_outgoing($user){
		global $config;

		if ($config->users_indexed_by=='uuid') // in UUIDzed version we not able to get unpaired BYE records
			$q = "(".$this->acc_sql['select_out'].", 'outgoing' as call_type ".
			         $this->acc_sql['from_1'].
			         $this->acc_sql['where_1']." and ".$this->get_indexing_sql_where_phrase($user, 't1.caller_UUID', 't1.username', 't1.domain').
				 ")";
		else
			$q = "(".$this->acc_sql['select_out'].", 'outgoing' as call_type ".
			         $this->acc_sql['from_1'].
			         $this->acc_sql['where_1']." and ".$this->get_indexing_sql_where_phrase($user, 't1.caller_UUID', 't1.username', 't1.domain').
				 ") union (".
				     $this->acc_sql['select_out'].", 'outgoing' as call_type ".
			         $this->acc_sql['from_2'].
			         $this->acc_sql['where_2']." and ".$this->get_indexing_sql_where_phrase($user, 't2.callee_UUID', 't2.username', 't2.domain').
				 ")";
		return $q;
	
	}

	/* return SQL query for get number of outgoing calls */
	function acc_get_SQL_select_outgoing_count($user){
		global $config;

		$q=array();
		if ($config->users_indexed_by=='uuid'){
			$q[] = "select count(*) ".
					$this->acc_sql['from_1'].
					$this->acc_sql['where_1']." and ".$this->get_indexing_sql_where_phrase($user, 't1.caller_UUID', 't1.username', 't1.domain');
		}
		else{
			$q[] = "select count(*) ".
					$this->acc_sql['from_1'].
					$this->acc_sql['where_1']." and ".$this->get_indexing_sql_where_phrase($user, 't1.caller_UUID', 't1.username', 't1.domain');
			$q[] = "select count(*) ".
					$this->acc_sql['from_2'].
					$this->acc_sql['where_2']." and ".$this->get_indexing_sql_where_phrase($user, 't2.callee_UUID', 't2.username', 't2.domain');
		}
		return $q;	
	}

	/* return SQL query for get incoming calls */
	function acc_get_SQL_select_incoming($user){
		global $config;

		if ($config->users_indexed_by=='uuid')
			$q = "(".$this->acc_sql['select_in'].", 'incoming' as call_type ".
			         $this->acc_sql['from_1'].
			         $this->acc_sql['where_1']." and t1.callee_UUID = '".$user->uuid."'".
				 ")";
		else
			$q = ""; 
			/*
			 * I can't implement this, because acc table contains fields username/domain only for caller, not for callee!
			 */
			die('SORRY! displaying incoming calls ($config->acc_display_incoming_calls) is avaible only if users are indexed by uuid ($config->users_indexed_by)');
		return $q;
	
	}

	/* return SQL query for get number of incoming calls */
	function acc_get_SQL_select_incoming_count($user){
		global $config;

		$q=array();
		if ($config->users_indexed_by=='uuid'){
			$q[] = "select count(*) ".
					$this->acc_sql['from_1'].
					$this->acc_sql['where_1']." and t1.callee_UUID = '".$user->uuid."'";
		}
		else{
		}
		return $q;	
	}

	
	/* return SQL query for get missed calls */
	function acc_get_SQL_select_missed($user){
		global $config;

		if ($config->users_indexed_by=='uuid'){
			$q="(".$this->acc_sql['select_missed'].", 'missed' as call_type ".
				    "FROM ".$config->data_sql->table_missed_calls." t1 ".
				    "WHERE t1.callee_UUID='".$user->uuid."' )";
		}
		else{
			$q="(".$this->acc_sql['select_missed'].", 'missed' as call_type ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
					"WHERE t1.username='".$user->uname."' and t1.domain='".$user->domain."' ) ".
				"UNION ".
				"(".$this->acc_sql['select_missed'].", 'missed' as call_type ".
					"FROM ".$config->data_sql->table_missed_calls." t1, ".$config->data_sql->table_aliases." t2 ".
					"WHERE 'sip:".$user->uname."@".$user->domain."'".
						"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain ) ";
		}
		return $q;
	
	}

	/* return SQL query for get number of missed calls */
	function acc_get_SQL_select_missed_count($user){
		global $config;

		$q=array();
		if ($config->users_indexed_by=='uuid'){
			$q[]="SELECT count(*) ".
				"FROM ".$config->data_sql->table_missed_calls." t1 ".
                "WHERE t1.callee_UUID='".$user->uuid."'";
		}
		else{
			$q[]="SELECT count(*)  ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
					"WHERE t1.username='".$user->uname."' and t1.domain='".$user->domain."'";
			$q[]="SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
					"FROM ".$config->data_sql->table_missed_calls." t1, ".$config->data_sql->table_aliases." t2 ".
					"WHERE 'sip:".$user->uname."@".$user->domain."'=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain";
		}
		return $q;	
	}
	
	 /*
	  * get calls from accounting
	  */

	function get_acc_entries($user, &$errors){
		global $config, $serweb_auth, $sip_status_messages_array;

		if (!$this->connect_to_db($errors)) return false;

		$this->acc_prepare_SQL();
		
		/* get num rows */
		$num_rows=0;

		//compose get number of calls queries
		$q = array();
		if ($config->acc_display_outgoing_calls) $q=array_merge($q, $this->acc_get_SQL_select_outgoing_count($user));
		if ($config->acc_display_incoming_calls) $q=array_merge($q, $this->acc_get_SQL_select_incoming_count($user));
		if ($config->acc_display_missed_calls)   $q=array_merge($q, $this->acc_get_SQL_select_missed_count($user));

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
		if ($config->acc_display_outgoing_calls) $q[] = $this->acc_get_SQL_select_outgoing($user);
		if ($config->acc_display_incoming_calls) $q[] = $this->acc_get_SQL_select_incoming($user);
		if ($config->acc_display_missed_calls)   $q[] = $this->acc_get_SQL_select_missed($user);

		$q = implode(' union ', $q);
		$q .= $this->acc_sql['order'].
				"limit ".$this->get_act_row().", ".$this->get_showed_rows();
		
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

			if ($timestamp <=0 ) $o['time'] = "";
			else {
				if (date('Y-m-d',$timestamp)==date('Y-m-d')) $o['time'] = "today ".date('H:i',$timestamp);
				else $o['time'] = date('Y-m-d H:i',$timestamp);
			}

			if (!$row->bye_callid){ //unpaired record, only INVITE
				$o['sip_callid']=$row->inv_callid;
				$o['to_uri']=$row->inv_to_uri;
				$o['sip_to']=$row->inv_sip_to;

				$o['length']="n/a";
				$o['hangup']="n/a";
			}
			elseif (!$row->inv_callid){ //unpaired record, only BYE
				$o['sip_callid']=$row->bye_callid;
				$o['to_uri']=$row->bye_from_uri;
				$o['sip_to']=$row->bye_sip_from;

				$o['length']="n/a";
				$o['hangup']="n/a";
			}
			else{
				$o['sip_callid']=$row->inv_callid;
				$o['to_uri']=$row->inv_to_uri;
				$o['sip_to']=$row->inv_sip_to;

				if ($row->inv_fromtag==$row->bye_fromtag) $o['hangup']="caller";
				else if ($row->inv_fromtag==$row->bye_totag) $o['hangup']="callee";
				else $o['hangup']="n/a";

				$o['length']=$row->length;
			}
			$o['url_ctd'] = "javascript: open_ctd_win2('".rawURLEncode($o['to_uri'])."', '".RawURLEncode("sip:".$serweb_auth->uname."@".$serweb_auth->domain)."');";
			$o['sip_to']  = htmlspecialchars(ereg_replace("(.*)(;tag=.*)","\\1",$o['sip_to']));
			$o['status']  = $this->get_status($o['to_uri'], $errors);
			$o['name']    = $this->get_user_name_from_phonebook($user, $o['to_uri'], $errors);
			$o['call_dir']= $row->call_type=='outgoing'?'outgoing':'incoming';

			$o['call_type']= $row->call_type;
			$o['sip_status_code'] = $row->inv_status;
			$o['sip_status'] = isset($sip_status_messages_array[$row->inv_status]) ?
									$sip_status_messages_array[$row->inv_status] :
									$row->inv_status;
			$out[]=$o;

		} //while
		$res->free();

		return $out;
	}
}
?>
