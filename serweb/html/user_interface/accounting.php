<?
/*
 * $Id: accounting.php,v 1.25 2004/08/09 23:04:57 kozlik Exp $
 */

$_data_layer_required_methods=array('check_admin_perms_to_user', 'set_timezone', 'get_acc_entries', 'get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

require "prepend.php";

if (!$sess->is_registered('sess_acc_act_row')) $sess->register('sess_acc_act_row');
if (!isset($sess_acc_act_row)) $sess_acc_act_row=0;

if (isset($HTTP_GET_VARS['act_row'])) $sess_acc_act_row=$HTTP_GET_VARS['act_row'];
				 
$acc_res=null;
$uid=null; //contains Cserweb_auth if admin editing this user
do{
	// get $user_id of user which accout should be displayed
	if ($perm->have_perm("admin")){
		if (false === $uid = get_userauth_from_get_param('u')) {
			$user_id=$serweb_auth;
		}
		else {
			if (0 > ($pp=$data->check_admin_perms_to_user($serweb_auth, $uid, $errors))) break;
			if (!$pp){
				die("You can't manage user '".$uid->uname."' this user is from different domain");
				break;
			}
	
			$user_id=$uid;
		}
	}
	else $user_id=$serweb_auth;
	
	$data->set_timezone($user_id, $errors);

	$data->set_act_row($sess_acc_act_row);

	if (false === $acc_res=$data->get_acc_entries($user_id, $errors)) break;

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$come_from_admin_interface = ($perm->have_perm("admin") and $uid);

if ($come_from_admin_interface){

	/* script is called from admin interface, load page attributes of admin interface */
	require ("../admin/page_attributes.php");
	$page_attributes['selected_tab']="users.php";

	print_html_body_begin($page_attributes);
	echo "<div class=\"swNameOfUser\">".$lang_str['user'].": ".$uid->uname."</div>";
}
else {
	$page_attributes['user_name']=$data->get_user_real_name($user_id, $errors);
	print_html_body_begin($page_attributes);
}

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$pager['url']="accounting.php?kvrk=".uniqid("").($uid?("&".userauth_to_get_param($uid, 'u')):"")."&act_row=";
$pager['pos']=$data->get_act_row();
$pager['items']=$data->get_num_rows();
$pager['limit']=$data->get_showed_rows();
$pager['from']=$data->get_res_from();
$pager['to']=$data->get_res_to();

if(!$acc_res) $acc_res = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref("come_from_admin_interface", $come_from_admin_interface);		

$smarty->assign_by_ref('pager', $pager);
$smarty->assign_by_ref('acc_res', $acc_res);

$smarty->assign('url_admin', $sess->url($config->admin_pages_path."users.php?kvrk=".uniqid("")));

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('u_accounting.tpl');

?>


<?print_html_body_end();?>
</html>
<?page_close();?>