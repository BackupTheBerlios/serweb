<?
/*
 * $Id: method.get_phonebook_entries.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_phonebook_entries {
	var $required_methods = array('get_aliases_by_uri', 'get_status');
	
	function get_phonebook_entries($user, $pbid, &$errors){
		global $config, $serweb_auth, $sess;

		if (!$this->connect_to_db($errors)) return false;

		if (!is_null($pbid)) $qw=" and id!=".$pbid." "; else $qw="";

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
	
		$q="select id, fname, lname, sip_uri from ".$config->data_sql->table_phonebook.
			" where ".$this->get_indexing_sql_where_phrase($user).$qw." order by lname".
			" limit ".$this->get_act_row().", ".$this->get_showed_rows();
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
			
			$out[$row->id]['name'] = implode(' ', array($name=$row->lname, $row->fname));
			$out[$row->id]['status'] = $this->get_status($row->sip_uri, $errors);
			$out[$row->id]['sip_uri'] = $row->sip_uri;
			$out[$row->id]['url_ctd'] = "javascript: open_ctd_win2('".rawURLEncode($row->sip_uri)."', '".RawURLEncode("sip:".$serweb_auth->uname."@".$serweb_auth->domain)."');";
			$out[$row->id]['url_dele'] = $sess->url("phonebook.php?kvrk=".uniqID("")."&dele_id=".$row->id);
			$out[$row->id]['url_edit'] = $sess->url("phonebook.php?kvrk=".uniqID("")."&edit_id=".$row->id);
			$out[$row->id]['aliases']='';

			if (false === ($aliases = $this->get_aliases_by_uri($row->sip_uri, $errors))) continue;

			$alias_arr=array();
			foreach($aliases as $val) $alias_arr[] = $val->username;
			
			$out[$row->id]['aliases'] = implode(", ", $alias_arr);
		}
		$res->free();

		return $out;			
	}
	
}
?>
