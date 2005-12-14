<?
/*
 * $Id: method.update_speed_dial.php,v 1.3 2005/12/14 16:19:58 kozlik Exp $
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

	function update_speed_dial($uid, $values, $opt){
		global $config, $lang_str;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		$opt_insert = isset($opt['insert']) ? (bool)$opt['insert'] : false;
		if (!isset($opt['primary_key']) or !is_array($opt['primary_key']) or empty($opt['primary_key'])){
			ErrorHandler::log_errors(PEAR::raiseError('primary key is missing')); return false;
		}

		if (!isset($opt['original_vals']) or 
			!array_key_exists('fname', $opt['original_vals']) or
			!array_key_exists('lname', $opt['original_vals'])){

				ErrorHandler::log_errors(PEAR::raiseError('original values of fname or lname is not set')); return false;
		}

		/* table's name */
		$t_name  = &$config->data_sql->speed_dial->table_name;
		$ta_name = &$config->data_sql->sd_attrs->table_name;
		/* col names */
		$c  = &$config->data_sql->speed_dial->cols;
		$ca = &$config->data_sql->sd_attrs->cols;
		/* flags */
		$fa = &$config->data_sql->sd_attrs->flag_values;
		/* attr names */
		$an = &$config->attr_names;


		if ($opt_insert) {
			$q = "select max(".$c->id.") from ".$t_name;

			$res=$this->db->query($q);
			if (DB::isError($res)) {ErrorHandler::log_errors($res, $errors); return false;}

			$next_id = 0;
			if ($row=$res->fetchRow(DB_FETCHMODE_ORDERED)){
				if (!is_null($row[0])) $next_id = $row[0] + 1;
			}

			if (false === $this->sd_insert($next_id, $uid, $values)) return false;


			if ($values['fname'] != ""){
				if (false === $this->sd_attr_insert($next_id, $an['sd_fname'], $values['fname'])) return false;
			}

			if ($values['lname'] != ""){
				if (false === $this->sd_attr_insert($next_id, $an['sd_lname'], $values['lname'])) return false;
			}
			
			return true;
		}

		if ($values['new_uri'] == "" and 
		    $values['fname'] == "" and
			$values['lname'] == ""){
			
			if (false === $this->sd_delete($opt['primary_key']['id'], $uid)) return false;

			return true;
		}


		if (false === $this->sd_update($opt['primary_key']['id'], $uid, $values)) return false;
		
		if ($opt['original_vals']['fname'] != $values['fname']){

			if ($values['fname'] == ""){
				if (false === $this->sd_attr_delete($opt['primary_key']['id'], $an['sd_fname'])) return false;
			}
			elseif(is_null($opt['original_vals']['fname'])){
				if (false === $this->sd_attr_insert($opt['primary_key']['id'], $an['sd_fname'], $values['fname'])) return false;
			}
			else{
				if (false === $this->sd_attr_update($opt['primary_key']['id'], $an['sd_fname'], $values['fname'])) return false;
			}
			
		}


		if ($opt['original_vals']['lname'] != $values['lname']){

			if ($values['lname'] == ""){
				if (false === $this->sd_attr_delete($opt['primary_key']['id'], $an['sd_lname'])) return false;
			}
			elseif(is_null($opt['original_vals']['lname'])){
				if (false === $this->sd_attr_insert($opt['primary_key']['id'], $an['sd_lname'], $values['lname'])) return false;
			}
			else{
				if (false === $this->sd_attr_update($opt['primary_key']['id'], $an['sd_lname'], $values['lname'])) return false;
			}
			
		}

		return true;
	}

	function sd_insert($id, $uid, &$values){
		global $config;

		/* table's name */
		$t_name  = &$config->data_sql->speed_dial->table_name;
		/* col names */
		$c  = &$config->data_sql->speed_dial->cols;

		$q="insert into ".$t_name." (
		           ".$c->id.", 
		           ".$c->uid.", 
				   ".$c->dial_username.",
				   ".$c->dial_did.", 
				   ".$c->new_uri."
		    ) 
			values (
			       ".$id.", 
			       '".$uid."', 
				   '".$values['dial_username']."', 
				   '".$values['dial_did']."', 
				   '".$values['new_uri']."'
			 )";

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
		
		return true;
	}

	function sd_attr_insert($id, $name, $value){
		global $config;

		/* table's name */
		$ta_name = &$config->data_sql->sd_attrs->table_name;
		/* col names */
		$ca = &$config->data_sql->sd_attrs->cols;
		/* flags */
		$fa = &$config->data_sql->sd_attrs->flag_values;

		$q="insert into ".$ta_name." (
		           ".$ca->id.", 
		           ".$ca->name.", 
				   ".$ca->value.",
				   ".$ca->flags."
		    ) 
			values (
			       ".$id.", 
			       '".$name."', 
				   '".$value."', 
				   '".$fa['DB_FOR_SERWEB']."'
			 )";

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
		
		return true;
	}

	function sd_delete($id, $uid){
		global $config;

		/* table's name */
		$t_name  = &$config->data_sql->speed_dial->table_name;
		$ta_name = &$config->data_sql->sd_attrs->table_name;
		/* col names */
		$c  = &$config->data_sql->speed_dial->cols;
		$ca = &$config->data_sql->sd_attrs->cols;

		$q="delete from ".$t_name." 
			where ".$c->id." = ".$id." and 
				  ".$c->uid." = '".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}

		$q="delete from ".$ta_name." 
			where ".$ca->id." = ".$id;

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}

		return true;	
	}

	function sd_attr_delete($id, $name){
		global $config;

		/* table's name */
		$ta_name = &$config->data_sql->sd_attrs->table_name;
		/* col names */
		$ca = &$config->data_sql->sd_attrs->cols;

		$q="delete from ".$ta_name." 
			where ".$ca->id." = ".$id." and 
			      ".$ca->name." = '".$name."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}

		return true;	
	}

	function sd_update($id, $uid, $values){
		global $config;

		/* table's name */
		$t_name  = &$config->data_sql->speed_dial->table_name;
		/* col names */
		$c  = &$config->data_sql->speed_dial->cols;

		// do replace (faster than update) if useing mysql
		if ($this->db_host['parsed']['phptype'] == 'mysql'){
			$q="replace ".$t_name." (
			           ".$c->id.", 
			           ".$c->uid.", 
					   ".$c->dial_username.",
					   ".$c->dial_did.", 
					   ".$c->new_uri."
			    ) 
				values (
					   ".$id.",
				       '".$uid."', 
					   '".$values['dial_username']."', 
					   '".$values['dial_did']."', 
					   '".$values['new_uri']."'
				 )";
		}
		else{
		// for other databases do insert or update
			$q="update ".$t_name." 
			    set ".$c->new_uri."='".$values['new_uri']."', 
					".$c->dial_username."='".$values['dial_username']."', 
					".$c->dial_did."='".$values['dial_did']."' 
				where ".$c->id." = ".$id." and 
					  ".$c->uid." = '".$uid."'";
		}
	
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}

		return true;	
	}

	function sd_attr_update($id, $name, $value){
		global $config;

		/* table's name */
		$ta_name = &$config->data_sql->sd_attrs->table_name;
		/* col names */
		$ca = &$config->data_sql->sd_attrs->cols;

		/* notice: the 'replace' statement (for mysql) can't be used here 
		           bacause it cause insert into sd_attrs instead of update
		 */
		$q="update ".$ta_name." 
		    set ".$ca->value."='".$value."'
			where ".$ca->id." = ".$id." and 
				  ".$ca->name." = '".$name."'";
	
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}

		return true;	
	}

}
?>
