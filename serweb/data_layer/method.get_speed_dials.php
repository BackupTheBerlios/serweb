<?
/*
 * $Id: method.get_speed_dials.php,v 1.2 2004/11/05 19:43:33 kozlik Exp $
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
 *    from	(numeric)
 *      lower limit of numeric usernames which should be returned
 *
 *    to  	(numeric)
 *      upeer limit of numeric usernames which should be returned
 *
 *    solid_interval_of_usernames   (bool) default:false
 *      if is true, solid interval of usernames is returned, this requires 
 *      to set 'from' and 'to' options too
 *  
 */ 

class CData_Layer_get_speed_dials {
	var $required_methods = array();

	function get_speed_dials($user, $opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

	    $opt_from = (isset($opt['from'])) ? $opt['from'] : "";
    	$opt_to   = (isset($opt['to']))   ? $opt['to'] : "";
		$opt_solid_interval = isset($opt['solid_interval_of_usernames']) ? (bool)$opt['solid_interval_of_usernames'] : false;

		/* options 'from' and 'to' must be specified if we want get solid interval of usernames */
		if ($opt_from === "" or $opt_to === "") $opt_solid_interval = false;
		
//		if (!is_null($sd)) $qw=" and (username_from_req_uri!='$sd' or domain_from_req_uri!='$sd_dom') "; else $qw="";

		$where_phrase = "";
		if (!is_null($opt_from)) $where_phrase .= " and abs(username_from_req_uri) >= ".$opt_from." ";  //abs() converts string to integer
		if (!is_null($opt_to))   $where_phrase .= " and abs(username_from_req_uri) <= ".$opt_to." ";

		$q="select username_from_req_uri, domain_from_req_uri, new_request_uri, first_name, last_name from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user).$where_phrase." order by username_from_req_uri";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		$last_username = $opt_from - 1;
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			/* if should be returned solid interval, skip usernames which isn't numerical */
			if ($opt_solid_interval and !is_numeric($row['username_from_req_uri'])) {
				$i--; /*corect incorrect increment*/ continue;
			}
			
			/* if should be returned solid interval and is there hole - fill in it */
			if ($opt_solid_interval and 
			      ((int)$last_username)+1 < ((int) $row['username_from_req_uri']) 
			   )$this->sd_fill_interval($out, $i, $last_username, $row['username_from_req_uri']);

			$out[$i]   = $row;
			$out[$i]['empty']  = false;
			$out[$i]['primary_key']  = array('username_from_req_uri' => &$out[$i]['username_from_req_uri'],
			                                 'domain_from_req_uri' => &$out[$i]['domain_from_req_uri']);
			$last_username = $row['username_from_req_uri'];
		}
		
		/* if should be returned solid interval and last_username not equal to high limit */		
		if ($opt_solid_interval and 
		    ((int)$last_username) < ((int) $opt_to) ) $this->sd_fill_interval($out, $i, $last_username, $opt_to+1);

		$res->free();
		return $out;
	}
	

	/*
		function add empty records to 'out' array with 'username_from_req_uri' from
		interval ($starting_username, $ending_username) - exclude limits
	*/
	function sd_fill_interval(&$out, &$i, $starting_username, $ending_username){
		/* if limits isn't numeric, return*/
		if (!is_numeric($starting_username) or !is_numeric($ending_username)) return;
		
		$starting_username = ((int)$starting_username) + 1;
		$ending_username   = ((int)$ending_username);
		
		for (; $starting_username < $ending_username; $i++, $starting_username++){
			$out[$i]['username_from_req_uri'] = sprintf("%02u", $starting_username);
			$out[$i]['domain_from_req_uri']   = "";
			$out[$i]['new_request_uri']       = "";
			$out[$i]['first_name']            = "";
			$out[$i]['last_name']             = "";
			$out[$i]['empty']                 = true;
			$out[$i]['primary_key']  = array('username_from_req_uri' => &$out[$i]['username_from_req_uri'],
			                                 'domain_from_req_uri' => &$out[$i]['domain_from_req_uri']);
		}
	
	}

}
?>
