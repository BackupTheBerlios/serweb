<?
/*
 * $Id: users.php,v 1.17 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('check_admin_perms_to_user', 'delete_sip_user', 'get_users');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

if (!$sess->is_registered('sess_fusers')) $sess->register('sess_fusers');
if (!isset($sess_fusers)) $sess_fusers=new Cfusers(array('template'=>'_form_a_users.tpl'));

if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

$sess_fusers->init();

do{
	if (false !== $usr = get_userauth_from_get_param('d')) { //delete user
		if (0 > ($pp=$data->check_admin_perms_to_user($serweb_auth, $usr, $errors))) break;
		if (!$pp){
			$errors[]="You can't delete user '".$usr->uname."' this user is from different domain";
			break;
		}

		if (!$data->delete_sip_user($usr, $errors)) break;

        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&message=".RawURLEncode("user deleted succesfully")));
		page_close();
		exit;
	}
}while (false);

do{
	$users=array();

	if ($data){

		$data->set_act_row($sess_fusers->act_row);

		if (false === $users = $data->get_users($sess_fusers, $serweb_auth->domain, $errors)) break;
	}

}while (false);

if (isset($_GET['m_acl_updated'])){
	$message['short']="ACL updated";
	$message['long']="Access control list of user has been updated";
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>functions.js"></script>
<?
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$pager['url']="users.php?kvrk=".uniqid("")."&act_row=";
$pager['pos']=$data->get_act_row();
$pager['items']=$data->get_num_rows();
$pager['limit']=$data->get_showed_rows();
$pager['from']=$data->get_res_from();
$pager['to']=$data->get_res_to();

if(!$users) $users = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref('pager', $pager);
$smarty->assign_by_ref('users', $users);

$smarty->assign('form', $sess_fusers->get_form());

$smarty->display('a_users.tpl');

?>

<?print_html_body_end();?>
</html>
<?page_close();?>
