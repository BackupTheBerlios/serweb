<?php
/**
 *	Application unit aliases
 *	
 *	@author     Karel Kozlik
 *	@version    $Id: apu_aliases.php,v 1.9 2009/12/17 12:11:56 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_uri
 */ 

/** 
 *	Application unit aliases
 *
 *
 *	This application unit is used for manipulation with aliases
 *	notice: manipulation still not dome. only get list of aliases may be used
 *	   
 *	<pre>
 *	Configuration:
 *	--------------
 *	'allow_edit'				(bool) default: false
 *   set true if instance of this APU should be used for change values
 *	 by default only get list of aliases is enabled
 *
 *	'msg_add'					default: $lang_str['msg_alias_added_s'] and $lang_str['msg_alias_added_l']
 *	 message which should be showed on add new alias - assoc array with keys 'short' and 'long'
 *	
 *	'msg_update'					default: $lang_str['msg_alias_updated_s'] and $lang_str['msg_alias_updated_l']
 *	 message which should be showed on alias update - assoc array with keys 'short' and 'long'
 *	
 *	'msg_delete'					default: $lang_str['msg_alias_deleted_s'] and $lang_str['msg_alias_deleted_l']
 *	 message which should be showed on alias delete - assoc array with keys 'short' and 'long'
 *	
 *	'form_name'					(string) default: ''
 *	 name of html form
 *	
 *	'form_submit'				(assoc)
 *	 assotiative array describe submit element of form. For details see description 
 *	 of method add_submit in class form_ext
 *	
 *	'smarty_form'				name of smarty variable - see below
 *	'smarty_action'				name of smarty variable - see below
 *	'smarty_aliases'			name of smarty variable - see below
 *	
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_aliases'] 		(aliases)	
 *	 associative array containing user's aliases 
 *	 The array have same keys as function get_aliases (from data layer) returned. 
 *	
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_ack_uris']  	(ack_uris) 
 *	 list of URIs with same username and did as changed URI
 *	 
 *	opt['smarty_url_ack']  		(ack_url) 
 *	 URL which acknowledges changes
 *	 
 *	opt['smarty_url_deny']  	(deny_url) 
 *	 URL denying acknowledgement
 *	 
 *	opt['smarty_uri_for_ack']  	(uri_for_ack) 
 *	 contain URI which should be acknowlesged
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_updated' - when user submited form and data was succefully stored
 *	  'was_added'   - when user submited form and new alias was succefully stored
 *	  'was_deleted' - when user delete alias
 *	  'edit'        - when user is editing alias
 *	  'ack'         - when user should acknowledge values
 *	
 *	</pre>
 *	@package    serweb
 *	@subpackage mod_uri
 */

class apu_aliases extends apu_base_class{
	var $smarty_action='default';
	var $aliases = array();
	/** if changed should be acknowledged contain list of URIs with same username and did */
	var $ack_uris = array();
	/** url for acknowledge changes */
	var $ack_url;
	/** url for deny changes */
	var $deny_url;
	/** contain uri which should be acknowledged */
	var $uri_for_ack = array();
	/** display warning, flag is to is set in another uri */
	var $ack_is_to = false;

	var $act_alias;
	

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('delete_uri', 'add_uri', 'update_uri', 'get_uris',
                     'get_new_alias_number');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('functions.js',
                     'get_js.php?mod=uri&js=aliases.js');
	}
	
	/* constructor */
	function apu_aliases(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['allow_edit'] =	false;
		$this->opt['allowed_domains'] = null;
		$this->opt['get_all_uids_for_uri'] = false;

		/* message on alias update */
		$this->opt['msg_add']['short'] =	&$lang_str['msg_alias_added_s'];
		$this->opt['msg_add']['long']  =	&$lang_str['msg_alias_added_l'];

		$this->opt['msg_update']['short'] =	&$lang_str['msg_alias_updated_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_alias_updated_l'];

		$this->opt['msg_delete']['short'] =	&$lang_str['msg_alias_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_alias_deleted_l'];

		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =		'form';
		/* smarty action */
		$this->opt['smarty_action'] =	'action';

		$this->opt['smarty_aliases'] =	'aliases';

		$this->opt['smarty_ack_uris'] = 	'ack_uris';
		$this->opt['smarty_url_ack'] = 		'url_ack';
		$this->opt['smarty_url_deny'] = 	'url_deny';
		$this->opt['smarty_url_insert'] =   'url_insert';
		$this->opt['smarty_url_uri_suggest'] =  'url_uri_suggest';
		$this->opt['smarty_url_uri_generate'] = 'url_uri_generate';
		$this->opt['smarty_uri_for_ack'] = 	'uri_for_ack';
		$this->opt['smarty_is_to_warning'] ='is_to_warning';
		
		/* name of html form */
		$this->opt['form_name'] =			'uriForm';
	}

	/* this metod is called always at begining */
	function init(){
		parent::init();
	}

	function check_did($did){
		if (is_array($this->opt['allowed_domains'])){
			if (!in_array($did, $this->opt['allowed_domains'])) return false;
		}
		
		return true;
	}
	
	/**
	 *	Calculate value of flags column from values given by HTML form
	 *
	 *	@return int
	 */
	function get_flags_from_POSTs(){
		global $config;
		
		$f = &$config->data_sql->uri->flag_values;
		$flags = $_POST['al_id_f'];
		
		if (!empty($_POST['al_is_canon'])) $flags |= $f['DB_CANON'];
		else $flags &= ~$f['DB_CANON'];
		
		if (!empty($_POST['al_is_to'])) $flags |= $f['DB_IS_TO'];
		else $flags &= ~$f['DB_IS_TO'];
		
		if (!empty($_POST['al_is_from'])) $flags |= $f['DB_IS_FROM'];
		else $flags &= ~$f['DB_IS_FROM'];

		return $flags;
	}

	/**
	 *	Convert URI object to associative array
	 *	
	 *	@param	object	$uri
	 *	@param	array
	 */
	function uri_to_assoc(&$uri){
		$out = array();
		$out['uid']      = $uri->get_uid();
		$out['username'] = $uri->get_username();
		$out['did']      = $uri->get_did();
		$out['domain']   = isset($this->domain_names[$uri->get_did()]) ? 
		                         $this->domain_names[$uri->get_did()]  : 
								 "";
		$out['is_canon'] = $uri->is_canonical();
		$out['is_to']    = $uri->is_to();
		$out['is_from']  = $uri->is_from();
		$out['disabled'] = $uri->is_disabled();

		return $out;
	}

	/**
	 *	Get table of URIs of user
	 *
	 *	return	bool 	TRUE on success, FALSE on error
	 */
	function get_aliases(&$errors){
		global $sess;

		static $done = false;
		
		/* if aliases were been already obtained */
		if ($done) return true;

		/* get URIs of the user */
		$uri_handler = &URIs::singleton($this->user_id->get_uid());
		if (false === $aliases = $uri_handler->get_URIs()) return false;

		foreach($aliases as $k=>$v){

			if ($this->action['action']=='edit' and 
			    $v->get_username() == $this->act_alias['username'] and
				$v->get_did()      == $this->act_alias['did'] and 
				$v->get_flags()    == $this->act_alias['flags']){

				continue;	//skip this URI
			}

			$this->aliases[$k] = $this->uri_to_assoc($v);
			$this->aliases[$k]['uri_obj'] = &$aliases[$k];

			// check if admin has parmission to change this URI
			if (!$this->check_did($v->get_did())) {
				$this->aliases[$k]['allow_change']  = false;
			}
			else{
				$query_string =  "al_un=".RawURLEncode($v->get_username()).
				                "&al_did=".RawURLEncode($v->get_did()).
								"&al_flags=".RawURLEncode($v->get_flags());

				$this->aliases[$k]['allow_change']  = true;
				$this->aliases[$k]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&uri_dele=1&".$query_string);
				$this->aliases[$k]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&uri_edit=1&".$query_string);
			}

			if ($this->opt['get_all_uids_for_uri']){
				/* get URIs with same username and did */
				$uri_handler2 = &URIs::singleton_2($v->get_username(), $v->get_did());
				if (false === $uris_ud = $uri_handler2->get_URIs()) return false;
				
				$this->aliases[$k]['s_uris'] = array();
				foreach($uris_ud as $vv)
					$this->aliases[$k]['s_uris'][] = $this->uri_to_assoc($vv);
			}

		}
		
		$done = true;
		return true;
	}

	/**
	 *	Get list of URIs with same username and did as currently added uri has
	 */
	function get_ack_uris($username, $did){
		global $sess;

		$uris_handler = &URIS::singleton_2($username, $did);
		if (false === $uris = $uris_handler->get_URIs()) return false;

		$this->ack_is_to = false;

		foreach($uris as $k=>$v){
			/* skip URI which is currently changed */
			if ($this->action['action']=='ack_update' and
			    $uris[$k]->get_uid() == $this->user_id->get_uid() and
			    $uris[$k]->get_did() == $this->act_alias['did'] and
				$uris[$k]->get_username() == $this->act_alias['username'] and
				$uris[$k]->get_flags() == $this->act_alias['flags']) continue;

			if ($v->is_to()) $this->ack_is_to = true;

			$this->ack_uris[$k] = $this->uri_to_assoc($v);
		}

		$this->ack_url = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&al_ack_changes=1");
		$this->deny_url = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID(""));

		return true;
	}

	/**
	 *	store POSTs for smarty
	 */
	function ack_store_POSTs(){
		$_SESSION['apu_aliases']['ack']['vals']['new']	= 
						array('username' => $_POST['al_username'],
						      'did' =>      $_POST['al_domain'],
						      'flags' =>    $this->get_flags_from_POSTs());

		$this->uri_for_ack['uid'] = $this->user_id->get_uid();
		$this->uri_for_ack['username'] = $_POST['al_username'];
		$this->uri_for_ack['did']      = $_POST['al_domain'];
		$this->uri_for_ack['domain']   = isset($this->domain_names[$_POST['al_domain']]) ? $this->domain_names[$_POST['al_domain']] : "";
		$this->uri_for_ack['is_canon'] = !empty($_POST['al_is_canon']) ? true:  false;
		$this->uri_for_ack['is_to']    = !empty($_POST['al_is_to']) ? true:  false;
		$this->uri_for_ack['is_from']  = !empty($_POST['al_is_from']) ? true:  false;

		$this->ack_is_to = ($this->ack_is_to and $this->uri_for_ack['is_to']);
	}

	function action_ack_add(&$errors){

		$_SESSION['apu_aliases']['ack']['action']	= 'add';

		if (false === $this->get_ack_uris($_POST['al_username'], $_POST['al_domain'])) return false;
		$this->ack_store_POSTs();

		$this->smarty_action="ack";

		return true;
	}

	function action_ack_update(&$errors){

		$_SESSION['apu_aliases']['ack']['action']	= 'update';
		$_SESSION['apu_aliases']['ack']['vals']['old'] = 
						array('username' => $this->act_alias['username'],
						      'did' =>      $this->act_alias['did'],
						      'flags' =>    $this->act_alias['flags']);

		if (false === $this->get_ack_uris($_POST['al_username'], $_POST['al_domain'])) return false;
		$this->ack_store_POSTs();

		$this->smarty_action="ack";

		return true;
	}

	function clear_is_to($username, $did){
		global $data, $config;

		$f = &$config->data_sql->uri->flag_values;

		$uris_handler = &URIS::singleton_2($username, $did);
		if (false === $uris = $uris_handler->get_URIs()) return false;

		foreach($uris as $k=>$v){
			/* do not affect URI which is currently changed */
			if ($this->action['action']=='update' and
			    $uris[$k]->get_uid() == $this->user_id->get_uid() and
			    $uris[$k]->get_did() == $this->act_alias['did'] and
				$uris[$k]->get_username() == $this->act_alias['username'] and
				$uris[$k]->get_flags() == $this->act_alias['flags']) continue;

			if ($v->is_to()) {
				$old_v = array('uid' => $v->get_uid(),
				               'did' => $v->get_did(),
							   'username' => $v->get_username(),
							   'flags' => $v->get_flags());
			
				$new_v = array('flags' => $v->get_flags() & ~$f['DB_IS_TO']);

				if (false === $data->update_uri($new_v, $old_v, null)) return false;
			}
		}
	}

	function clear_is_canon($uid){
		global $data, $config, $lang_str;

		$f = &$config->data_sql->uri->flag_values;

		$uris_handler = &URIS::singleton($uid);
		if (false === $uris = $uris_handler->get_URIs()) return false;

		foreach($uris as $k=>$v){
			/* do not affect URI which is currently changed */
			if ($this->action['action']=='update' and
			    $uris[$k]->get_uid() == $this->user_id->get_uid() and
			    $uris[$k]->get_did() == $this->act_alias['did'] and
				$uris[$k]->get_username() == $this->act_alias['username'] and
				$uris[$k]->get_flags() == $this->act_alias['flags']) continue;

			if ($v->is_canonical()) {
				if (!$this->check_did($v->get_did())){
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

	function action_add(&$errors){
		global $data, $config;

		if (isset($_SESSION['apu_aliases']['ack'])){
			$username = $_SESSION['apu_aliases']['ack']['vals']['new']['username'];
			$did      = $_SESSION['apu_aliases']['ack']['vals']['new']['did'];
			$flags    = $_SESSION['apu_aliases']['ack']['vals']['new']['flags'];

			unset($_SESSION['apu_aliases']['ack']);
		}
		else{
			$username = $_POST['al_username'];
			$did      = $_POST['al_domain'];
			$flags    = $this->get_flags_from_POSTs();
		}
		$uid = $this->user_id->get_uid();

		// Check if admin has parmission to create URI with selected domain.
		// Domain should be chceke in method validate_form(), but check it 
		// again to be sure.
		if (!$this->check_did($did)) {
			$errors[] = "Can not create URI, you have not access to selected domain";
			return false;
		}


		$f = &$config->data_sql->uri->flag_values;

		$data->transaction_start();

		/* get URIs of the user */
		$uri_handler = &URIs::singleton($this->user_id->get_uid());
		if (false === $uris = $uri_handler->get_URIs()) {
			$data->transaction_rollback();
			return false;
		}

		/* Walk throught all existing URIs and find if newly created URI 
		 * will be disabled or not. If all URIs are disabled, newly created 
		 * URI will be disabled too.
		 */
		$uri_disabled = true; 
		foreach($uris as $k=>$v){
			$uri_disabled = ($uri_disabled and $v->is_disabled());
		}
		if (!count($uris)) $uri_disabled = false;

		if ($uri_disabled) $flags |= $f['DB_DISABLED'];
		else $flags &= ~$f['DB_DISABLED'];

		/* if flag 'IS_TO' is set in this URI, clear this flag in all other 
		 * URIs with same username and did
		 */
		if ($flags & $f['DB_IS_TO']){
			if (false === $this->clear_is_to($username, $did)) {
				$data->transaction_rollback();
				return false;
			}
		}

		/* if flag 'IS_CANON' is set in this URI, clear this flag in all other 
		 * URIs with same uid 
		 */
		if ($flags & $f['DB_CANON']){
			if ($flags & $f['DB_IS_TO']) {
				$uris_handler = &URIS::singleton($uid);
				$uris_handler->invalidate();
			}
		
			if (false === $this->clear_is_canon($uid)) {
				$data->transaction_rollback();
				return false;
			}
		}

		$opt = array('flags' => $flags);
		if (false === $data->add_uri($uid, 'sip', $username, $did, $opt)) {
			$data->transaction_rollback();
			return false;
		}

		$data->transaction_commit();

		return array("m_al_added=".RawURLEncode($this->opt['instance_id']));
	}

	function action_update(&$errors){
		global $data, $config;

		if (isset($_SESSION['apu_aliases']['ack'])){
			$username = $_SESSION['apu_aliases']['ack']['vals']['new']['username'];
			$did      = $_SESSION['apu_aliases']['ack']['vals']['new']['did'];
			$flags    = $_SESSION['apu_aliases']['ack']['vals']['new']['flags'];

			unset($_SESSION['apu_aliases']['ack']);
		}
		else{
			$username = $_POST['al_username'];
			$did      = $_POST['al_domain'];
			$flags    = $this->get_flags_from_POSTs();
		}
		$uid = $this->user_id->get_uid();

		// Check if admin has parmission to update URI.
		// Domain should be chceke in method validate_form(), but check it 
		// again to be sure.
		if (!$this->check_did($did)) {
			$errors[] = "Can not update URI, you have not access to selected domain";
			return false;
		}
		if (!$this->check_did($this->act_alias['did'])) {
			$errors[] = "Can not update, you have not access to this URI";
			return false;
		}


		$f = &$config->data_sql->uri->flag_values;

		$data->transaction_start();

		/* if flag 'IS_TO' is set in this URI, clear this flag in all other 
		 * URIs with same username and did
		 */
		if ($flags & $f['DB_IS_TO']){
			if (false === $this->clear_is_to($username, $did)) {
				$data->transaction_rollback();
				return false;
			}
		}

		/* if flag 'IS_CANON' is set in this URI, clear this flag in all other 
		 * URIs with same uid 
		 */
		if ($flags & $f['DB_CANON']){
			if ($flags & $f['DB_IS_TO']) {
				$uris_handler = &URIS::singleton($uid);
				$uris_handler->invalidate();
			}
		
			if (false === $this->clear_is_canon($uid)) {
				$data->transaction_rollback();
				return false;
			}
		}

		$old_v = array('uid' =>      $uid,
		               'did' =>      $this->act_alias['did'],
					   'username' => $this->act_alias['username'],
					   'flags' =>    $this->act_alias['flags']);
	
		$new_v = array('did' =>      $did,
		               'username' => $username,
					   'flags' =>    $flags);

		if (false === $data->update_uri($new_v, $old_v, null)) {
			$data->transaction_rollback();
			return false;
		}

		$data->transaction_commit();

		return array("m_al_updated=".RawURLEncode($this->opt['instance_id']));
	}

	function action_delete(&$errors){
		global $data;

		// check if admin has parmission to change this URI and if has not, return flase
		if (!$this->check_did($this->act_alias['did'])) {
			$errors[] = "Can not delete, you have not access to this URI";
			return false;
		}

		if (false === $data->delete_uri($this->user_id->get_uid(), 'sip', $this->act_alias['username'], $this->act_alias['did'], $this->act_alias['flags'], null)) return false;
		return array("m_al_deleted=".RawURLEncode($this->opt['instance_id']));
	}

	function action_edit(&$errors){

		if (false === $this->get_aliases($errors)) return false;
		$this->smarty_action="edit";
	}

	function action_insert(&$errors){

		$this->smarty_action="insert";
	}

	function action_default(&$errors){
		if (false === $this->get_aliases($errors)) return false;
		return true;
	}
    
    function action_generate_query(&$errors){
        global $data;

        $this->controler->disable_html_output();
        header("Content-Type: text/plain");

        $did = $_GET['al_did'];

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

        $uris_handler = &URIS::singleton_2($uname, $did);
        if (false === $uris = $uris_handler->get_URIs()) return false;

        $response = new stdClass();
        $response->uri_used = (count($uris) > 0) ? true : false;

        echo my_JSON_encode($response);
        return true;
    }
    
    function action_suggest_query(&$errors){
        global $data;

        $this->controler->disable_html_output();
        header("Content-Type: text/plain");

        $uname = $_GET['al_uname'];
        $did = $_GET['al_did'];

        if (ereg("^[0-9]+$", $uname)){
            // suggestion for numeric aliases
            $uris = array();
            for ($i=0; $i<strlen($uname); $i++){
                $gen_uris = array();
                for ($j=0; $j<10; $j++) $gen_uris[substr_replace($uname, $j, $i, 1)] = 1;
            
                $opt = array(); 
                $opt['filter']['did'] = new Filter("did", $did, "=", false, false);
                $opt['filter']['username'] = new Filter("username", substr_replace($uname, "?", $i, 1), "like", false, false);
                if (false === $used_uris = $data->get_uris(null, $opt)) return false;
    
                foreach($used_uris as $v) unset($gen_uris[$v->username]);
    
                $uris = array_merge($uris, array_keys($gen_uris));
            }
        }
        else{
            $gen_uris = array();
            for ($j=0; $j<10; $j++) $gen_uris[$uname.$j] = 1;

            $opt = array(); 
            $opt['filter']['did'] = new Filter("did", $did, "=", false, false);
            $opt['filter']['username'] = new Filter("username", $uname."?", "like", false, false);
            if (false === $used_uris = $data->get_uris(null, $opt)) return false;

            foreach($used_uris as $v) unset($gen_uris[$v->username]);

            $uris = array_keys($gen_uris);
        }

        sort($uris);
        $response = new stdClass();
        $response->suggested_uris = $uris;

        echo my_JSON_encode($response);
        return true;
    }
    
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){

		if ($this->opt['allow_edit']){	
			if ($this->was_form_submited()){	// Is there data to process?

				//check if alias exists
				$uris_handler = &URIS::singleton_2($_POST['al_username'], $_POST['al_domain']);
				if (false === $uris = $uris_handler->get_URIs()) return false;

				$ack = false;
				if (count($uris) > 1 ) $ack = true;
				if (count($uris) == 1) {
					if (!($uris[0]->get_uid() == $this->user_id->get_uid() and
					      $uris[0]->get_did() == $_POST['al_id_d'] and
						  $uris[0]->get_username() == $_POST['al_id_u'] and
						  $uris[0]->get_flags() == $_POST['al_id_f'])){
						  
						$ack = true;
					}
				}

				if ($_POST['al_id_u']){
					$this->act_alias['username'] = $_POST['al_id_u'];
					$this->act_alias['did']      = $_POST['al_id_d'];
					$this->act_alias['flags']    = $_POST['al_id_f'];
	
					if ($ack){
						$this->action = array('action'=>"ack_update",
					                          'validate_form'=>true,
						                      'reload'=>false);
					}
					else{
						$this->action = array('action'=>"update",
					                          'validate_form'=>true,
						                      'reload'=>true);
					}
				}
				else {
					if ($ack){
						$this->action = array('action'=>"ack_add",
					                          'validate_form'=>true,
						                      'reload'=>false);
					}
					else{
						$this->action = array('action'=>"add",
					                          'validate_form'=>true,
						                      'reload'=>true);
					}
				}
				return;
			}

			if (!empty($_GET['al_ack_changes']) and isset($_SESSION['apu_aliases']['ack'])){
				if ($_SESSION['apu_aliases']['ack']['action'] == 'update'){
					$this->act_alias['username'] = $_SESSION['apu_aliases']['ack']['vals']['old']['username'];
					$this->act_alias['did'] =      $_SESSION['apu_aliases']['ack']['vals']['old']['did'];
					$this->act_alias['flags'] =    $_SESSION['apu_aliases']['ack']['vals']['old']['flags'];

					$this->action = array('action'=>"update",
				                          'validate_form'=>false,
					                      'reload'=>true);
				}
				else {
					$this->action = array('action'=>"add",
				                          'validate_form'=>false,
					                      'reload'=>true);
				}
				return;
			}
			
			/* to be sure */
			if (isset($_SESSION['apu_aliases']['ack'])) 
				unset($_SESSION['apu_aliases']['ack']);
			
			if (isset($_GET['uri_insert'])){
	
				$this->action = array('action'=>"insert",
				                      'validate_form'=>false,
				                      'reload'=>false);
				return;
			}
	
			if (isset($_GET['uri_edit'])){
				$this->act_alias['username'] = $_GET['al_un'];
				$this->act_alias['did'] =      $_GET['al_did'];
				$this->act_alias['flags'] =    $_GET['al_flags'];
	
				$this->action = array('action'=>"edit",
				                      'validate_form'=>false,
				                      'reload'=>false);
				return;
			}
	
			if (isset($_GET['uri_dele'])){
				$this->act_alias['username'] = $_GET['al_un'];
				$this->act_alias['did'] =      $_GET['al_did'];
				$this->act_alias['flags'] =    $_GET['al_flags'];
	
				$this->action = array('action'=>"delete",
				                      'validate_form'=>false,
				                      'reload'=>true);
				return;
			}

			if (isset($_GET['uri_usage'])){
	
				$this->action = array('action'=>"usage_query",
				                      'validate_form'=>false,
				                      'reload'=>false,
                                      'alone'=>true);
				return;
			}

			if (isset($_GET['uri_suggest'])){
	
				$this->action = array('action'=>"suggest_query",
				                      'validate_form'=>false,
				                      'reload'=>false,
                                      'alone'=>true);
				return;
			}

			if (isset($_GET['uri_generate'])){
	
				$this->action = array('action'=>"generate_query",
				                      'validate_form'=>false,
				                      'reload'=>false,
                                      'alone'=>true);
				return;
			}
		}
		
		$this->action=array('action'=>"default",
		                    'validate_form'=>false,
		                    'reload'=>false);

	}
	
	/* create html form */
	function create_html_form(&$errors){
		parent::create_html_form($errors);
		global $lang_str, $config, $sess;
		
		$domains = &Domains::singleton();
		if (false === $this->domain_names = $domains->get_id_name_pairs()) return false;

		if ($this->opt['allow_edit']){	
			$an = &$config->attr_names;
			$f = &$config->data_sql->uri->flag_values;

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

			/* if flags is not set, get the default flags */	
			if (!isset($this->act_alias['flags'])){
				$ga_handler = &Global_attrs::singleton();
				if (false === $this->act_alias['flags'] = $ga_handler->get_attribute($an['uri_default_flags'])) return false;

				if (!is_numeric($this->act_alias['flags'])){
					ErrorHandler::log_errors(PEAR::raiseError("Global attribute '".$an['uri_default_flags']."' is not defined or is not a number Can't create URI."));
					return false;
				}

				/* if user ha not aliases set he 'canon' flag to true */
				if (false === $this->get_aliases($errors)) return false;
				if (!count($this->aliases)) $this->act_alias['flags'] |= $f['DB_CANON'];
			}

			$f_canon =   (bool)($this->act_alias['flags'] & $f['DB_CANON']);
			$f_is_to =   (bool)($this->act_alias['flags'] & $f['DB_IS_TO']);
			$f_is_from = (bool)($this->act_alias['flags'] & $f['DB_IS_FROM']);
	
			$dom_options = array();
			foreach ($alowed_domain_names as $k => $v) 
				$dom_options[]=array("label"=>$v, "value"=>$k);

			$reg = &Creg::singleton();

			$this->f->add_element(array("type"=>"text",
			                             "name"=>"al_username",
										 "size"=>16,
										 "maxlength"=>64,
										 "minlength"=>1,
										 "valid_regex"=>"^".$reg->user."$",
										 "valid_e"=>$lang_str['fe_not_valid_username'],
										 "length_e"=>$lang_str['fe_not_filled_username'],
			                             "value"=>isset($this->act_alias['username']) ? $this->act_alias['username'] : "",
                                         "extrahtml"=>"onkeyup='alias_ctl.onAliasChange();' oncut='alias_ctl.onAliasChange();' onpaste='alias_ctl.onAliasChange();'"));
	
			$this->f->add_element(array("type"=>"select",
										 "name"=>"al_domain",
										 "options"=>$dom_options,
										 "value"=>(isset($this->act_alias['did']) ? $this->act_alias['did'] : $this->user_id->get_did()),
										 "size"=>1,
                                         "extrahtml"=>"onchange='alias_ctl.onAliasChange();' onkeyup='alias_ctl.onAliasChange();'"));

			$this->f->add_element(array("type"=>"checkbox",
										 "name"=>"al_is_canon",
										 "checked"=>$f_canon,
										 "value"=>1));

			$this->f->add_element(array("type"=>"checkbox",
										 "name"=>"al_is_from",
										 "checked"=>$f_is_from,
										 "value"=>1));

			$this->f->add_element(array("type"=>"checkbox",
										 "name"=>"al_is_to",
										 "checked"=>$f_is_to,
										 "value"=>1));


			$this->f->add_element(array("type"=>   "hidden",
			                             "name"=>  "al_id_u",
			                             "value"=> isset($this->act_alias['username']) ? $this->act_alias['username'] : ""));
		
			$this->f->add_element(array("type"=>   "hidden",
			                             "name"=>  "al_id_d",
			                             "value"=> isset($this->act_alias['did']) ? $this->act_alias['did'] : ""));

			$this->f->add_element(array("type"=>   "hidden",
			                             "name"=>  "al_id_f",
			                             "value"=> $this->act_alias['flags']));


            $onload_js = "
                var alias_ctl;
                alias_ctl = new Aliases_ctl('alias_ctl');
                alias_ctl.init('".$this->opt['form_name']."');
                alias_ctl.onAliasChangeUrl='".$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&uri_usage=1")."';
                alias_ctl.aliasSuggestUrl='".$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&uri_suggest=1")."';
                alias_ctl.aliasGenerateUrl='".$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&uri_generate=1")."';
                alias_ctl.lang_str.no_suggestions='".js_escape($lang_str['no_suggestions'])."'                
            ";

            $this->controler->set_onload_js($onload_js);

		}
	}

	/* validate html form */
	function validate_form(&$errors){
		global $lang_str, $data;
		
		if (false === parent::validate_form($errors)) return false;

		$did = $_POST['al_domain'];
		if (!$this->check_did($did)){
			$d = &Domain::singleton();
			$errors[] = "You haven't access to domain which you selected: ".$d->get_domain_name($did); 
			return false;
		}

		// check if admin has parmission to changed URI and if has not, return flase
		if ($_POST['al_id_d'] != "" and !$this->check_did($_POST['al_id_d'])) {
			$errors[] = "Can not update, you have not access to this URI";
			return false;
		}

		if (!empty($_POST['al_is_canon'])){
			// chenck if cannonical flag in other URI may be cleared

			$uri_handler = &URIs::singleton($this->user_id->get_uid());
			if (false === $uris = $uri_handler->get_URIs()) return false;

			foreach($uris as $k=>$v){
				if ($v->is_canonical() and !$this->check_did($v->get_did())){
					$errors[]=$lang_str['err_canon_uri_exists'];
					return false;
				}		
			}
		
		}
		
		return true;
	}

	/* callback function when some html form is invalid */
	function form_invalid(){
		$this->get_aliases($errors);
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;

		if (isset($_GET['m_al_updated']) and $_GET['m_al_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}

		if (isset($_GET['m_al_added']) and $_GET['m_al_added'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_add'];
			$this->smarty_action="was_added";
		}

		if (isset($_GET['m_al_deleted']) and $_GET['m_al_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}

	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty, $sess;

		$smarty->assign_by_ref($this->opt['smarty_aliases'], $this->aliases);
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_url_insert'], $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&uri_insert=1"));
		$smarty->assign($this->opt['smarty_url_uri_suggest'], "javascript:alias_ctl.aliasSuggest();");
		$smarty->assign($this->opt['smarty_url_uri_generate'], "javascript:alias_ctl.aliasGenerate();");

		if ($this->smarty_action=="ack"){
			$smarty->assign_by_ref($this->opt['smarty_ack_uris'], $this->ack_uris);
			$smarty->assign_by_ref($this->opt['smarty_url_ack'],  $this->ack_url);
			$smarty->assign_by_ref($this->opt['smarty_url_deny'], $this->deny_url);
			$smarty->assign_by_ref($this->opt['smarty_uri_for_ack'], $this->uri_for_ack);
			$smarty->assign_by_ref($this->opt['smarty_is_to_warning'], $this->ack_is_to);
		}
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => "",
					 'before'      => "");
	}
}

?>
