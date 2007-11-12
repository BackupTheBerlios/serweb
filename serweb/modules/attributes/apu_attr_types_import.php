<?php
/**
 * Application unit apu_attr_types_import
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_attr_types_import.php,v 1.1 2007/11/12 12:45:06 kozlik Exp $
 * @package   serweb
 */ 

require_once "attr_types_export.php";

/**
 *  Application unit apu_attr_types_import
 *
 *
 *  This application unit is used for import attribute types
 *     
 *  Configuration:
 *  --------------
 *  
 *  'msg_update'                    default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
 *   message which should be showed on attributes update - assoc array with keys 'short' and 'long'
 *                              
 *  'form_name'                     (string) default: ''
 *   name of html form
 *  
 *  'form_submit'               (assoc)
 *   assotiative array describe submit element of form. For details see description 
 *   of method add_submit in class form_ext
 *  
 *  'smarty_form'               name of smarty variable - see below
 *  'smarty_action'                 name of smarty variable - see below
 *  
 *  Exported smarty variables:
 *  --------------------------
 *  opt['smarty_form']              (form)          
 *   phplib html form
 *   
 *  opt['smarty_action']            (action)
 *    tells what should smarty display. Values:
 *    'default' - 
 *    'was_updated' - when user submited form and data was succefully stored
 *  
 */

class apu_attr_types_import extends apu_base_class{
    var $smarty_action='default';

    /** 
     *  return required data layer methods - static class 
     *
     *  @return array   array of required data layer methods
     */
    function get_required_data_layer_methods(){
        return array('update_attr_type', 'del_attr_type');
    }

    /**
     *  return array of strings - required javascript files 
     *
     *  @return array   array of required javascript files
     */
    function get_required_javascript(){
        return array();
    }
    
    /**
     *  constructor 
     *  
     *  initialize internal variables
     */
    function apu_attr_types_import(){
        global $lang_str;
        parent::apu_base_class();

        /* set default values to $this->opt */      
        $this->opt['redirect_on_import']  = "";


        /* message on attributes import */
        $this->opt['msg_import']['short'] =     &$lang_str['msg_at_imported_s'];
        $this->opt['msg_import']['long']  =     &$lang_str['msg_at_imported_l'];
        
        /*** names of variables assigned to smarty ***/
        /* form */
        $this->opt['smarty_form'] =             'form';
        /* smarty action */
        $this->opt['smarty_action'] =       'action';
        /* name of html form */
        $this->opt['form_name'] =           '';
        
        
    }

    /**
     *  this metod is called always at begining - initialize variables
     */
    function init(){
        parent::init();
    }
    
    /**
     *  Method perform action import
     *
     *  @param array $errors    array with error messages
     *  @return array           return array of $_GET params fo redirect or FALSE on failure
     */

    function action_import(&$errors){
        global $data;
    
        $import_obj = new attr_types_import();
        
        $result = $import_obj->setInputFile($_FILES['at_file']['tmp_name']);
		if (PEAR::isError($result)) { ErrorHandler::log_errors($result); return false; }
		
        $result = $import_obj->parse();
		if (PEAR::isError($result)) { ErrorHandler::log_errors($result); return false; }

        if ($err = $import_obj->get_errors()){
            ErrorHandler::add_error($err);
            return false;
        }
    
        $new_at = $import_obj->get_attr_types();


        $at_h = &Attr_types::singleton();
        if (false === $current_at = $at_h->get_attr_types()) return false;


        $current_at_names = array_keys($current_at);


        if (!empty($_POST['at_purge'])){
            /* purge old attribute types */
            foreach($current_at_names as $v){
                if (false === $data->del_attr_type($v, null)) return false;
            }    
        }

        foreach($new_at as $v){
        
            if (empty($_POST['at_purge']) and 
                in_array($v->get_name(), $current_at_names)){
                
                /* if current attribute already exists */
                
                if ($_POST['at_exists'] == "update"){
                    if (false === $data->del_attr_type($v->get_name(), null)) return false;
                }
                else {
                       continue;
                }
            }

            /* store attr type to DB */
            if (false === $data->update_attr_type($v, null, null)) return false;
        }

    
        if ($this->opt['redirect_on_import']){
            $this->controler->change_url_for_reload($this->opt['redirect_on_import']);
        }

        $get = array('at_imported='.RawURLEncode($this->opt['instance_id']));
        return $get;
    }
    
    /**
     *  check _get and _post arrays and determine what we will do 
     */
    function determine_action(){
        if ($this->was_form_submited()){    // Is there data to process?
            $this->action=array('action'=>"import",
                                'validate_form'=>true,
                                'reload'=>true);
        }
        else $this->action=array('action'=>"default",
                                 'validate_form'=>false,
                                 'reload'=>false);
    }
    
    /**
     *  create html form 
     *
     *  @param array $errors    array with error messages
     *  @return null            FALSE on failure
     */
    function create_html_form(&$errors){
        parent::create_html_form($errors);

        $ex_opts = array(
                    array("value" => "update", "label" => "Update current attribute type"),
                    array("value" => "skip",   "label" => "Replace current attribute type")
                );

        $js = ' if (this.checked){
                    this.form.at_exists[0].disabled=true;
                    this.form.at_exists[1].disabled=true;
                }
                else{
                    this.form.at_exists[0].disabled=false;
                    this.form.at_exists[1].disabled=false;
                } ';

		$this->f->add_element(array("type"=>"file",
		                            "name"=>"at_file",
		                            "value"=>""));

		$this->f->add_element(array("type"=>"checkbox",
		                            "name"=>"at_purge",
		                            "value"=>"1",
                                    "extra_html"=>"onclick='".$js."'"));

		$this->f->add_element(array("type"=>"radio",
		                            "name"=>"at_exists",
		                            "value"=>"skip",
		                            "options"=>$ex_opts));


    }

    /**
     *  validate html form 
     *
     *  @param array $errors    array with error messages
     *  @return bool            TRUE if given values of form are OK, FALSE otherwise
     */
    function validate_form(&$errors){
        global $lang_str;
        if (false === parent::validate_form($errors)) return false;

		if ($_FILES['at_file']['error'] == UPLOAD_ERR_FORM_SIZE or 
			$_FILES['at_file']['error'] == UPLOAD_ERR_INI_SIZE){
			$errors[]=$lang_str['fe_file_too_big'];
			return false;
		}

		if (!is_uploaded_file($_FILES['at_file']['tmp_name'])){
			$errors[]=$lang_str['fe_at_no_xml_file'];
			return false;
		}

		if (filesize($_FILES['at_file']['tmp_name'])==0){
			$errors[]=$lang_str['fe_at_invalid_sml_file'];
			return false;
		}

		if ($_FILES['at_file']['type'] != "text/xml"){
			$errors[]=$lang_str['fe_at_xml_file_type'];
			return false;
		}

        return true;
    }
    
    
    /**
     *  add messages to given array 
     *
     *  @param array $msgs  array of messages
     */
    function return_messages(&$msgs){
        global $_GET;
        
        if (isset($_GET['at_imported']) and $_GET['at_imported'] == $this->opt['instance_id']){
            $msgs[]=&$this->opt['msg_import'];
        }
    }

    /**
     *  assign variables to smarty 
     */
    function pass_values_to_html(){
        global $smarty;
        $smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
    }
    
    /**
     *  return info need to assign html form to smarty 
     */
    function pass_form_to_html(){
        return array('smarty_name' => $this->opt['smarty_form'],
                     'form_name'   => $this->opt['form_name'],
                     'after'       => '',
                     'before'      => '');
    }
}

?>
