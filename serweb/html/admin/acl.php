<?
/*
 * $Id: acl.php,v 1.14 2004/08/10 17:33:50 kozlik Exp $
 */

$_data_layer_required_methods=array('check_admin_perms_to_user', 'get_admin_acl_privileges', 
		'get_acl_of_user', 'update_acl_of_user');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

require "prepend.php";

$perm->check("admin");

$f = new form;                   // create a form object
$grp_val=array();
$ACL_control=array();

do{
	if (false !== $uid = get_userauth_from_get_param('u')) {
		if (0 > ($pp=$data->check_admin_perms_to_user($serweb_auth, $uid, $errors))) break;
		if (!$pp){
			$errors[]=$lang_str['err_admin_can_not_manage_user_1']." '".$uid->uname."' ".$lang_str['err_admin_can_not_manage_user_2'];
			break;
		}
	}
	else {
		$errors[]=$lang_str['err_unknown_user']; break;
	}
	
	/* get admin ACL control privileges */
	if (false === $ACL_control = $data->get_admin_ACL_privileges($serweb_auth, $errors)) break;

	/* get access control list of user */
	if (false === $grp_val_tmp = $data->get_ACL_of_user($uid, $errors)) break;
	$grp_val=array();
	foreach($grp_val_tmp as $val) $grp_val[]=$val['grp'];
	unset($grp_val_tmp);

	/* add form elements */
	foreach ($ACL_control as $row){
		$f->add_element(array("type"=>"checkbox",
		                      "name"=>"chk_".$row,
		                      "checked"=>in_array($row, $grp_val)?"1":"0",
		                      "value"=>"1"));

		$f->add_element(array("type"=>"hidden",
		                      "name"=>"hidden_".$row,
		                      "value"=>in_array($row, $grp_val)?"1":"0"));
	}

	userauth_to_form($uid, 'u', $f);

	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));

	if (isset($_POST['okey_x'])){					// Is there data to process?

		foreach ($ACL_control as $row){
			//if checkbox isn't checked, assign value "0" to variable
			if (!isset($_POST["chk_".$row])) $_POST["chk_".$row] = "0";

			//if state of checkbox was changed
			if ($_POST["chk_".$row] != $_POST["hidden_".$row]){
				if (!$data->update_ACL_of_user($uid, $row, $_POST["chk_".$row]?'set':'del', $errors)) break;
			}
		}

		if (isset($errors) and $errors) break;

        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&m_acl_updated=1"));
		page_close();
		exit;
	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['selected_tab']="users.php";
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

if(!$ACL_control) $ACL_control = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref('ACL_control', $ACL_control);

$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'));

$smarty->assign('uname', $uid->uname);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('a_acl.tpl');

?>

<?print_html_body_end();?>
</html>
<?page_close();?>
