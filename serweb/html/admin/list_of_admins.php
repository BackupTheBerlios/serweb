<?
/*
 * $Id: list_of_admins.php,v 1.7 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('get_admins');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin,change_priv");

if (!$sess->is_registered('sess_list_of_admins')) $sess->register('sess_list_of_admins');
if (!isset($sess_list_of_admins)) $sess_list_of_admins=new Cfusers(array('adminsonly'=>true, 'template'=>'_form_a_list_of_admins.tpl'));

$sess_list_of_admins->init();

do{
	$admins=array();

	$data->set_act_row($sess_list_of_admins->act_row);

	if (false === $admins = $data->get_admins($sess_list_of_admins, $errors)) break;

}while (false);

if (isset($_GET['m_priv_saved'])){
	$message['short']="Privileges updated";
	$message['long']="The privileges of user has been updated";
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$pager['url']="list_of_admins.php?kvrk=".uniqid("")."&act_row=";
$pager['pos']=$data->get_act_row();
$pager['items']=$data->get_num_rows();
$pager['limit']=$data->get_showed_rows();
$pager['from']=$data->get_res_from();
$pager['to']=$data->get_res_to();

if(!$admins) $admins = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref('pager', $pager);
$smarty->assign_by_ref('admins', $admins);

$smarty->assign('form', $sess_list_of_admins->get_form());

$smarty->display('a_list_of_admins.tpl');

?>
<?print_html_body_end();?>
</html>
<?page_close();?>
