<?
/*
 * $Id: find_user.php,v 1.16 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('find_users', 'get_user_real_name');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

if (!$sess->is_registered('sess_fu_search_filter')) $sess->register('sess_fu_search_filter');
if (!isset($sess_fu_search_filter)){
	$sess_fu_search_filter=array();
	$sess_fu_search_filter['act_row'] = 0;
	$sess_fu_search_filter['fname'] = '';
	$sess_fu_search_filter['lname'] = '';
	$sess_fu_search_filter['uname'] = '';
	$sess_fu_search_filter['sip_uri'] = '';
	$sess_fu_search_filter['alias'] = '';
	$sess_fu_search_filter['onlineonly'] = '';
}

if (isset($_GET['act_row'])) $sess_fu_search_filter['act_row'] = $_GET['act_row'];

if (isset($_POST['okey_x'])){ //create new search filter if form was submited
	$sess_fu_search_filter['act_row'] = 0;
	if (isset($_POST['fname'])) $sess_fu_search_filter['fname'] = $_POST['fname'];
	if (isset($_POST['lname'])) $sess_fu_search_filter['lname'] = $_POST['lname'];
	if (isset($_POST['uname'])) $sess_fu_search_filter['uname'] = $_POST['uname'];
	if (isset($_POST['sip_uri'])) $sess_fu_search_filter['sip_uri'] = $_POST['sip_uri'];
	if (isset($_POST['alias'])) $sess_fu_search_filter['alias'] = $_POST['alias'];
	
	if (isset($_POST['onlineonly'])) $sess_fu_search_filter['onlineonly'] = '1';
	else $sess_fu_search_filter['onlineonly'] = '0';
}

define("max_rows",50);

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

$action='';
if (isset($_GET['act_row']) or isset($_POST['okey_x'])) $action='browse';


do{
	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>$sess_fu_search_filter['fname'],
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>$sess_fu_search_filter['lname'],
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"uname",
								 "size"=>16,
								 "maxlength"=>50,
	                             "value"=>$sess_fu_search_filter['uname'],
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"sip_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>$sess_fu_search_filter['sip_uri'],
	                             "valid_regex"=>"^((".$reg->sip_address.")|())$",
	                             "valid_e"=>"not valid sip address",
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"alias",
								 "size"=>16,
								 "maxlength"=>50,
	                             "value"=>$sess_fu_search_filter['alias'],
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"checkbox",
	                             "value"=>1,
								 "checked"=>$sess_fu_search_filter['onlineonly'],
	                             "name"=>"onlineonly"));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_find.gif",
								 "extrahtml"=>"alt='find'"));

	$found_users=array();

	if (isset($_POST['okey_x'])){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}
	}

			/* Process data */           // Data ok;
		$found_users=array();
		$data->set_act_row($sess_fu_search_filter['act_row']);
		if(false === $found_users = $data->find_users($sess_fu_search_filter, $errors)) break;

}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>
<?
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
$page_attributes['selected_tab']="phonebook.php";
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$pager['url']="find_user.php?kvrk=".uniqid("")."&act_row=";
$pager['pos']=$data->get_act_row();
$pager['items']=$data->get_num_rows();
$pager['limit']=$data->get_showed_rows();
$pager['from']=$data->get_res_from();
$pager['to']=$data->get_res_to();

//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->max_showed_rows = $config->max_showed_rows;

if(!$found_users) $found_users = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref('pager', $pager);
$smarty->assign_by_ref("config", $cfg);		
$smarty->assign_by_ref('found_users', $found_users);

//$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'));
$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'), array('before'=>'sip_address_completion(f.sip_uri);'));

$smarty->assign_by_ref('action', $action);

$smarty->display('u_find_user.tpl');
?>


<?print_html_body_end();?>
</html>
<?page_close();?>
