<?
/*
 * $Id: caller_screening.php,v 1.8 2004/08/09 23:04:57 kozlik Exp $
 */

$_data_layer_required_methods=array('get_user_real_name', 'del_cs_caller', 'get_cs_caller',
									'update_cs_caller', 'get_cs_callers');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

require "prepend.php";

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

set_global('edit_caller');

do{
	if (isset($_GET['dele_caller'])){
		if (!$data->del_CS_caller($serweb_auth, $_GET['dele_caller'], $errors)) break;

        Header("Location: ".$sess->url("caller_screening.php?kvrk=".uniqID("")."&m_cs_deleted=1"));
		page_close();
		exit;
	}

	if ($edit_caller){
		if (false === $row = $data->get_CS_caller($serweb_auth, $edit_caller, $errors)) break;
	}

	//create array of options of select
	$opt=array();
	foreach($config->calls_forwarding["screening"] as $k => $v){
		$opt[]=array("label" => $v->label, "value" => $k);
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"uri_re",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->uri_re)?$row->uri_re:"",
								 "minlength"=>1,
								 "length_e"=>$lang_str['fe_not_caller_uri'],
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"select",
	                             "name"=>"action_key",
								 "size"=>1,
	                             "value"=>isset($row->action)?(
								 			Ccall_fw::get_key($config->calls_forwarding["screening"],
																$row->action,
																$row->param1,
																$row->param2)
											):"",
								 "options"=>$opt,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_caller",
	                             "value"=>$edit_caller?$edit_caller:""));
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
		if (!$data->update_CS_caller($serweb_auth, $edit_caller, $_POST['uri_re'], $_POST['action_key'], $errors)) break;

        Header("Location: ".$sess->url("caller_screening.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	$caller_uris=array();

	if ($data){
		// get screenings
		if (false === $caller_uris = $data->get_CS_callers($serweb_auth, $edit_caller?$edit_caller:NULL, $errors)) break;

	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

if (isset($_GET['m_cs_deleted'])){
	$message['short'] = $lang_str['msg_caller_screening_deleted_s'];
	$message['long']  = $lang_str['msg_caller_screening_deleted_l'];
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

if(!$caller_uris) $caller_uris = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref('caller_uris', $caller_uris);

$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'), array('before'=>'sip_address_completion(f.new_uri);'));

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('u_caller_screening.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>
