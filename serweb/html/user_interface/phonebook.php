<?
/*
 * $Id: phonebook.php,v 1.18 2004/08/09 23:04:57 kozlik Exp $
 */

$_data_layer_required_methods=array('del_phonebook_entry', 'get_phonebook_entry', 'get_phonebook_entries', 
		'update_phonebook_entry', 'get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");
						   
require "prepend.php";

if (!$sess->is_registered('sess_pb_act_row')) $sess->register('sess_pb_act_row');
if (!isset($sess_pb_act_row)) $sess_pb_act_row=0;

if (isset($HTTP_GET_VARS['act_row'])) $sess_pb_act_row=$HTTP_GET_VARS['act_row'];

				 
$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

set_global('okey_x');
set_global('id');
set_global('fname');
set_global('lname');
set_global('sip_uri');

$action='';
if (isset($_GET['edit_id'])) $action='edit';
else if (isset($_REQUEST['dele_id'])) $action='delete';

$contact=null;
$delete_confirmed=false;
if (isset($_POST['dele_confirmed'])) 
	if (isset($_POST['okey_x'])) //$_POST['okey_x'] is need for recognize which button was clicked
		$delete_confirmed = $_POST['dele_confirmed']?true:false;
	else
		$action=''; //cancel button

do{
	if ($action=='delete'){
	
		if (!$config->require_delete_confirmation_page or $delete_confirmed){
			if (!$data->del_phonebook_entry($serweb_auth, $_REQUEST['dele_id'], $errors)) break;
	
	        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("")."&m_contact_deleted=1"));
			page_close();
			exit;
		}
		else{ //should be displayed delete confirmation page first

			$f->add_element(array("type"=>"hidden",
			                             "name"=>"dele_id",
			                             "value"=>$_REQUEST['dele_id']));
			$f->add_element(array("type"=>"hidden",
			                             "name"=>"dele_confirmed",
			                             "value"=>'1'));
			$f->add_element(array("type"=>"submit",
			                             "name"=>"okey",
			                             "src"=>$config->img_src_path."butons/b_delete.gif",
										 "extrahtml"=>"alt='delete'"));


			//get contact details and display confirmation page
			$contact = $data->get_phonebook_entry($serweb_auth, $_REQUEST['dele_id'], $errors);
			break;
		}
	}

	if (isset($_GET['edit_id'])){
		if (false === $contact = $data->get_phonebook_entry($serweb_auth, $_GET['edit_id'], $errors)) break;
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>isset($contact['fname'])?$contact['fname']:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>isset($contact['lname'])?$contact['lname']:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"sip_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($contact['sip_uri'])?$contact['sip_uri']:"",
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>$lang_str['fe_not_valid_sip'],
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"id",
	                             "value"=>isset($_GET['edit_id'])?$_GET['edit_id']:""));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));


	if (!is_null($okey_x)){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;

		if (!$data->update_phonebook_entry($serweb_auth, $id, $fname, $lname, $sip_uri, $errors)) break;

        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("").($id?"&m_contact_updated=1":"&m_contact_added=1")));
		page_close();
		exit;
	}
}while (false);

do{
	$pb_res=array();
	if ($data){
		// get phonebook
		$data->set_act_row($sess_pb_act_row);
		if (false === $pb_res = $data->get_phonebook_entries($serweb_auth, isset($_GET['edit_id'])?$_GET['edit_id']:NULL, $errors)) break;
	}
}while (false);

if (!is_null($okey_x)){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

if (isset($_GET['m_contact_deleted'])){
	$message['short'] = $lang_str['msg_pb_contact_deleted_s'];
	$message['long']  = $lang_str['msg_pb_contact_deleted_l'];
}

if (isset($_GET['m_contact_updated'])){
	$message['short'] = $lang_str['msg_pb_contact_updated_s'];
	$message['long']  = $lang_str['msg_pb_contact_updated_l'];
}

if (isset($_GET['m_contact_added'])){
	$message['short'] = $lang_str['msg_pb_contact_added_s'];
	$message['long']  = $lang_str['msg_pb_contact_added_l'];
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>
<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$pager['url']="phonebook.php?kvrk=".uniqid("")."&act_row=";
$pager['pos']=$data->get_act_row();
$pager['items']=$data->get_num_rows();
$pager['limit']=$data->get_showed_rows();
$pager['from']=$data->get_res_from();
$pager['to']=$data->get_res_to();

if(!$pb_res) $pb_res = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref('pager', $pager);
$smarty->assign_by_ref('pb_res', $pb_res);

$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'), array('before'=>'sip_address_completion(f.sip_uri);'));

$smarty->assign_by_ref('action', $action);
$smarty->assign_by_ref('contact', $contact);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('u_phonebook.tpl');

?>


<?print_html_body_end();?>
</html>
<?page_close();?>
