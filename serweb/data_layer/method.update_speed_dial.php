<?
/*
 * $Id: method.update_speed_dial.php,v 1.3 2005/05/13 14:29:21 kozlik Exp $
 */

/*
 *  Function update speed dial entry of $user by $values
 *
 *  Keys of associative array $values:
 *    new_uri
 *    fname
 *    lname
 *    primary_key            - array - reflect primary key without user identification
 *
 *
 *  Possible options parameters:
 *
 *    primary_key	(array) required
 *      contain primary key (without user specification) of record which should be updated
 *      The array contain the same keys as functon get_speed_dials returned in entry 'primary_key'
 *
 *    insert  	(bool) default:true
 *      if true, function insert new record, otherwise update old record
 *
 */ 

class CData_Layer_update_speed_dial {
	var $required_methods = array();

	function update_speed_dial($user, $values, $opt, &$errors){
		global $config, $lang_str;
		
		if (!$this->connect_to_db($errors)) return false;

		$opt_insert = isset($opt['insert']) ? (bool)$opt['insert'] : false;
		if (!isset($opt['primary_key']) or !is_array($opt['primary_key']) or empty($opt['primary_key'])){
			log_errors(PEAR::raiseError('primary key is missing'), $errors); return false;
		}

		$c = &$config->data_sql->speed_dial;

		$att=$this->get_indexing_sql_insert_attribs($user);

		if ($values['new_uri'] == "" and 
		    $values['fname'] == "" and
			$values['lname'] == ""){
			
			$q="delete from ".$config->data_sql->table_speed_dial." 
				where ".$c->sd_username."='".$opt['primary_key']['sd_username']."' and 
				      ".$c->sd_domain."='".$opt['primary_key']['sd_domain']."' and 
					  ".$this->get_indexing_sql_where_phrase($user);
		}
		else{
			$q="replace ".$config->data_sql->table_speed_dial." (
			           ".$att['attributes'].", 
					   ".$c->sd_username.",
					   ".$c->sd_domain.", 
					   ".$c->new_uri.", 
					   ".$c->fname.", 
					   ".$c->lname."
			    ) 
				values (
				       ".$att['values'].", 
					   '".$opt['primary_key']['sd_username']."',
					   '".$opt['primary_key']['sd_domain']."',
					   '".$values['new_uri']."', 
					   '".$values['fname']."', 
					   '".$values['lname']."'
				 )";
		}

/*
		if ($opt_insert) {
			$att=$this->get_indexing_sql_insert_attribs($user);

			$q="insert into ".$config->data_sql->table_speed_dial." (
			           ".$att['attributes'].", 
					   ".$c->sd_username.",
					   ".$c->sd_domain.", 
					   ".$c->new_uri.", 
					   ".$c->fname.", 
					   ".$c->lname."
			    ) 
				values (
				       ".$att['values'].", 
					   '".$opt['primary_key']['sd_username']."',
					   '".$opt['primary_key']['sd_domain']."',
					   '".$values['new_uri']."', 
					   '".$values['fname']."', 
					   '".$values['lname']."'
				 )";
		}
		else {
			$q="update ".$config->data_sql->table_speed_dial." 
			    set ".$c->new_uri."='".$values['new_uri']."', 
					".$c->fname."='".$values['fname']."', 
					".$c->lname."='".$values['lname']."' 
				where ".$c->sd_username."='".$opt['primary_key']['sd_username']."' and 
				      ".$c->sd_domain."='".$opt['primary_key']['sd_domain']."' and 
					  ".$this->get_indexing_sql_where_phrase($user);

		}
*/
		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
				$errors[]=$lang_str['err_speed_dial_already_exists'];
			else log_errors($res, $errors); 
			return false;
		}
		return true;

	}

}
?>
