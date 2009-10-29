<?php
/**
 *  @author     Karel Kozlik
 *  @version    $Id: method.rename_attr_type_group.php,v 1.1 2009/10/29 13:01:05 kozlik Exp $
 *  @package    serweb
 *  @subpackage mod_attributes
 */ 

/**
 *  Data layer container holding the method for update group of attribute type
 * 
 *  @package    serweb
 *  @subpackage mod_attributes
 */ 
class CData_Layer_rename_attr_type_group {
    var $required_methods = array();
    
    /**
     *  Update attribute type group
     *
     *  On error this method returning FALSE.
     *
     *  Possible options:
     *   - none
     *  
     *  @param  string      $old_group_name   Name of group to rename
     *  @param  string      $new_group_name   New name for the group
     *  @param  array       $opt        Array of options
     *  @return bool
     */ 
     
    function rename_attr_type_group($old_group_name, $new_group_name, $opt){
        global $config;
        
        $errors = array();
        if (!$this->connect_to_db($errors)) {
            ErrorHandler::add_error($errors);
            return false;
        }

        /* table's name */
        $t_name = &$config->data_sql->attr_types->table_name;
        /* col names */
        $c = &$config->data_sql->attr_types->cols;

        $q = "update ".$t_name." 
              set   ".$c->group." = ".$this->sql_format($new_group_name, "s")."
              where ".$c->group." = ".$this->sql_format($old_group_name, "s");

        $res=$this->db->query($q);
        if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

        return true;
    }
}
?>
