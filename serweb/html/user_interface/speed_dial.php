<?
/*
 * $Id: speed_dial.php,v 1.11 2004/11/10 13:13:06 kozlik Exp $
 */

$_data_layer_required_methods=array('get_user_real_name', 'update_sd_request', 'del_sd_request',
									'get_sd_request', 'get_sd_requests');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

require "prepend.php";

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

set_global('edit_sd');
set_global('edit_sd_dom');
$errors = array();


do{
	if (isset($_GET['dele_sd'])){
		if (!$data->del_SD_request($serweb_auth, $_GET['dele_sd'], $_GET['dele_sd_dom'], $errors)) break;

        Header("Location: ".$sess->url("speed_dial.php?kvrk=".uniqID("")."&m_sd_deleted=1"));
		page_close();
		exit;
	}

	if ($edit_sd){
		if (false === $row = $data->get_SD_request($serweb_auth, $edit_sd, $edit_sd_dom, $errors)) break;
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"usrnm_from_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->username_from_req_uri)?$row->username_from_req_uri:"",
								 "valid_regex"=>$config->speed_dial['validation'],
								 "valid_e"=>$lang_str[$config->speed_dial['validation_msg']],
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"domain_from_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->domain_from_req_uri)?$row->domain_from_req_uri:$serweb_auth->domain,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"new_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->new_request_uri)?$row->new_request_uri:"",
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>$lang_str['fe_not_valid_sip'],
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_sd",
	                             "value"=>$edit_sd?$edit_sd:""));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_sd_dom",
	                             "value"=>$edit_sd_dom?$edit_sd_dom:""));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));


	if (isset($_POST['okey_x'])){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;

		if (!$data->update_SD_request($serweb_auth, $edit_sd, $_POST['new_uri'], $_POST['usrnm_from_uri'], $_POST['domain_from_uri'], $errors)) break;

        Header("Location: ".$sess->url("speed_dial.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	$speed_dials=array();

	if ($data){
		// get speed dial requests
		if (false === $speed_dials = $data->get_SD_requests($serweb_auth, $edit_sd?$edit_sd:NULL, $edit_sd_dom?$edit_sd_dom:NULL, $errors)) break;

	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

if (isset($_GET['m_sd_deleted'])){
	$message['short'] = $lang_str['msg_speed_dial_deleted_s'];
	$message['long']  = $lang_str['msg_speed_dial_deleted_l'];
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

if(!$speed_dials) $speed_dials = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref('speed_dials', $speed_dials);

$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'), array('before'=>'sip_address_completion(f.new_uri);'));

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('u_speed_dial.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>
