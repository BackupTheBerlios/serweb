<?
/*
 * $Id: method.get_speed_dials.php,v 1.3 2004/11/29 21:32:50 kozlik Exp $
 */

/*
 *  Function return array of associtive arrays containig speed dials of $user
 *
 *  Keys of associative arrays:
 *    username_from_req_uri
 *    domain_from_req_uri
 *    new_request_uri
 *    first_name
 *    last_name
 *    empty					 - is true if column isn't stored in database
 *    primary_key            - array - reflect primary key without user identification
 *
 *
 *  Possible options parameters:
 *
 *    sort  	(one of: 'from_uri', 'fname', 'lname', 'to_uri') default: 'from_uri'
 *      column by which the result may be sorted
 *
 */ 

class CData_Layer_get_speed_dials {
	var $required_methods = array();

	function get_speed_dials($user, $opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

    	$opt_sort   = (isset($opt['sort']))   ? $opt['sort'] : "from_uri";

		$where_phrase = "";
		
		if (false === $num_rows = $this->get_speed_dials_count($user, $where_phrase, $errors)) return false;
		$this->set_num_rows($num_rows);
		
		$q="select username_from_req_uri, domain_from_req_uri, new_request_uri, first_name, last_name from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user).$where_phrase." order by ";
			
		/* sorting */
		switch ($opt_sort){	/* the expressions cause that empty values are on the end */
		case "from_uri":
			$q .= "if(ifnull(trim(username_from_req_uri)='',1), char(255, 255, 255), username_from_req_uri)"; break;
		case "to_uri":
			$q .= "if(ifnull(trim(new_request_uri)='',1), char(255, 255, 255), new_request_uri)"; break;
		case "fname":
			$q .= "if(ifnull(trim(first_name)='',1), char(255, 255, 255), first_name)"; break;
		case "lname":
			$q .= "if(ifnull(trim(last_name)='',1), char(255, 255, 255), last_name)"; break;
		default: 
			log_errors(PEAR::raiseError("unknown sorting column: ".$opt_sort), $errors); return false;
		}

		$q .= " limit ".$this->get_act_row().", ".$this->get_showed_rows();
			
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();

		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){

			$out[$i]   = $row;
			$out[$i]['empty']  = false;
			$out[$i]['primary_key']  = array('username_from_req_uri' => &$out[$i]['username_from_req_uri'],
			                                 'domain_from_req_uri' => &$out[$i]['domain_from_req_uri']);
		}

		$res->free();
		return $out;
	}
	
	/* count number of entries which match to where phrase */
	function get_speed_dials_count($user, $where_phrase, &$errors){
		global $config;
		$q="select count(*) from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user).$where_phrase;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();
		return $row[0];
	}
}
?>
