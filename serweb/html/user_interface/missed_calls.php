<?
/*
 * $Id: missed_calls.php,v 1.25 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('delete_user_missed_calls', 'set_timezone', 'get_missed_calls', 'get_user_real_name');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

if (!$sess->is_registered('sess_mc_act_row')) $sess->register('sess_mc_act_row');
if (!isset($sess_mc_act_row)) $sess_mc_act_row=0;

if (isset($HTTP_GET_VARS['act_row'])) $sess_mc_act_row=$HTTP_GET_VARS['act_row'];

do{
	if (isset($_GET['delete_calls'])){

		if (!$data->delete_user_missed_calls($serweb_auth, $page_loaded_timestamp, $errors)) break;
		$sess_mc_act_row=0;

        Header("Location: ".$sess->url("missed_calls.php?kvrk=".uniqID("")."&message=".RawURLEncode("calls deleted succesfully")));
		page_close();
		exit;
	}

	$mc_res=array();

    $data->set_timezone($serweb_auth, $errors);

	$data->set_act_row($sess_mc_act_row);

	if (false === $mc_res = $data->get_missed_calls($serweb_auth, $errors)) break;


}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->img_src_path = $config->img_src_path;

$pager['url']="missed_calls.php?kvrk=".uniqid("")."&act_row=";
$pager['pos']=$data->get_act_row();
$pager['items']=$data->get_num_rows();
$pager['limit']=$data->get_showed_rows();
$pager['from']=$data->get_res_from();
$pager['to']=$data->get_res_to();

if(!$mc_res) $mc_res = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref("config", $cfg);		

$smarty->assign_by_ref('pager', $pager);
$smarty->assign_by_ref('mc_res', $mc_res);

$smarty->assign('url_dele', $sess->url("missed_calls.php?kvrk=".uniqID("")."&delete_calls=1&page_loaded_timestamp=".time()));

$smarty->display('u_missed_calls.tpl');

?>

<?print_html_body_end();?>
</html>
<?page_close();?>
