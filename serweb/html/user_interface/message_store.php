<?
/*
 * $Id: message_store.php,v 1.8 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('set_timezone', 'get_user_real_name', 'del_im', 'del_vm', 'get_ims', 'get_vms');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	if (isset($_GET['dele_im'])){
		if (!$data->del_IM($serweb_auth, $_GET['dele_im'], $errors)) break;

		Header("Location: ".$sess->url("message_store.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($_GET['dele_vm'])){
		if (!$data->del_VM($serweb_auth, $_GET['dele_vm'], $errors)) break;

		Header("Location: ".$sess->url("message_store.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while(false);

do{
	$im_res=array();
	$vm_res=array();

	if ($data){
		$data->set_timezone($serweb_auth, $errors);

		if(false === $im_res = $data->get_IMs($serweb_auth, $errors)) break;

		if ($config->show_voice_silo) {
			if(false === $vm_res = $data->get_VMs($serweb_auth, $errors)) break;
		}
	}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

if(!$im_res) $im_res = array();
if(!$vm_res) $vm_res = array();

$smarty->assign_by_ref('parameters', $page_attributes);

$smarty->assign_by_ref('im_res', $im_res);
$smarty->assign_by_ref('vm_res', $vm_res);

$smarty->assign('show_voice_silo', $config->show_voice_silo);

$smarty->display('u_message_store.tpl');

?>
<?print_html_body_end();?>
</html>
<?page_close();?>
