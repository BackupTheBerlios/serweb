<?php
/**
 * Application unit customers
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_customers.php,v 1.7 2006/04/10 13:01:57 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Application unit customers
 *
 *
 *	This application unit is used for add/delete/edit customers
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'redirect_on_update'		(string) default: ''
 *	 name of script to which is browser redirected after succesfull update
 *	 if empty, browser isn't redirected
 *	
 *	'redirect_on_create'		(string) default: ''
 *	 name of script to which is browser redirected after creation of customer 
 *	 if empty, browser isn't redirected
 *	
 *	'redirect_on_delete'		(string) default: ''
 *	 name of script to which is browser redirected after succesfull delete of customer
 *	 if empty, browser isn't redirected
 *
 *	'msg_add'					default: $lang_str['msg_customer_added_s'] and $lang_str['msg_customer_added_l']
 *	 message which should be showed on customer update - assoc array with keys 'short' and 'long'
 *								
 *	'msg_update'				default: $lang_str['msg_customer_updated_s'] and $lang_str['msg_customer_updated_l']
 *	 message which should be showed on customer update - assoc array with keys 'short' and 'long'
 *								
 *	'msg_delete'				default: $lang_str['msg_customer_deleted_s'] and $lang_str['msg_customer_deleted_l']
 *	 message which should be showed on customer update - assoc array with keys 'short' and 'long'
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
 *	'smarty_pager'				name of smarty variable - see below
 *	'smarty_customer'			name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']		(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_updated' - when user submited form and data was succefully stored
 *	
 *	opt['smarty_pager']			(pager)
 *	 associative array containing size of result and which page is returned
 *	
 *	opt['smarty_customer'] 		(customers)	
 *	 associative array containing customers
 *	 The array have same keys as function get_customer (from data layer) returned. 
 *	
 */

class apu_customers extends apu_base_class{
	var $smarty_action='default';
	var $act_id = null;
	/** array of customers */
	var $customers;
	/** customer which is currently editing */
	var $customer;

	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array('get_customers', 'update_customer', 'delete_customer', 'get_domains');
	}

	/**
	 *	return array of strings - required javascript files 
	 *
	 *	@return array	array of required javascript files
	 */
	function get_required_javascript(){
		return array();
	}
	
	/**
	 *	constructor 
	 *	
	 *	initialize internal variables
	 */
	function apu_customers(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['redirect_on_create'] = "";
		$this->opt['redirect_on_update']  = "";
		$this->opt['redirect_on_delete']  = "";

		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_customer_updated_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_customer_updated_l'];

		$this->opt['msg_delete']['short'] =	&$lang_str['msg_customer_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_customer_deleted_l'];

		$this->opt['msg_add']['short'] =	&$lang_str['msg_customer_added_s'];
		$this->opt['msg_add']['long']  =	&$lang_str['msg_customer_added_l'];

		$this->opt['err_owning_domains']  =	&$lang_str['err_customer_own_domains'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';

		$this->opt['smarty_customers'] =	'customers';
		$this->opt['smarty_pager'] =		'pager';
		
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		global $sess_cu_act_row, $sess;
		parent::init();

		if (!$sess->is_registered('sess_cu_act_row')) $sess->register('sess_cu_act_row');
		if (!isset($sess_cu_act_row)) $sess_cu_act_row=0;
		
		if (isset($_GET['act_row'])) $sess_cu_act_row=$_GET['act_row'];
	}
	
	/**
	 *	Method obtain a list of customers and store it into variable $this->customers
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			return TRUE on success, FALSE on failure
	 */
	function get_customers(&$errors){
		global $data, $sess_cu_act_row, $sess;
		
		$data->set_act_row($sess_cu_act_row);

		$opt = array();
					 
		if ($this->action['action'] == 'edit')
			$opt['exclude'] = $this->act_id;

		if (false === $this->customers = $data->get_customers($opt, $errors)) return false;

		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();

		foreach($this->customers as $key=>$val){
			$this->customers[$key]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&cu_dele_id=".$val['primary_key']['cid']);
			$this->customers[$key]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&cu_edit_id=".$val['primary_key']['cid']);
		}
		
		return true;
	}

	/**
	 *	Method create new customer
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_add(&$errors){
		global $data;

		$opt = array( 'insert' => true);
		$values = array('name'  => $_POST['cu_name'],
		                'address'  => $_POST['cu_address'],
						'phone'  => $_POST['cu_phone'],
						'email'  => $_POST['cu_email']);			
							
		if (false === $data->update_customer($values, $opt, $errors)) return false;

		if ($this->opt['redirect_on_create']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_create']);
		}

		return array("m_cu_added=".RawURLEncode($this->opt['instance_id']),
		             "new_cust_id=".RawURLEncode($opt['new_id']));
	}

	/**
	 *	Method update the customer
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_update(&$errors){
		global $data;

		$opt = array( 'insert' => false,
		              'primary_key' => array('cid' => $this->act_id));
		$values = array('name'  => $_POST['cu_name'],
		                'address'  => $_POST['cu_address'],
						'phone'  => $_POST['cu_phone'],
						'email'  => $_POST['cu_email']);			
							
		if (false === $data->update_customer($values, $opt, $errors)) return false;

		if ($this->opt['redirect_on_update']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_update']);
		}

		return array("m_cu_updated=".RawURLEncode($this->opt['instance_id']));
	}

	/**
	 *	Method delete the customer
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_delete(&$errors){
		global $data;

		/* check if customer owning some domains */
		
		$opt = array( 'filter' => array('customer_id' => $this->act_id));
		if (false === $domains = $data->get_domains($opt, $errors)) return false;
		if (count($domains)){
			/* set error message */
			$errors = $this->opt['err_owning_domains'];

			/* get customers in order to the list can be displayed */
			$this->get_customers($errors);
			
			/* unset customer id in the form in order to if
			 * someone submit the form, the action 'add' will perform,
			 * not action 'update' 
			 */
			$this->f->elements['cu_id']['ob']->value = null;
			return false;
		}		

		$opt = array( 'primary_key' => array('cid' => $this->act_id));
		if (false === $data->delete_customer($opt, $errors)) return false;
	
		if ($this->opt['redirect_on_delete']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_delete']);
		}

		return array("m_cu_deleted=".RawURLEncode($this->opt['instance_id']));
	}

	/**
	 *	Default action - only get list of customers
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_default(&$errors){
		if (false === $this->get_customers($errors)) return false;
	}

	/**
	 *	Default action vhen editing customer - only get list of customers
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_edit(&$errors){
		if (false === $this->get_customers($errors)) return false;
		$this->smarty_action="edit";
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){

		if ($this->was_form_submited()){	// Is there data to process?
			if ($_POST['cu_id'] !== ""){
				$this->act_id = $_POST['cu_id'];
				$this->action = array('action'=>"update",
			                    'validate_form'=>true,
								'reload'=>true);
			}
			else {
				$this->action = array('action'=>"add",
			                    'validate_form'=>true,
								'reload'=>true);
			}
			return;
		}
		
		if (isset($_GET['cu_edit_id'])){
			$this->act_id = $_GET['cu_edit_id'];
			$this->action = array('action'=>"edit",
			                    'validate_form'=>false,
								'reload'=>false);
			return;
		}

		if (isset($_GET['cu_dele_id'])){
			$this->act_id = $_GET['cu_dele_id'];

			$this->action = array('action'=>"delete",
			                      'validate_form'=>false,
							      'reload'=>true);
			return;
		}
		
		$this->action=array('action'=>"default",
			                'validate_form'=>false,
							'reload'=>false);
	}
	
	/**
	 *	create html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return null			FALSE on failure
	 */
	function create_html_form(&$errors){
		global $lang_str, $data;
		parent::create_html_form($errors);

		if ($this->action['action'] == 'edit'){
			$opt = array('single' => $this->act_id);
			if (false === $customer = $data->get_customers($opt, $errors)) return false;
			$this->customer = reset($customer);	//return first value of array
		}

		$reg = Creg::singleton();

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"cu_name",
									 "size"=>16,
									 "maxlength"=>128,
		                             "value"=>isset($this->customer['name']) ? $this->customer['name'] : "",
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_customer_name']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"cu_address",
									 "size"=>16,
									 "maxlength"=>255,
		                             "value"=>isset($this->customer['address']) ? $this->customer['address'] : ""));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"cu_phone",
									 "size"=>16,
									 "maxlength"=>64,
		                             "value"=>isset($this->customer['phone']) ? $this->customer['phone'] : ""));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"cu_email",
									 "size"=>16,
									 "maxlength"=>255,
		                             "value"=>isset($this->customer['email']) ? $this->customer['email'] : "",
		                             "valid_regex"=>"(".$reg->email.")|(^$)",
		                             "valid_e"=>$lang_str['fe_not_valid_email']));

		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"cu_id",
		                             "value"=>$this->act_id));
	}

	/**
	 *	validate html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE if given values of form are OK, FALSE otherwise
	 */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/**
	 *	add messages to given array 
	 *
	 *	@param array $msgs	array of messages
	 */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_cu_updated']) and $_GET['m_cu_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}

		if (isset($_GET['m_cu_added']) and $_GET['m_cu_added'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_add'];
			$this->smarty_action="was_added";
		}

		if (isset($_GET['m_cu_deleted']) and $_GET['m_cu_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}
	}

	/**
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_customers'], $this->customers);
	}
	
	/**
	 *	return info need to assign html form to smarty 
	 */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => '');
	}
}

?>
