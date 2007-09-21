<?php
/**
 * Application unit domain_dns_validator
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_domain_dns_validator.php,v 1.1 2007/09/21 14:21:20 kozlik Exp $
 * @package   serweb
 */ 

/**
 *  Application unit domain_dns_validator
 *
 *
 *  This application unit got domain name from user and check its SRV records
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

class apu_domain_dns_validator extends apu_base_class{
    var $smarty_action='default';

    /** 
     *  return required data layer methods - static class 
     *
     *  @return array   array of required data layer methods
     */
    function get_required_data_layer_methods(){
        return array();
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
    function apu_domain_dns_validator(){
        global $lang_str;
        parent::apu_base_class();

        /* set default values to $this->opt */      
		$this->opt['prohibited_domain_names'] = array();
		$this->opt['set_session_var'] = "";
		$this->opt['redirect_on_update']  = "";

        /* message on attributes update */
        $this->opt['msg_update']['short'] =     &$lang_str['msg_changes_saved_s'];
        $this->opt['msg_update']['long']  =     &$lang_str['msg_changes_saved_l'];
        
        /*** names of variables assigned to smarty ***/
        /* form */
        $this->opt['smarty_form'] =             'form';
        /* smarty action */
        $this->opt['smarty_action'] =       'action';
        /* name of html form */
        $this->opt['form_name'] =           '';
        
        $this->opt['smarty_srv_host'] =           'srv_host';
        $this->opt['smarty_srv_port'] =           'srv_port';
        
    }

    /**
     *  this metod is called always at begining - initialize variables
     */
    function init(){
        parent::init();
    }
    
    /**
     *  Method perform action update
     *
     *  @param array $errors    array with error messages
     *  @return array           return array of $_GET params fo redirect or FALSE on failure
     */

    function action_update(&$errors){
		
        if ($this->opt['set_session_var']){
            $_SESSION[$this->opt['set_session_var']] = $_POST['domainname'];
        }
        
		if ($this->opt['redirect_on_update']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_update']);
		}

        return true;
    }
    
    /**
     *  check _get and _post arrays and determine what we will do 
     */
    function determine_action(){
        if ($this->was_form_submited()){    // Is there data to process?
            $this->action=array('action'=>"update",
                                'validate_form'=>true,
                                'reload'=>true);
        }
        else $this->action=array('action'=>"default",
                                 'validate_form'=>false,
                                 'reload'=>false);
    }
    
    /**
     *  this method is called always after determine_action method
     *
     *  @return none
     */
    function post_determine_action(){
        parent::post_determine_action();
    }

    /**
     *  create html form 
     *
     *  @param array $errors    array with error messages
     *  @return null            FALSE on failure
     */
    function create_html_form(&$errors){
        global $lang_str;
        parent::create_html_form($errors);
        
        $reg = &CReg::singleton();
        $this->f->add_element(array("type"=>"text",
                                     "name"=>"domainname",
                                     "size"=>16,
                                     "maxlength"=>128,
                                     "value"=>"",
                                     "valid_regex"=>"^(".$reg->hostname.")?$",
                                     "valid_e"=>$lang_str['fe_not_valid_domainname']));
        
    }

    /**
     *  validate html form 
     *
     *  @param array $errors    array with error messages
     *  @return bool            TRUE if given values of form are OK, FALSE otherwise
     */
    function validate_form(&$errors){
        global $lang_str, $config, $data;
        if (false === parent::validate_form($errors)) return false;

        /* Check if the domain name is not prohibited */
		if (!empty($_POST['domainname']) and
		    in_array($_POST['domainname'], $this->opt['prohibited_domain_names'])){

			$errors[] = $lang_str['prohibited_domain_name'];
			return false;
		}

        /* Check if domain name does not already exists */
		$o = array('check_deleted_flag' => false,
		           'filter' => array('name' => $_POST['domainname']));

		$data->add_method('get_domain');
		if (false === $domains = $data->get_domain($o)) return false;

        if (count($domains)){
			$errors[] = $lang_str['err_domain_already_hosted'];
			return false;
        }


        /* execute 'host' command */
        $cmd = $config->cmd_host." -t srv ".escapeshellarg("_sip._udp.".$_POST['domainname']);
        exec($cmd, $output, $retval);
        if ($retval != 0){
            ErrorHandler::log_errors(PEAR::raiseError($lang_str['err_cant_run_host_command'], null, null, null,
                                     "Can not execute command: '".$cmd."' return code: ".$retval));
            return false;
        }

        /* Parse output of 'host' command */
        $unrecognized_output = array();
        $srv_ok = false;
        $srv_false_rec = array();
        foreach($output as $v){
            if (!ereg('([0-9]+) ([0-9]+) ([0-9]+) ([-~_.a-zA-Z0-9]+)\\.$', $v, $regs)){
                $unrecognized_output[] = $v;
                continue;
            }
        
            if ($regs[3] == $config->srv_sip_proxy_port and
                $regs[4] == $config->srv_sip_proxy_hostname){
            
                $srv_ok = true;
                break;
            }
            
            $srv_false_rec[] = array("host" => $regs[4],
                                     "port" => $regs[3]);
        }

        if ($srv_ok) return true;

        if ($srv_false_rec){
            $err = $lang_str['err_wrong_srv_record']."\n";

            foreach($srv_false_rec as $v){
                $err .= "host: ".$v['host'].", port: ".$v['port']."\n";
            }
            
            $errors[] = $err;
            return false;
        }

        if ($unrecognized_output){
            $err = $lang_str['err_unrecognized_output_of_host']."\n";

            foreach($unrecognized_output as $v){
                $err .= $v."\n";
            }
            
            $errors[] = $err;
            return false;
        }
        
        ErrorHandler::log_errors(PEAR::raiseError($lang_str['err_no_output_of_host_command']));
        return false;
    }
    
    
    /**
     *  add messages to given array 
     *
     *  @param array $msgs  array of messages
     */
    function return_messages(&$msgs){
        global $_GET;
        
        if (isset($_GET['m_my_apu_updated']) and $_GET['m_my_apu_updated'] == $this->opt['instance_id']){
            $msgs[]=&$this->opt['msg_update'];
            $this->smarty_action="was_updated";
        }
    }

    /**
     *  assign variables to smarty 
     */
    function pass_values_to_html(){
        global $smarty, $config;
        $smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);

        $smarty->assign($this->opt['smarty_srv_host'], $config->srv_sip_proxy_hostname);
        $smarty->assign($this->opt['smarty_srv_port'], $config->srv_sip_proxy_port);

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
