<?
/*
 * $Id: user_preferences.php,v 1.1 2004/04/14 20:51:31 kozlik Exp $
 */

class Cattrib{
	var $att_name;
	var $att_value;
	var $att_rich_type;
	var $att_type_spec;
	var $dafault_value;

	function Cattrib($att_name, $att_value, $att_rich_type, $att_type_spec){
		$this->att_name=$att_name;
		$this->att_value=$att_value;
		$this->att_rich_type=$att_rich_type;
		$this->att_type_spec=$att_type_spec;
		$this->default_value=$att_value;
	}
}

class CData_Layer extends CDL_common{

	function get_attributes($att_edit, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			if (! is_null($att_edit)) $qw=" att_name != '$att_edit' "; else $qw="1";

			$q="select att_name, att_rich_type, att_type_spec, default_value from ".$config->data_sql->table_user_preferences_types.
				" where ".$qw.
				" order by att_name";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				$out[$row->att_name]=new Cattrib($row->att_name, $row->default_value, $row->att_rich_type, $row->att_type_spec);
			}
			$res->free();
			return $out;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}

	function get_att_values($user, $domain, &$attributes, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select attribute, value from ".$config->data_sql->table_user_preferences.
				" where domain='".$domain."' and username='".$user."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
				$attributes[$row->attribute]->att_value = $row->value;
			}
			$res->free();
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}

	function update_attribute_of_user($user, $domain, $attribute, $value, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="replace into ".$config->data_sql->table_user_preferences." (username, domain, attribute, value) ".
				"values ('".$user."', '".$domain."', '".$attribute."', '".$value."')";

			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}

	function get_providers(&$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select id, name from ".$config->data_sql->table_providers;
			$res=$this->db->query($q); 
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$out=array();
			while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=new UP_List_Items($row->name, $row->id);
			$res->free();
			return $out;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
	function del_attribute($att_name, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			//delete attribute from user_preferences table
			$q="delete from ".$config->data_sql->table_user_preferences.
				" where attribute='".$att_name."'";
			$res=$this->db->query($q); 
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			//delete attribute form user_preferences_types table
			$q="delete from ".$config->data_sql->table_user_preferences_types.
				" where att_name='".$att_name."'";
			$res=$this->db->query($q); 
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
	function get_attribute($att_name, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="select att_name, att_rich_type, att_type_spec, default_value from ".$config->data_sql->table_user_preferences_types.
				" where att_name='".$att_name."'";
			$res=$this->db->query($q); 
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
			$res->free();
			return $row;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}

	function update_attribute($att_edit, $att_name, $att_rich_type, $att_raw_type, $default_value, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			if ($att_edit) 
				$q="update ".$config->data_sql->table_user_preferences_types." ".
					"set att_name='$att_name', att_rich_type='$att_rich_type', default_value='$default_value', ".
						"att_raw_type='".$att_raw_type."'".
					"where att_name='$att_edit'";
			else 
				$q="insert into ".$config->data_sql->table_user_preferences_types." (att_name, att_rich_type, default_value, att_raw_type) ".
					"values ('$att_name', '$att_rich_type', '$default_value', '".$att_raw_type."')";
	
			$res=$this->db->query($q); 
			if (DB::isError($res)) {
				if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
					$errors[]="This attribute name already exists - choose another";
				else log_errors($res, $errors); 
				return false;
			}
	
			//if name of attribute is changed, update user_preferences table
			if ($att_edit and $att_edit!=$att_name){
				$q="update ".$config->data_sql->table_user_preferences." ".
					"set attribute='$att_name' where attribute='$att_edit'";
	
				$res=$this->db->query($q); 
				if (DB::isError($res)) {log_errors($res, $errors); return false;}
			}

			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
	
	function update_att_type_spec($att_name, $att_type_spec, $default_value, &$errors){
		global $config;
		
		switch($this->container_type){
		case 'sql':
			$q="update ".$config->data_sql->table_user_preferences_types.
				" set att_type_spec='".$att_type_spec."' ".
					(is_null($default_value)?
						"":
						", default_value='".$default_value."'").
				" where att_name='".$att_name."'";
			$res=$this->db->query($q); 
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;

		case 'ldap':
		default:
			die('NOT IMPLEMENTED: '.__FUNCTION__."; container type: ".$this->container_type);
		}
	}
}

?>