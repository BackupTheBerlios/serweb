<?
/*
 * $Id: accounting.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class CData_Layer extends CDL_common{

	 /*
	  * get calls from accounting
	  */

	function select_calls_from_acc($user, $domain, &$errors){
		global $config;
	
		switch($this->container_type){
		case 'sql':
			/*
				select calls from accounting table
				first SELECT selects pairs INVITE,BYE and unpaired INVITE records
				second SELECT selects unpaired BYE records
			*/
		
			$q="(select t1.to_uri as inv_to_uri, t1.sip_to as inv_sip_to, t1.sip_callid as inv_callid, t1.time as inv_time, t1.fromtag as inv_fromtag,
					t2.to_uri as bye_to_uri, t2.sip_to as bye_sip_to, t2.sip_callid as bye_callid, t2.time as bye_time, t2.fromtag as bye_fromtag, t2.totag as bye_totag,
					t2.from_uri as bye_from_uri, t2.sip_from as bye_sip_from,
		 			sec_to_time(unix_timestamp(t2.time)-unix_timestamp(t1.time)) as length, ifnull(t1.time, t2.time) as ttime
			 from ".$config->data_sql->table_accounting." t1 left outer join ".$config->data_sql->table_accounting." t2 on
					t1.sip_callid=t2.sip_callid and
					((t1.totag=t2.totag and t1.fromtag=t2.fromtag) or
					 (t1.totag=t2.fromtag and t1.fromtag=t2.totag)) and
					t2.sip_method='BYE'
			 where t1.username='".$user."' and t1.domain='".$domain."' and t1.sip_method='INVITE' )
		
			union
		
			(select t1.to_uri as inv_to_uri, t1.sip_to as inv_sip_to, t1.sip_callid as inv_callid, t1.time as inv_time, t1.fromtag as inv_fromtag,
					t2.to_uri as bye_to_uri, t2.sip_to as bye_sip_to, t2.sip_callid as bye_callid, t2.time as bye_time, t2.fromtag as bye_fromtag, t2.totag as bye_totag,
					t2.from_uri as bye_from_uri, t2.sip_from as bye_sip_from,
		 			sec_to_time(unix_timestamp(t2.time)-unix_timestamp(t1.time)) as length, ifnull(t1.time, t2.time) as ttime
			from ".$config->data_sql->table_accounting." t1 right outer join ".$config->data_sql->table_accounting." t2 on
					t1.sip_callid=t2.sip_callid and
					((t1.totag=t2.totag and t1.fromtag=t2.fromtag) or
					 (t1.totag=t2.fromtag and t1.fromtag=t2.totag)) and
					t1.sip_method='INVITE'
			where t2.username='".$user."' and t2.domain='".$domain."' and t2.sip_method='BYE' and isnull(t1.username) )
		
		   order by ttime desc";
		
		
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				$o=new stdClass();
			
				$timestamp=gmmktime(substr($row->ttime,11,2), 	//hour
									substr($row->ttime,14,2), 	//minute
									substr($row->ttime,17,2), 	//second
									substr($row->ttime,5,2), 	//month
									substr($row->ttime,8,2), 	//day
									substr($row->ttime,0,4));	//year

				if ($timestamp <=0 ) $o->time="";
				else {
					if (date('Y-m-d',$timestamp)==date('Y-m-d')) $o->time="today ".date('H:i',$timestamp);
					else $o->time=date('Y-m-d H:i',$timestamp);
				}
		
				if (!$row->bye_callid){ //unpaired record, only INVITE
					$o->sip_callid=$row->inv_callid;
					$o->to_uri=$row->inv_to_uri;
					$o->sip_to=$row->inv_sip_to;
		
					$o->length="n/a";
					$o->hangup="n/a";
				}
				elseif (!$row->inv_callid){ //unpaired record, only BYE
					$o->sip_callid=$row->bye_callid;
					$o->to_uri=$row->bye_from_uri;
					$o->sip_to=$row->bye_sip_from;
		
					$o->length="n/a";
					$o->hangup="n/a";
				}
				else{
					$o->sip_callid=$row->inv_callid;
					$o->to_uri=$row->inv_to_uri;
					$o->sip_to=$row->inv_sip_to;
		
					if ($row->inv_fromtag==$row->bye_fromtag) $o->hangup="caller";
					else if ($row->inv_fromtag==$row->bye_totag) $o->hangup="callee";
					else $o->hangup="n/a";
		
					$o->length=$row->length;
				}
				$out[]=$o;

			} //while
			$res->free();			

			return $out;
			
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}

	
	}

}

?>