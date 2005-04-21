<?php
/**
 * Application unit send_im 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_send_im.php,v 1.1 2005/04/21 15:09:45 kozlik Exp $
 * @package   serweb
 */ 

/* Application unit send_im 
 *
 *
 *	This application unit is used for sending instant messages
 *	   
 *	Configuration:
 *	--------------
 *
 *	'im_length'					(int)  default: $config->im_length;
 *	 maximum length of message
 *
 *	'show_wait_win'				(bool) default: true
 *	 should be displayed "please wait" window?
 *
 *	'wait_win_url'				(string) default: "im_sending.php"
 *	 URL of content of "please wait" window
 *
 *	
 *	'msg_send'					default: $lang_str['msg_im_send_s'] and $lang_str['msg_im_send_l']
 *	 message which should be showed on message send - assoc array with keys 'short' and 'long'
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
 *	'smarty_js_closing_win'		name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_sended' - when user submited form and IM was succefully sended
 *	
 */

class apu_send_im extends apu_base_class{
	var $smarty_action='default';
	var $js_closing_win = '';
	/** is opened wait window? Should be closed? */
	var $close_win = false;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('send_im');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('sip_address_completion.js.php', 'instant_message.js');
	}
	
	/* constructor */
	function apu_send_im(){
		global $lang_str, $sess_lang, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['im_length'] =			$config->im_length;

		$this->opt['show_wait_win'] =		true;
		$this->opt['wait_win_url'] =		"im_sending.php";


		/* message on attributes update */
		$this->opt['msg_send']['short'] =	&$lang_str['msg_im_send_s'];
		$this->opt['msg_send']['long']  =	&$lang_str['msg_im_send_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_send'],
										'src'  => get_path_to_buttons("btn_send.gif", $sess_lang));
		
	}

	function action_send(&$errors){
		global $data;

		if (false === $data->send_im($this->user_id, 
									$_POST['im_sip_address'],
									$_POST['im_instant_message'],
									array(),
									$errors)){

			$this->f->load_defaults();			
			return false;
		}
	
		return array("m_im_sended=".RawURLEncode($this->opt['instance_id']),
					 "m_im_sended_uri=".RawURLencode($_POST['im_sip_address']));
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();
		
		if ($this->opt['show_wait_win']){
			$this->js_closing_win='im_close_window();';
		}
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->close_win = true;
			$this->action=array('action'=>"send",
			                    'validate_form'=>true,
								'reload'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $lang_str;
		parent::create_html_form($errors);
		
		$max_len_msg = addslashes($lang_str['max_length_of_im']);
		$max_len = $this->opt['im_length'];

		if (isset($_POST['im_instant_message'])){
			$num_chars = $max_len - strlen($_POST['im_instant_message']);
			// element is disabled, set its value manualy in order to form->load_defaults work correctly
			$_POST['im_num_chars'] = $num_chars;
			$_REQUEST['im_num_chars'] = $num_chars;
		}
		else
			$num_chars = $max_len;


		$this->f->add_element(array("type"=>"text",
		                             "name"=>"im_sip_address",
		                             "value"=>isset($_GET['im_sip_addr'])?$_GET['im_sip_addr']:"",
									 "size"=>16,
									 "maxlength"=>128,
		                             "valid_regex"=>"^".$this->controler->reg->sip_address."$",
		                             "valid_e"=>$lang_str['fe_not_valid_sip'],
									 "extrahtml"=>"onBlur='sip_address_completion(this)'"));
									 
		$this->f->add_element(array("type"=>"textarea",
		                             "name"=>"im_instant_message",
									 "rows"=>6,
									 "cols"=>40,
									 "wrap"=>"soft",
									 "extrahtml"=>"onBlur='im_countit(this.form, ".$max_len.", \"".$max_len_msg."\");' ".
									 	"onChange='im_countit(this.form, ".$max_len.", \"".$max_len_msg."\");' ".
										"onClick='im_countit(this.form, ".$max_len.", \"".$max_len_msg."\");' ".
										"onFocus='im_countit(this.form, ".$max_len.", \"".$max_len_msg."\");' ".
										"onKeyUp='im_countit(this.form, ".$max_len.", \"".$max_len_msg."\");'"));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"im_num_chars",
									 "value"=>$num_chars,
									 "size"=>5,
									 "maxlength"=>5,
									 "extrahtml"=>"disabled class='swFormElementInvisible'"));
	}

	/* validate html form */
	function validate_form(&$errors){
		global $lang_str;
		if (false === parent::validate_form($errors)) return false;

		if (!$_POST['im_instant_message']){
			$errors[]=$lang_str['fe_no_im'];
			return false;
		}

		if (strlen($_POST['im_instant_message']) > $this->opt['im_length']){
			$errors[]=$lang_str['fe_im_too_long'];
			return false;
		}

		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_im_sended']) and $_GET['m_im_sended'] == $this->opt['instance_id']){
			$this->opt['msg_send']['long']  =	$this->opt['msg_send']['long']." ".$_GET['m_im_sended_uri'];
			$msgs[]=&$this->opt['msg_send'];
			$this->smarty_action="was_sended";
			$this->close_win = true;
		}

		if ($this->close_win) $this->controler->set_onload_js($this->js_closing_win);
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		global $lang_str;
		
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => "						
					 	if (f.im_instant_message.value==''){
							alert('".addslashes($lang_str['fe_no_im'])."');
							f.im_instant_message.focus();
							return (false);
						}
						".
						($this->opt['show_wait_win']?
							'im_display_window("'.$this->opt['wait_win_url'].'");':
							''),
					 'before'      => 'sip_address_completion(f.im_sip_address);');
	}
}

?>
