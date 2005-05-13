<?
/*
 * $Id: method.get_speed_dials.php,v 1.6 2005/05/13 14:29:21 kozlik Exp $
 */

/**
 *  Function return array of associtive arrays containig speed dials of $user
 *
 *  Keys of associative arrays:
 *    sd_username
 *    sd_domain
 *    new_uri
 *    fname
 *    lname
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
 *	  sd_domain		(string) default: ''
 *		Domain used in speed dials
 *
 *	  sd_from		(int) dafault: 0
 *		Starting username part of speed dials
 *
 *	  sd_to			(int) dafault: 99
 *		Ending username part of speed dials
 *
 *    length  		(integer)
 *      length of inserted usernames with leading zeros, default is derived from 
 *		length of sd_to parameter
 *
 */ 

class CData_Layer_get_speed_dials {
	var $required_methods = array();

	function get_speed_dials($user, $opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->speed_dial;

    	$opt['sort']      = (isset($opt['sort']))      ? $opt['sort'] : "from_uri";
    	$opt['sort_desc'] = (isset($opt['sort_desc'])) ? $opt['sort_desc'] : false;

    	$opt['sd_domain'] = (isset($opt['sd_domain'])) ? $opt['sd_domain'] : '';
    	$opt['sd_from']= (isset($opt['sd_from'])) ? $opt['sd_from'] : 0;
    	$opt['sd_to'] = (isset($opt['sd_to'])) ? $opt['sd_to'] : 99;
    	$opt['length'] = (isset($opt['length'])) ? $opt['length'] : ceil(log10($opt['sd_to']));

		$where_phrase = "";
		
		if (false === $num_rows_in_db = $this->get_speed_dials_count($user, $where_phrase, $errors)) return false;
		$this->set_num_rows($opt['sd_to'] - $opt['sd_from'] +1);

		$q_limit = " limit ".$this->get_act_row().", ".$this->get_showed_rows();

		/* sorting */
		switch ($opt['sort']){	
		case "from_uri":	
			if ($opt['sort_desc']){
				$opt['get_from'] = $opt['sd_to']-$this->get_act_row()-$this->get_showed_rows()+1;
				$opt['get_to']   = $opt['sd_to']-$this->get_act_row()+1;
			}
			else{
				$opt['get_from'] = $opt['sd_from']+$this->get_act_row();
				$opt['get_to']   = $opt['sd_from']+$this->get_act_row()+$this->get_showed_rows();
			}
		
			$q_ord = $c->sd_username; 
			$where_phrase .= " and abs(".$c->sd_username.") >= ".$opt['get_from']." ";  //abs() converts string to integer
			$where_phrase .= " and abs(".$c->sd_username.") <  ".$opt['get_to']." ";
			$q_limit = "";
			break;
		case "to_uri":		$q_ord = $c->new_uri; break;
		case "fname":		$q_ord = $c->fname; break;
		case "lname":		$q_ord = $c->lname; break;
		default: 
			log_errors(PEAR::raiseError("unknown sorting column: ".$opt['sort']), $errors); return false;
		}

		if ($opt['sort_desc'] and $opt['sort'] != "from_uri"){    /* sorting descending*/
			$q_ord .= " desc";
		}


		$q="select ".$c->sd_username." as sd_username, ".
			         $c->sd_domain." as sd_domain, ".
					 $c->new_uri." as new_uri, ".
					 $c->fname." as fname, ".
					 $c->lname." as lname ".
			" from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user).$where_phrase.
		   " order by ".$q_ord.
		   $q_limit;
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		if ($opt['sort'] == "from_uri"){
			if (false === $out = $this->sd_format_result_by_sd_uri ($user, $num_rows_in_db, $res, $opt, $errors))
				return false;
		}
		else{
			if (false === $out = $this->sd_format_result ($user, $num_rows_in_db, $res, $opt, $errors))
				return false;
		}

		$res->free();

		return $out;
	}
	
	/**
	 *	Format result of DB query, add empty rows
	 *
	 *	@param object $user
	 *	@param int $num_rows_in_db
	 *	@param object $res
	 *	@param array $opt
	 *	@param array $errors
	 *	@return array
	 *	@access private
	 */
	function sd_format_result ($user, $num_rows_in_db, $res, $opt, &$errors){

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){

			$out[$i]   = $row;
			$out[$i]['empty']  = false;
			$out[$i]['primary_key']  = array('sd_username' => &$out[$i]['sd_username'],
			                                 'sd_domain' => &$out[$i]['sd_domain']);
		}

		if (false === $this->sd_add_empty_entries_to_result($user, $num_rows_in_db, $out, $opt, $errors)) return false;
		
		return $out;
	}


	/**
	 *	Format result of DB query, add empty rows
	 *	Used in case when result should be sorted by speed dial
	 *
	 *	@param object $user
	 *	@param int $num_rows_in_db
	 *	@param object $res
	 *	@param array $opt
	 *	@param array $errors
	 *	@return array
	 *	@access private
	 */
	function sd_format_result_by_sd_uri ($user, $num_rows_in_db, $res, $opt, &$errors){

		$out=array();
		$last_username = $opt['get_from'] - 1;
		$to = $opt['get_to'];
		
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			/* skip usernames which isn't numerical */
			if (!is_numeric($row['sd_username'])) {
				$i--; /*corect incorrect increment*/ continue;
			}
			
			/* if is there hole - fill in it */
			if (((int)$last_username)+1 < ((int) $row['sd_username']))
				$this->sd_fill_interval($out, $i, $last_username, $row['sd_username'], $opt);

			$out[$i]           = $row;
			$out[$i]['empty']  = false;
			$out[$i]['primary_key']  = array('sd_username' => &$out[$i]['sd_username'],
			                                 'sd_domain' => &$out[$i]['sd_domain']);
			$last_username = $row['sd_username'];
		}
		
		/* if last_username not equal to high limit */		
		if (((int)$last_username) < $to)  
			$this->sd_fill_interval($out, $i, $last_username, $to, $opt);

		if ($opt['sort_desc'])
			$out = array_reverse($out);
		
		return $out;
	}

	/**
	 *	function add empty records to 'out' array with 'sd_username' from
	 *	interval ($starting_username, $ending_username) - exclude limits
	 *
	 *	@param array $out
	 *	@param int $i		key into $out array
	 *	@param int $starting_username
	 *	@param int $ending_username
	 *	@param array $opt
	 *	@access private
	 */
	function sd_fill_interval(&$out, &$i, $starting_username, $ending_username, $opt){
		/* if limits isn't numeric, return*/
		if (!is_numeric($starting_username) or !is_numeric($ending_username)) return;
		
		$starting_username = ((int)$starting_username) + 1;
		$ending_username   = ((int)$ending_username);
		
		for (; $starting_username < $ending_username; $i++, $starting_username++){
			$out[$i]['sd_username'] = sprintf("%02u", $starting_username);
			$out[$i]['sd_domain']   = $opt['sd_domain'];
			$out[$i]['new_uri']     = "";
			$out[$i]['fname']       = "";
			$out[$i]['lname']       = "";
			$out[$i]['empty']       = true;
			$out[$i]['primary_key'] = array('sd_username' => &$out[$i]['sd_username'],
			                                'sd_domain' => &$out[$i]['sd_domain']);
		}
	
	}

	/**
	 *	Add empty entries with correct speed dials to end of result
	 *
	 *	@param object $user
	 *	@param int $num_rows_in_db
	 *	@param object $result
	 *	@param array $opt
	 *	@param array $errors
	 *	@return bool 		true on siccess, false on error
	 *	@access private
	 */
	function sd_add_empty_entries_to_result($user, $num_rows_in_db, &$result, $opt, &$errors){

		/* if result is empty */
		if (count($result) == 0){
			/* determine number of first speed dial of added rows */
			$from = $this->get_act_row() - $num_rows_in_db;
			/* number of added empty rows */
			$nr = $this->get_showed_rows();
		}
		else{
			$from = 0;
			/* number of added empty rows */
			$nr = $this->get_showed_rows() - count($result);
		}

		if ($nr == 0) return true;		

		/* get array of not used speed dials by this user */
		if (false === $unused = $this->sd_get_unused_speed_dials($user, $from, $nr, $errors)) return false;

		$out = array();
		$i=0;
		/* create array of empty entries */
		foreach ($unused as $v){
			$out[$i] = array('sd_username' => sprintf("%0".$opt['length']."u", $v),
			                 'sd_domain' => $opt['sd_domain'],
			                 'new_uri' => '',
			                 'fname' => '',
			                 'lname' => '',
			                 'empty' => true);
			$out[$i]['primary_key'] = array('sd_username' => &$out[$i]['sd_username'],
			                                'sd_domain' => &$out[$i]['sd_domain']);
			$i++;		
		}
		
		/* merge created array with result */
		$result = array_merge($result, $out);
		return true;
	}



	/**
	 *	Get array of speed dials unused by $user
	 *
	 *	@param object $user
	 *	@param int $from
	 *	@param int $nr
	 *	@param array $errors
	 *	@return array
	 *	@access private
	 */
	function sd_get_unused_speed_dials($user, $from, $nr, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->speed_dial;

		/* get used speed dials */	
		$q="select ".$c->sd_username." as sd_username 
		    from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user)." order by sd_username";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		/* return array */
		$out = array();
		/* counter */
		$i = 0;
		/* determine if in next cycle should be fetched next used speed dial*/
		$fetch_next = true;
		/* counter of skipped entries*/
		$skiped = 0;

		/* create array of unused spedd dials */		
		while (count($out) < $nr){
			if ($fetch_next){
				$row=$res->fetchRow(DB_FETCHMODE_ASSOC);
				if ($row) $next_used = $row['sd_username'];
				else $next_used = -1;

				$fetch_next = false;
			}

			if ($i == $next_used) {
				$fetch_next = true;
			}
			else{
				if ($skiped < $from){
					$skiped++;
				}
				else {
					$out[] = $i;
				}
			}

			$i++;
		}

		return $out;
	}




	
	/**
	 *	count number of entries which match to where phrase 
	 *
	 *	@param object $user
	 *	@param string $where_phrase
	 *	@param array $errors
	 *	@return int
	 *	@access private
	 */
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
