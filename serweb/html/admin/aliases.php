<?
/*
 * $Id: aliases.php,v 1.1 2004/08/09 13:08:10 kozlik Exp $
 */

$_data_layer_required_methods=array('check_admin_perms_to_user', 'delete_alias', 'is_alias_exists', 
		'add_new_alias', 'get_aliases');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

$f = new form;                  // create a form object

$action='';
if (isset($_REQUEST['cancel_x'])) $action='cancel';
else if (isset($_REQUEST['edit_alias']) and $_REQUEST['edit_alias']) $action='edit';
else if (isset($_GET['dele_alias'])) $action='delete';

do{
	if (!$data = CData_Layer::create($errors)) break;

	if (false !== $uid = get_userauth_from_get_param('u')) {
		if (0 > ($pp=$data->check_admin_perms_to_user($serweb_auth, $uid, $errors))) break;
		if (!$pp){
			$errors[]="You can't manage user '".$uid->uname."' this user is from different domain";
			break;
		}
	}
	else {
		$errors[]="unknown user"; break;
	}

	if ($action=='cancel') {
		Header("Location: ".$sess->url("aliases.php?kvrk=".uniqID("")."&".userauth_to_get_param($uid, 'u')));
		page_close();
		exit;
	}
	
	if ($action=='delete'){
		if (false === $data->delete_alias($uid, $_GET['dele_alias'], $uid->domain, $errors)) break;
	
        Header("Location: ".$sess->url("aliases.php?kvrk=".uniqID("")."&m_alias_deleted=1&".userauth_to_get_param($uid, 'u')));
		page_close();
		exit;
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"alias",
								 "size"=>16,
								 "maxlength"=>64,
	                             "value"=>$action=='edit'?$_REQUEST['edit_alias']:"",
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_alias",
	                             "value"=>isset($_REQUEST['edit_alias'])?$_REQUEST['edit_alias']:"",
								 "extrahtml"=>"style='width:120px;'"));

	userauth_to_form($uid, 'u', $f);

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

		if (!($action=='edit' and $_REQUEST['alias']==$_REQUEST['edit_alias'])){
			//process data only if data was changed		
		
			//check if alias exists
			$alias_exists = $data->is_alias_exists($_REQUEST['alias'], $uid->domain, $errors);
			if ($alias_exists < 0) break;
			if ($alias_exists){ $errors[]="The Phone Number: ".$_REQUEST['alias']." already exists"; break; }
				
			if ($action=='edit'){
				//delete alias
				if (false === $data->delete_alias($uid, $_REQUEST['edit_alias'], $uid->domain, $errors)) break;
			}
				
			if (!$data->add_new_alias($uid, $_REQUEST['alias'], $uid->domain, $errors)) break;
		}

        Header("Location: ".$sess->url("aliases.php?kvrk=".uniqID("").
				($action=='edit'?"&m_alias_updated=1":"&m_alias_added=1").
				"&".userauth_to_get_param($uid, 'u')));
		page_close();
		exit;
	}
}while (false);

do{
	$aliases=array();
	if ($data){
		$al_res=array();
		// get aliases
		if (false === $al_res = $data->get_aliases($uid, $errors)) break;
		
		foreach($al_res as $k=>$row){
			if ($action=='edit' and $row->username==$_REQUEST['edit_alias']) continue;
			$aliases[$k]['username'] = $row->username;
			$aliases[$k]['domain'] = $row->domain;
			$aliases[$k]['url_dele'] = $sess->url("aliases.php?kvrk=".uniqID("")."&dele_alias=".$row->username."&".userauth_to_get_param($uid, 'u'));
			$aliases[$k]['url_edit'] = $sess->url("aliases.php?kvrk=".uniqID("")."&edit_alias=".$row->username)."&".userauth_to_get_param($uid, 'u');
		}
		
	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

if (isset($_GET['m_alias_deleted'])){
	$message['short']="Phone Number Deleted";
	$message['long']="The Phone Number of user has been deleted";
}

if (isset($_GET['m_alias_updated'])){
	$message['short']="Phone Number Updated";
	$message['long']="Your changes have been saved.";
}

if (isset($_GET['m_alias_added'])){
	$message['short']="Phone Number Added";
	$message['long']="The Phone Number has been added to user";
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['selected_tab']="users.php";
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

if(!$aliases) $aliases = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref('al_res', $aliases);

$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'));

$smarty->assign('uname', $uid->uname);
$smarty->assign_by_ref('action', $action);

$smarty->display('a_aliases.tpl');

?>


<?print_html_body_end();?>
</html>
<?page_close();?>
