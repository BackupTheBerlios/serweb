<?
/*
 * $Id: missed_calls.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class Cmisc{
	var $sip_from, $time, $sip_status, $status;
	function Cmisc($from_uri, $sip_from, $time, $sip_status, $status){
		$this->from_uri=$from_uri;
		$this->sip_from=$sip_from;
		$this->time=$time;
		$this->sip_status=$sip_status;
		$this->status=$status;
	}
}

class CData_Layer extends CDL_common{

	function del_missed_calls($user, $domain, $timestamp, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':

			if (!is_array($aliases = $this->get_aliases("sip:".$user."@".$domain, $errors))) return false;

			$a=new stdClass();
			$a->username=$user;
			$a->domain=$domain;
			
			$aliases[]=$a;
			
			/* delete missed calls of $user and all him aliases */
			foreach($aliases as $row){
				$q="delete from ".$config->data_sql->table_missed_calls.
					" where username='".$row->username."' and domain='".$row->domain."' ".
					" and time<'".gmdate("Y-m-d H:i:s", $timestamp)."'";
				$res=$this->db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors); return false;}
			}
	
			return true;			
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}
	
	function get_missed_calls($user, $domain, &$errors){
		global $config;

		switch($this->container_type){
		case 'sql':
			/* get num rows */		
			$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
					"WHERE t1.username='".$user."' and t1.domain='".$domain."' ) ".
				"UNION ".
				"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
					"FROM ".$config->data_sql->table_missed_calls." t1, ".$config->data_sql->table_aliases." t2 ".
					"WHERE 'sip:".$user."@".$domain."'".
						"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain ) ";
		
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
			$this->set_num_rows($res->numRows());
			$res->free();
		
			/* if act_row is bigger then num_rows, correct it */
			if ($this->get_act_row() >= $this->get_num_rows()) 
				$this->set_act_row(max(0, $this->get_num_rows()-$this->get_showed_rows()));
		
			$q="(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status  ".
					"FROM ".$config->data_sql->table_missed_calls." t1 ".
					"WHERE t1.username='".$user."' and t1.domain='".$domain."' ) ".
				"UNION ".
				"(SELECT t1.from_uri, t1.sip_from, t1.time, t1.sip_status ".
					"FROM ".$config->data_sql->table_missed_calls." t1, ".$config->data_sql->table_aliases." t2 ".
					"WHERE 'sip:".$user."@".$domain."'".
						"=t2.contact AND t2.username=t1.username AND t2.domain=t1.domain ) ".
				"ORDER BY time DESC ".
				"limit ".$this->get_act_row().", ".$this->get_showed_rows();
		
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				$timestamp=gmmktime(substr($row->time,11,2), 	//hour
									substr($row->time,14,2), 	//minute
									substr($row->time,17,2), 	//second
									substr($row->time,5,2), 	//month
									substr($row->time,8,2), 	//day
									substr($row->time,0,4));	//year
				if ($timestamp <=0 ) $time="";
				else {
					if (date('Y-m-d',$timestamp)==date('Y-m-d')) $time="today ".date('H:i',$timestamp);
					else $time=date('Y-m-d H:i',$timestamp);
				}

				$out[]=new Cmisc($row->from_uri, $row->sip_from, $time,
					$row->sip_status, $this->get_status($row->from_uri, $errors));
			}
			$res->free();
		
			return $out;
		case 'ldap':
			die('NOT IMPLEMENTED: '.__FILE__.":".__LINE__);
		}
	}
	
}

?>