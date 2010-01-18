<?php
/**
 *  Application unit URI - user 
 * 
 *  @author    Karel Kozlik
 *  @version   $Id: apu_uri_user.php,v 1.1 2010/01/18 15:02:14 kozlik Exp $
 *  @package   serweb
 *  @subpackage mod_uri
 */ 


/**
 *  Application unit URI - user 
 *
 *
 *  This application unit is used for display and edit URI. It's intended to be 
 *  used by users itself for self provisioning of URI's.
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

class apu_uri_user extends apu_base_class{
    var $sorter=null;
    var $filter=null;
    var $smarty_uris;
    var $edit;
    var $pager;
    var $edit_uri;
    var $js_after="";
    var $js_before="";

    /** 
     *  return required data layer methods - static class 
     *
     *  @return array   array of required data layer methods
     */
    function get_required_data_layer_methods(){
        return array("get_uris", "add_uri", "delete_uri", "update_uri", 
                     "get_new_alias_number");
    }

    /**
     *  return array of strings - required javascript files 
     *
     *  @return array   array of required javascript files
     */
    function get_required_javascript(){
		return array('functions.js',
                     'get_js.php?mod=uri&js=aliases.js');
    }
    
    /**
     *  constructor 
     *  
     *  initialize internal variables
     */
    function apu_uri_user(){
        global $lang_str;
        parent::apu_base_class();

        /* set default values to $this->opt */      
        $this->opt['max_records_per_page_fn'] = null;

        $this->opt['perm_edit'] = true;
        $this->opt['perm_insert'] = true;
        $this->opt['perm_delete'] = true;

        $this->opt['screen_name'] = "URI self provisioning";

        $this->opt['max_uris'] = null;
        $this->opt['allowed_domains'] = null;

        /* message on attributes update */
        $this->opt['msg_update']['short'] =     &$lang_str['msg_uri_updated_s'];
        $this->opt['msg_update']['long']  =     &$lang_str['msg_uri_updated_l'];
        $this->opt['msg_create']['short'] =     &$lang_str['msg_uri_created_s'];
        $this->opt['msg_create']['long']  =     &$lang_str['msg_uri_created_l'];
        $this->opt['msg_delete']['short'] =     &$lang_str['msg_uri_deleted_s'];
        $this->opt['msg_delete']['long']  =     &$lang_str['msg_uri_deleted_l'];
        
        /*** names of variables assigned to smarty ***/
        /* form */
        $this->opt['smarty_form'] =         'form';
        /* smarty action */
        $this->opt['smarty_action'] =       'action';
        /* name of html form */
        $this->opt['form_name'] =           'uriForm';
        /* pager */
        $this->opt['smarty_pager'] =        'pager';
        
        $this->opt['smarty_uris'] =         'uris';
        
        $this->opt['smarty_url_insert'] =       'url_insert';
		$this->opt['smarty_url_uri_suggest'] =  'url_uri_suggest';
		$this->opt['smarty_url_uri_generate'] = 'url_uri_generate';

    }

    function set_filter(&$filter){
        $this->filter = &$filter;
    }

    function set_sorter(&$sorter){
        $this->sorter = &$sorter;
    }

    /**
     *  this metod is called always at begining - initialize variables
     */
    function init(){
        parent::init();

        if (!isset($_SESSION['apu_uri_user'][$this->opt['instance_id']])){
            $_SESSION['apu_uri_user'][$this->opt['instance_id']] = array();
        }
        
        $this->session = &$_SESSION['apu_uri_user'][$this->opt['instance_id']];
        
        if (!isset($this->session['smarty_action'])){
            $this->session['smarty_action'] = 'default';
        }

        if (is_a($this->sorter, "apu_base_class")){
            /* register callback called on sorter change */
            $this->sorter->set_opt('on_change_callback', array(&$this, 'sorter_changed'));
            $this->sorter->set_base_apu($this);
        }

        if (is_a($this->filter, "apu_base_class")){
            $this->filter->set_base_apu($this);
        }
        else{
            if (!isset($this->session['act_row'])){
                $this->session['act_row'] = 0;
            }
            
            if (isset($_GET['act_row'])) $this->session['act_row'] = $_GET['act_row'];
        }
    }
    
    /**
     *  callback function called when sorter is changed
     */
    function sorter_changed(){
        if (is_a($this->filter, "apu_base_class")){
            $this->filter->set_act_row(0);
        }
        else{
            $this->session['act_row'] = 0;
        }
    }

    function get_sorter_columns(){
        return array('uri', 'username', 'did');
    }
    
    function get_filter_form(){
        global $lang_str;
        
        $f = array();

        $f[] = array("type"=>"text",
                     "name"=>"username",
                     "label"=>$lang_str['tcssr_rs_prefix']);

        $f[] = array("type"=>"text",
                     "name"=>"did",
                     "label"=>$lang_str['tcssr_rs_invert']);

        return $f;
    }
    

    /**
     *  Method perform action create
     *
     *  @param array $errors    array with error messages
     *  @return array           return array of $_GET params fo redirect or FALSE on failure
     */

    function action_create(&$errors){
        global $data, $config;

		$data->transaction_start();

		/* if flag 'IS_CANON' is set in this URI, clear this flag in all other 
		 * URIs of that user 
		 */
		if ($_POST['uri_is_canon']){
			if (false === $this->clear_canon_flag()) {
				$data->transaction_rollback();
				return false;
			}
		}

        $uid = $this->controler->user_id->get_uid();
        $scheme = "sip";
        $uname =  $_POST['uri_un'];
        $did =    $_POST['uri_did'];
        $opt = array("canon" => $_POST['uri_is_canon']);

        if (false === $data->add_uri($uid, $scheme, $uname, $did, $opt)) {
			$data->transaction_rollback();
            return false;     
        }

		$data->transaction_commit();

        action_log($this->opt['screen_name'], $this->action, "Insert URI ".$uname." did: ".$did);
        
        $get = array('uri_created='.RawURLEncode($this->opt['instance_id']));
        return $get;
    }
    
    /**
     *  Method perform action update
     *
     *  @param array $errors    array with error messages
     *  @return array           return array of $_GET params fo redirect or FALSE on failure
     */

    function action_update(&$errors){
        global $data, $config;

        $f = &$config->data_sql->uri->flag_values;


		$data->transaction_start();

		/* if flag 'IS_CANON' is set in this URI, clear this flag in all other 
		 * URIs of that user 
		 */
		if ($_POST['uri_is_canon']){
			if (false === $this->clear_canon_flag($uid)) {
				$data->transaction_rollback();
				return false;
			}
		}

        $old_v = array('uid' =>      $this->controler->user_id->get_uid(),
                       'did' =>      $this->edit['did'],
                       'username' => $this->edit['un'],
                       'flags' =>    $this->edit['flags']);

        if ($_POST['uri_is_canon'])  $new_flags = ($this->edit['flags'] | $f['DB_CANON']);
        else                         $new_flags = ($this->edit['flags'] & !$f['DB_CANON']); 
    
        $new_v = array('did' =>      $_POST['uri_did'],
                       'username' => $_POST['uri_un'],
                       'flags' =>    $new_flags);

        if (false === $data->update_uri($new_v, $old_v, null)) {
			$data->transaction_rollback();
            return false;
        }

		$data->transaction_commit();

        action_log($this->opt['screen_name'], $this->action, "Edit URI; username:".$this->edit['un']." did: ".$this->edit['did']);
        
        $get = array('uri_updated='.RawURLEncode($this->opt['instance_id']));
        return $get;
    }

    /**
     *  Method perform action delete
     *
     *  @param array $errors    array with error messages
     *  @return array           return array of $_GET params fo redirect or FALSE on failure
     */

    function action_delete(&$errors){
        global $data, $config;


        $opt = array();
        if (false === $data->delete_uri($this->controler->user_id->get_uid(),
                                        $this->edit['scheme'], 
                                        $this->edit['un'],
                                        $this->edit['did'],
                                        $this->edit['flags'],
                                        $opt)) return false;      


        action_log($this->opt['screen_name'], $this->action, "Delete URI; username:".$this->edit['un']." did: ".$this->edit['did']);
        
        $get = array('uri_deleted='.RawURLEncode($this->opt['instance_id']));
        return $get;
    }
    
    /**
     *  Method perform action insert
     *
     *  @param array $errors    array with error messages
     *  @return array           return array of $_GET params fo redirect or FALSE on failure
     */

    function action_insert(&$errors){
        $this->session['smarty_action'] = 'insert';

        action_log($this->opt['screen_name'], $this->action, "display URI insert screen");
        
        return true;
    }

    /**
     *  Method perform action edit
     *
     *  @param array $errors    array with error messages
     *  @return array           return array of $_GET params fo redirect or FALSE on failure
     */

    function action_edit(&$errors){
        $this->session['smarty_action'] = 'edit';

        action_log($this->opt['screen_name'], $this->action, "display URI edit screen");
        
        return true;
    }

    
    /**
     *  Method perform action default 
     *
     *  @param array $errors    array with error messages
     *  @return array           return array of $_GET params fo redirect or FALSE on failure
     */

    function action_default(&$errors){
        global $data, $sess, $config;

        $this->session['smarty_action'] = 'default';

        $opt = array('use_pager' => true);

        if (is_a($this->filter, "apu_base_class")){
            $opt['filter'] = $this->filter->get_filter();
            $data->set_act_row($this->filter->get_act_row());
        }
        else{
            $data->set_act_row($this->session['act_row']);
        }
        if (is_a($this->sorter, "apu_base_class")){
            $opt['order_by']   = $this->sorter->get_sort_col();
            $opt['order_desc'] = $this->sorter->get_sort_dir();
        }


        if ($this->opt['max_records_per_page_fn']){
            if (false === $r = call_user_func($this->opt['max_records_per_page_fn'])) return false;
            $data->set_showed_rows($r);
        }

        if (false === $uris = $data->get_uris($this->controler->user_id->get_uid(), $opt)) return false;

        $this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
        $this->pager['pos']=$data->get_act_row();
        $this->pager['items']=$data->get_num_rows();
        $this->pager['limit']=$data->get_showed_rows();
        $this->pager['from']=$data->get_res_from();
        $this->pager['to']=$data->get_res_to();

        $opt = null;
        $this->smarty_uris = array();
        foreach ($uris as $k => $v){
            if (false === $sm_uri = $v->to_smarty($opt)) return false;

            $query_string =  "uri_un=".RawURLEncode($v->get_username()).
                            "&uri_did=".RawURLEncode($v->get_did()).
                            "&uri_flags=".RawURLEncode($v->get_flags());

            $sm_uri['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&edit=1&".$query_string);
            $sm_uri['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&delete=1&".$query_string);

            $sm_uri['edit_allowed'] = $this->is_edit_allowed($v->get_username(), $v->get_did());
            
            $this->smarty_uris[] = $sm_uri;
        }
        
        if ($this->was_form_canceled()){
            action_log($this->opt['screen_name'], $this->action, "data form has been canceled", true, array('cancel'=>true));
        }
        action_log($this->opt['screen_name'], $this->action, "View URIs");

        return true;
    }
    
    function action_generate_query(&$errors){
        global $data;

        $this->controler->disable_html_output();
        header("Content-Type: text/plain");

        $did = $_GET['al_did'];

        // check whether user have access to the did of the uri
        if (!$this->check_did($did)) return true; //finish the action execution

		// generate numeric alias 
		if (false === $alias_uname=$data->get_new_alias_number($did, null)) {
			return false;
		}

        $response = new stdClass();
        $response->uri_uname = $alias_uname;

        echo my_JSON_encode($response);
        return true;
    }
    
    function action_usage_query(&$errors){
        global $data;

        $this->controler->disable_html_output();
        header("Content-Type: text/plain");

        $uname = $_GET['al_uname'];
        $did = $_GET['al_did'];

        // check whether user have access to the did of the uri
        if (!$this->check_did($did)) return true; //finish the action execution

        $uris_handler = &URIS::singleton_2($uname, $did);
        if (false === $uris = $uris_handler->get_URIs()) return false;

        $response = new stdClass();
        $response->uri_used = (count($uris) > 0) ? true : false;

        echo my_JSON_encode($response);
        return true;
    }
    
    function action_suggest_query(&$errors){

        $this->controler->disable_html_output();
        header("Content-Type: text/plain");

        $uname = $_GET['al_uname'];
        $did = $_GET['al_did'];

        // check whether user have access to the did of the uri
        if (!$this->check_did($did)) return true; //finish the action execution

        if (false === $uris = URI_functions::suggest_uri($uname, $did)) return false;

        $response = new stdClass();
        $response->suggested_uris = $uris;

        echo my_JSON_encode($response);
        return true;
    }

    /**
     *  check _get and _post arrays and determine what we will do 
     */
    function determine_action(){
        if ($this->was_form_submited()){    // Is there data to process?
            if (isset($_POST['uri_un_edit']) and $this->opt['perm_edit']){
                $this->action=array('action'=>"update",
                                    'validate_form'=>true,
                                    'reload'=>true);
                $this->edit['scheme']= "sip";
                $this->edit['un']    = $_POST['uri_un_edit'];
                $this->edit['did']   = $_POST['uri_did_edit'];
                $this->edit['flags'] = $_POST['uri_flags_edit'];
            }
            elseif ($this->opt['perm_insert']){
                $this->action=array('action'=>"create",
                                    'validate_form'=>true,
                                    'reload'=>true);
            }
            else $this->action=array('action'=>"default",
                                     'validate_form'=>false,
                                     'reload'=>false);
        }
        elseif (isset($_GET['insert']) and $this->opt['perm_insert']){
            $this->action=array('action'=>"insert",
                                 'validate_form'=>true,
                                 'reload'=>false);
        }
        elseif (isset($_GET['edit']) and $this->opt['perm_edit']){
            $this->action=array('action'=>"edit",
                                 'validate_form'=>true,
                                 'reload'=>false);
            $this->edit['scheme']= "sip";
            $this->edit['un']    = $_GET['uri_un'];
            $this->edit['did']   = $_GET['uri_did'];
            $this->edit['flags'] = $_GET['uri_flags'];
        }
        elseif (isset($_GET['delete']) and $this->opt['perm_delete']){
            $this->action=array('action'=>"delete",
                                 'validate_form'=>true,
                                 'reload'=>true);
            $this->edit['scheme']= "sip";
            $this->edit['un']    = $_GET['uri_un'];
            $this->edit['did']   = $_GET['uri_did'];
            $this->edit['flags'] = $_GET['uri_flags'];
        }
		elseif (isset($_GET['uri_usage'])){
			$this->action = array('action'=>"usage_query",
			                      'validate_form'=>false,
			                      'reload'=>false,
                                  'alone'=>true);
		}
		elseif (isset($_GET['uri_suggest'])){
			$this->action = array('action'=>"suggest_query",
			                      'validate_form'=>false,
			                      'reload'=>false,
                                  'alone'=>true);
		}
		elseif (isset($_GET['uri_generate'])){
			$this->action = array('action'=>"generate_query",
			                      'validate_form'=>false,
			                      'reload'=>false,
                                  'alone'=>true);
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
        parent::create_html_form($errors);

        global $lang_str, $data, $config, $sess;
 
        $f = &$config->data_sql->uri->flag_values;

        $domains = &Domains::singleton();
        if (false === $this->domain_names = $domains->get_id_name_pairs()) return false;

        /* create a list of allowed domain names for the form */
        if (is_array($this->opt['allowed_domains'])){
            $alowed_domain_names = array();         
            foreach ($this->opt['allowed_domains'] as $v){
                $alowed_domain_names[$v] = $this->domain_names[$v];
            }
        }
        else{
            $alowed_domain_names = &$this->domain_names;
        }

        $dom_options = array();
        foreach ($alowed_domain_names as $k => $v) 
            $dom_options[]=array("label"=>$v, "value"=>$k);

        $reg = &Creg::singleton();



        if ($this->action['action'] == "edit" or
            $this->action['action'] == "update"){

            $opt = array();
            $opt['filter']['username'] = new Filter("username", $this->edit['un'], "=", false, false);
            $opt['filter']['did']      = new Filter("did", $this->edit['did'], "=", false, false);
            $opt['filter']['flags']    = new Filter("flags", $this->edit['flags'], "=", false, false);
            $opt['filter']['scheme']   = new Filter("scheme", $this->edit['scheme'], "=", false, false);
            if (false === $uri = $data->get_uris($this->controler->user_id->get_uid(), $opt)) return false;

            if (!count($uri)){
                $uri =  new URI($this->controler->user_id->get_uid(),
                                "", "", $f['DB_CANON']);
                $errors[] = $lang_str['tcssr_err_rs_prefix_to_edit_not_found'];
            }else{
                //get first (and only) element of array
                $uri = reset($uri);
            }
            
            $this->edit_uri = &$uri;
            $edit = true;
        }
        else{
            $uri =  new URI($this->controler->user_id->get_uid(),
                            "", "", &$config->data_sql->uri->flag_values['DB_CANON']);
            $this->edit_uri = &$uri;
            $edit = false;
        }


        $this->f->add_element(array("type"=>"text",
                                     "name"=>"uri_un",
                                     "value"=>$this->edit_uri->username,
                                     "js_trim_value" => true,
                                     "size"=>16,
                                     "maxlength"=>64,
                                     "minlength"=>1,
                                     "valid_regex"=>"^".$reg->user."$",
                                     "valid_e"=>$lang_str['fe_not_valid_username'],
                                     "length_e"=>$lang_str['fe_not_filled_username'],
                                     "value"=>$this->edit_uri->username,
                                     "extrahtml"=>"onkeyup='alias_ctl.onAliasChange();' oncut='alias_ctl.onAliasChange();' onpaste='alias_ctl.onAliasChange();'"));

        $this->f->add_element(array("type"=>"select",
                                     "name"=>"uri_did",
                                     "options"=>$dom_options,
                                     "value"=>$this->edit_uri->did,
                                     "size"=>1,
                                     "extrahtml"=>"onchange='alias_ctl.onAliasChange();' onkeyup='alias_ctl.onAliasChange();'"));

        $this->f->add_element(array("type"=>"checkbox",
                                     "name"=>"uri_is_canon",
                                     "checked"=>(bool)($this->edit_uri->flags & $f['DB_CANON']),
                                     "value"=>1));


        if ($this->action['action'] == "edit" or
            $this->action['action'] == "update"){

            $this->f->add_element(array("type"=>   "hidden",
                                         "name"=>  "uri_un_edit",
                                         "value"=> $this->edit_uri->username));
        
            $this->f->add_element(array("type"=>   "hidden",
                                         "name"=>  "uri_did_edit",
                                         "value"=> $this->edit_uri->did));

            $this->f->add_element(array("type"=>   "hidden",
                                         "name"=>  "uri_flags_edit",
                                         "value"=> $this->edit_uri->flags));
        }

        $onload_js = "
            var alias_ctl;
            alias_ctl = new Aliases_ctl('alias_ctl');
            alias_ctl.init('".$this->opt['form_name']."', 'uri_un', 'uri_did');
            alias_ctl.onAliasChangeUrl='".$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&uri_usage=1")."';
            alias_ctl.aliasSuggestUrl='".$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&uri_suggest=1")."';
            alias_ctl.aliasGenerateUrl='".$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&uri_generate=1")."';
            alias_ctl.lang_str.no_suggestions='".js_escape($lang_str['no_suggestions'])."'                
        ";

        $this->controler->set_onload_js($onload_js);

    }

    function form_invalid(){
        if ($this->action['action'] == "delete"){
            if (false === $this->action_default($errors)) return false;
            action_log($this->opt['screen_name'], $this->action, "Delete URI failed", false, array("errors"=>$this->controler->errors));
        }
        elseif ($this->action['action'] == "insert"){
            if (false === $this->action_default($errors)) return false;
            action_log($this->opt['screen_name'], $this->action, "Insert URI failed", false, array("errors"=>$this->controler->errors));
        }
        elseif ($this->action['action'] == "create"){
            action_log($this->opt['screen_name'], $this->action, "Insert URI failed ".$_POST['uri_un']." did: ".$_POST['uri_did'], false, array("errors"=>$this->controler->errors));
        }
        elseif ($this->action['action'] == "edit"){
            if (false === $this->action_default($errors)) return false;
            action_log($this->opt['screen_name'], $this->action, "Edit URI failed ".$this->edit_uri->username." did: ".$this->edit_uri->did, false, array("errors"=>$this->controler->errors));
        }
        elseif ($this->action['action'] == "update"){
            action_log($this->opt['screen_name'], $this->action, "Edit URI failed ".$this->edit_uri->username." did: ".$this->edit_uri->did, false, array("errors"=>$this->controler->errors));
        }
    }

    /**
     *  validate html form 
     *
     *  @param array $errors    array with error messages
     *  @return bool            TRUE if given values of form are OK, FALSE otherwise
     */
    function validate_form(&$errors){
        global $lang_str, $data, $config;

        
        if ($this->action['action'] == "insert"){
            if (!$this->validate_number_of_entries($errors)) return false;
            return true;
        }

        if ($this->action['action'] == 'create'){
            if (!$this->validate_number_of_entries($errors)) return false;
        }

        if ($this->action['action'] == 'edit' or
            $this->action['action'] == 'update' or
            $this->action['action'] == 'delete'){
        
            if (!$this->is_edit_allowed($this->edit['un'], $this->edit['did'])){
                $errors[] = $lang_str['err_uri_modify_not_permited']; 
                return false;
            }
        }

        if ($this->action['action'] == 'edit' or
            $this->action['action'] == 'delete'){
            return true;
        }
        
        $form_ok = true;
        if (false === parent::validate_form($errors)) $form_ok = false;

        if (!isset($_POST['uri_is_canon'])) $_POST['uri_is_canon'] = 0; 


        if (!$this->check_did($_POST['uri_did'])){
            $d = &Domain::singleton();
            $errors[] = "Operation failed: You haven't access to domain: ".$d->get_domain_name($did); 
            return false;
        }
        
        if ($_POST['uri_is_canon']){
            if (!$this->check_canon_flag()) return false;
        }

        /* Check wheteher URI is unique */
        $opt = array();
        $opt['filter']['username'] = new Filter("username", $_POST['uri_un'], "=", false, false);
        $opt['filter']['did']      = new Filter("did", $_POST['uri_did'], "=", false, false);
        $opt['filter']['scheme']   = new Filter("scheme", "sip", "=", false, false);


        if (false === $dups = $data->get_uris(null, $opt)) return false;
        if ($dups){
            $dup = reset($dups);
            if ($this->action['action'] == "update" and count($dups)==1 and
                $dup->username == $this->edit_uri->username and
                $dup->did == $this->edit_uri->did and
                $dup->scheme == $this->edit_uri->scheme and
                $dup->uid == $this->edit_uri->uid){
                
                
            }
            else{
                $errors[] = $lang_str['err_ri_dup'];
                $form_ok = false;
            }
        }

        return $form_ok;
    }
    
    function validate_number_of_entries(&$errors){
        global $data, $lang_str;
        
        if (!empty($this->opt['max_uris'])){
            $opt = array('count_only' => true);
            if (false === $cnt = $data->get_uris($this->controler->user_id->get_uid(), $opt)) return false;
            
            if ($cnt >= $this->opt['max_uris']){
                $errors[] = $lang_str['err_uri_limit_reached'];
                return false;
            }
        }
        
        return true;
    }

    function check_did($did){
        if (is_array($this->opt['allowed_domains'])){
            if (!in_array($did, $this->opt['allowed_domains'])) return false;
        }
        
        return true;
    }
    
    function is_edit_allowed($uname, $did){
        global $data;

        // check whether user have access to the did of the uri
        if (!$this->check_did($did))    return false;
    
        $opt = array();
        $opt['filter']['username'] = new Filter("username", $uname, "=", false, false);
        $opt['filter']['did']      = new Filter("did", $did, "=", false, false);
        $opt['filter']['scheme']   = new Filter("scheme", "sip", "=", false, false);
        $opt['count_only'] = true;

        if (false === $cnt = $data->get_uris(null, $opt)) return false;

        // if the URI is used by more subscribers, disallow the edit for users
        if ($cnt > 1) return false;
        
        return true;                
    }


	function check_canon_flag(){
		global $data, $config, $lang_str;

		$f = &$config->data_sql->uri->flag_values;

        $uid = $this->controler->user_id->get_uid();
		$uris_handler = &URIS::singleton($uid);
		if (false === $uris = $uris_handler->get_URIs()) return false;

		foreach($uris as $k=>$v){

			if ($v->is_canonical()) {
    			/* skip URI edited just now */
    			if ($this->action['action']=='update' and
    			    $uris[$k]->get_scheme() == 'sip' and
    			    $uris[$k]->get_did() == $this->edit['did'] and
    				$uris[$k]->get_username() == $this->edit['un']) continue;

				if (!$this->is_edit_allowed($v->get_username(), $v->get_did())){
					ErrorHandler::add_error($lang_str['err_canon_uri_exists']);
					return false;
				}
			}
		}
		
		return true;
	}

    
	function clear_canon_flag(){
		global $data, $config, $lang_str;

		$f = &$config->data_sql->uri->flag_values;

        $uid = $this->controler->user_id->get_uid();
		$uris_handler = &URIS::singleton($uid);
		if (false === $uris = $uris_handler->get_URIs()) return false;

		foreach($uris as $k=>$v){
			if ($v->is_canonical()) {
    			/* do not affect URI which is currently changed */
    			if ($this->action['action']=='update' and
    			    $uris[$k]->get_scheme() == 'sip' and
    			    $uris[$k]->get_did() == $this->edit['did'] and
    				$uris[$k]->get_username() == $this->edit['un']) continue;

				if (!$this->is_edit_allowed($v->get_username(), $v->get_did())){
					ErrorHandler::add_error($lang_str['err_canon_uri_exists']);
					return false;
				}
				$old_v = array('uid' => $v->get_uid(),
				               'did' => $v->get_did(),
							   'username' => $v->get_username(),
							   'flags' => $v->get_flags());
			
				$new_v = array('flags' => $v->get_flags() & ~$f['DB_CANON']);

				if (false === $data->update_uri($new_v, $old_v, null)) return false;
			}
		}
	}

    
    /**
     *  add messages to given array 
     *
     *  @param array $msgs  array of messages
     */
    function return_messages(&$msgs){
        global $_GET;
        
        if (isset($_GET['uri_updated']) and $_GET['uri_updated'] == $this->opt['instance_id']){
            $msgs[]=&$this->opt['msg_update'];
        }
        if (isset($_GET['uri_created']) and $_GET['uri_created'] == $this->opt['instance_id']){
            $msgs[]=&$this->opt['msg_create'];
        }
        if (isset($_GET['uri_deleted']) and $_GET['uri_deleted'] == $this->opt['instance_id']){
            $msgs[]=&$this->opt['msg_delete'];
        }

    }

    /**
     *  assign variables to smarty 
     */
    function pass_values_to_html(){
        global $smarty, $sess;

        $smarty->assign_by_ref($this->opt['smarty_action'], $this->session['smarty_action']);
        $smarty->assign_by_ref($this->opt['smarty_uris'], $this->smarty_uris);
        $smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);

        $smarty->assign($this->opt['smarty_url_insert'], $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&insert=1"));
		$smarty->assign($this->opt['smarty_url_uri_suggest'], "javascript:alias_ctl.aliasSuggest();");
		$smarty->assign($this->opt['smarty_url_uri_generate'], "javascript:alias_ctl.aliasGenerate();");
    }
    
    /**
     *  return info need to assign html form to smarty 
     */
    function pass_form_to_html(){
        return array('smarty_name' => $this->opt['smarty_form'],
                     'form_name'   => $this->opt['form_name'],
                     'after'       => $this->js_after,
                     'before'      => $this->js_before);
    }
}


?>
