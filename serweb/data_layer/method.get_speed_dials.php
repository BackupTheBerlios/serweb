<?
/*
 * $Id: method.get_speed_dials.php,v 1.4 2004/12/15 11:57:58 kozlik Exp $
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
 *    sort_desc  	(boolean) default: false
 *      By default is output sorted ascending. Setting this option to true cause sorting descending
 *
 */ 

class CData_Layer_get_speed_dials {
	var $required_methods = array();

	function get_speed_dials($user, $opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

    	$opt_sort      = (isset($opt['sort']))      ? $opt['sort'] : "from_uri";
    	$opt_sort_desc = (isset($opt['sort_desc'])) ? $opt['sort_desc'] : false;

		$where_phrase = "";
		
		if (false === $num_rows = $this->get_speed_dials_count($user, $where_phrase, $errors)) return false;
		$this->set_num_rows($num_rows);
		
		$q="select username_from_req_uri, domain_from_req_uri, new_request_uri, first_name, last_name from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user).$where_phrase." order by ";
			
		/* sorting */
		if ($opt_sort_desc){    /* sorting descending*/
			switch ($opt_sort){	
			case "from_uri":
				$q .= "username_from_req_uri desc"; break;
			case "to_uri":
				$q .= "new_request_uri desc"; break;
			case "fname":
				$q .= "first_name desc"; break;
			case "lname":
				$q .= "last_name desc"; break;
			default: 
				log_errors(PEAR::raiseError("unknown sorting column: ".$opt_sort), $errors); return false;
			}
		}
		else{                   /* sorting ascending*/
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
