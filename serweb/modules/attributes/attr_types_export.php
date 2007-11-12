<?php
require_once 'XML/Parser/Simple.php';

class attr_types_export{

    function export_to_sql(){

        $at_h = &Attr_types::singleton();
        if (false === $attr_types = $at_h->get_attr_types()) return false;

		Header("Content-Disposition: attachment;filename=".RawURLEncode("attr-types-".date("Y-m-d").".sql"));
        header("Content-type: text/sql");

        foreach ($attr_types as $at){
            echo $this->at_to_sql($at);
        }

        return true;        
    }

    function export_to_xml($dtdfile=""){

        $at_h = &Attr_types::singleton();
        if (false === $attr_types = $at_h->get_attr_types()) return false;

		Header("Content-Disposition: attachment;filename=".RawURLEncode("attr-types-".date("Y-m-d").".xml"));
        header("Content-type: text/xml");

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n";
        if ($dtdfile){
            echo "<!DOCTYPE attr_types SYSTEM \"".$dtdfile."\">\n";
        }
        
        echo "<attr_types>\n";
        foreach ($attr_types as $at){
            echo $this->at_to_xml($at);
        }
        echo "</attr_types>\n";

        return true;        
    }


    function at_to_sql($at){
        global $config, $data;

        /* table's name */
        $t_name = &$config->data_sql->attr_types->table_name;
        /* col names */
        $c = &$config->data_sql->attr_types->cols;

        $type_spec = $at->get_type_spec();
        if ($type_spec){
            $type_spec = serialize($type_spec);
        }
        else{
            $type_spec = "";
        }
    
        $out = "INSERT INTO ".$t_name." ("
                .$c->name.", "
                .$c->rich_type.", "
                .$c->raw_type.", "
                .$c->type_spec.", "
                .$c->desc.", "
                .$c->default_flags.", "
                .$c->flags.", "
                .$c->priority.", "
                .$c->access.", "
                .$c->group.", "
                .$c->order.") VALUES (";

        $out .= $data->sql_format($at->get_name(),            "s").", ";
        $out .= $data->sql_format($at->get_type(),            "s").", ";
        $out .= $data->sql_format($at->get_raw_type(),        "n").", "; 
        $out .= $data->sql_format($type_spec,                 "s").", ";
        $out .= $data->sql_format($at->get_raw_description(), "s").", "; 
        $out .= $data->sql_format($at->get_default_flags(),   "n").", "; 
        $out .= $data->sql_format($at->get_flags(),           "n").", ";
        $out .= $data->sql_format($at->get_priority(),        "n").", ";
        $out .= $data->sql_format($at->get_access(),          "n").", ";
        $out .= $data->sql_format($at->get_group(),           "s").", ";
        $out .= $data->sql_format($at->get_order(),           "n").");\n";
    
        return $out;
    }


    function at_to_xml($attr_type){
    
        $out = "<attr_type>\n";

        $out .= "\t<name>".htmlspecialchars($attr_type->get_name())."</name>\n";
        
        $out .= "\t<type>\n";
        $out .= "\t\t<rich>".htmlspecialchars($attr_type->get_type())."</rich>\n";
        $out .= "\t\t<raw>".htmlspecialchars($attr_type->get_raw_type())."</raw>\n";
        
        $type_spec = $attr_type->get_type_spec();
        if ($type_spec){
            $out .= "\t\t<spec>".htmlspecialchars(serialize($type_spec))."</spec>\n";
        }
        $out .= "\t</type>\n";
        
        $out .= "\t<description>".htmlspecialchars($attr_type->get_raw_description())."</description>\n";

        $default_flags = "";
        if ($attr_type->is_for_ser())    $default_flags .= "\t\t<for_ser/>\n";
        if ($attr_type->is_for_serweb()) $default_flags .= "\t\t<for_serweb/>\n";
        if ($attr_type->is_to_flag())    $default_flags .= "\t\t<is_to/>\n";
        if ($attr_type->is_from_flag())  $default_flags .= "\t\t<is_from/>\n";
        
        if ($default_flags){
            $out .= "\t<default_flags>\n";
            $out .= $default_flags;
            $out .= "\t</default_flags>\n";
        }

        $flags = "";
        if ($attr_type->is_multivalue())    $flags .= "\t\t<multivalue/>\n";
        if ($attr_type->fill_on_register()) $flags .= "\t\t<on_registration/>\n";
        if ($attr_type->is_required())      $flags .= "\t\t<required/>\n";

        if ($flags){
            $out .= "\t<flags>\n";
            $out .= $flags;
            $out .= "\t</flags>\n";
        }

        $priority = "";
        if ($attr_type->is_for_URIs())    $priority .= "\t\t<uri/>\n";
        if ($attr_type->is_for_users())   $priority .= "\t\t<user/>\n";
        if ($attr_type->is_for_domains()) $priority .= "\t\t<domain/>\n";
        if ($attr_type->is_for_globals()) $priority .= "\t\t<global/>\n";

        if ($priority){
            $out .= "\t<priority>\n";
            $out .= $priority;
            $out .= "\t</priority>\n";
        }


        $out .= "\t<access>".htmlspecialchars($attr_type->get_access())."</access>\n";
        $out .= "\t<order>".htmlspecialchars($attr_type->get_order())."</order>\n";
        $out .= "\t<group>".htmlspecialchars($attr_type->get_group())."</group>\n";
        
        
        $out .= "</attr_type>\n";
    
        return $out;
    }

}

class attr_types_import extends XML_Parser_Simple{
    var $attr_types = array();
    var $new_at;
    var $errors = array();
    
    function attr_types_import(){
        parent::XML_Parser_Simple(null, 'event', 'UTF-8');
        $this->folding = true;
    }

    function parse(){
        $this->init();
        return parent::parse();
    }

    function init(){
        $this->new_at = new Attr_type(null, null, null, "", "", 0, 0, 0, 0, 0);

        $this->attr_types = array();
        $this->errors = array();
    }

    function get_attr_types(){
        return $this->attr_types;
    }

    function get_errors(){
        return $this->errors;
    }


    /**
     * handle element
     *
     * @access private
     * @param  string    name of the element
     * @param  array     attributes
     * @param  string    character data
     */
    function handleElement($name, $attribs, $data){
    
        switch($name){
        case "NAME":
            $this->new_at->set_name($data);
            break;
        case "RICH":
            $this->new_at->set_type($data);
            break;
        case "SPEC":
            $ts = "";
            if ($data) $ts = @unserialize($data);
            if (false === $ts) {
                $this->errors[] = "Error in input file on line: ".
                                  xml_get_current_line_number($this->parser).
                                  " Type spec string is not valid.";
                return;
            }
            $this->new_at->set_type_spec($ts);
            break;
        case "DESCRIPTION":
            $this->new_at->set_description($data);
            break;

        case "FOR_SERWEB":
            $this->new_at->set_for_serweb();
            break;
        case "FOR_SER":
            $this->new_at->set_for_ser();
            break;
        case "IS_TO":
            $this->new_at->set_to_flag();
            break;
        case "IS_FROM":
            $this->new_at->set_from_flag();
            break;

        case "URI":
            $this->new_at->set_for_URIs();
            break;
        case "USER":
            $this->new_at->set_for_users();
            break;
        case "DOMAIN":
            $this->new_at->set_for_domains();
            break;
        case "GLOBAL":
            $this->new_at->set_for_globals();
            break;
            
        case "MULTIVALUE":
            $this->new_at->set_multivalue();
            break;
        case "ON_REGISTRATION":
            $this->new_at->set_registration();
            break;
        case "REQUIRED":
            $this->new_at->set_required();
            break;

        case "ACCESS":
            $this->new_at->set_access($data);
            break;
        case "ORDER":
            $this->new_at->set_order($data);
            break;
        case "GROUP":
            $this->new_at->set_group($data);
            break;

        case "ATTR_TYPE":
            $this->attr_types[] = $this->new_at;
            
            $this->new_at = new Attr_type(null, null, null, "", "", 0, 0, 0, 0, 0);
            
            break;

        /* unhandled tags */
        case "RAW":
        case "TYPE":
        case "DEFAULT_FLAGS":
        case "PRIORITY":
        case "FLAGS":
        case "ATTR_TYPES":
            break;

            
        default:
            printf('unhandled handle %s<br>', $name);
        }
    
    }


}

?>
