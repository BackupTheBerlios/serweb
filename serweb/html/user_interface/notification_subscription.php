<?
/*
 * $Id: notification_subscription.php,v 1.13 2004/08/09 23:04:57 kozlik Exp $
 */

$_data_layer_required_methods=array('subscribe_event', 'unsubscribe_event', 'get_events', 'get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	if (isset($_GET['uri']) and isset($_GET['desc'])){
		if (!$data->subscribe_event($serweb_auth, $_GET['uri'], $_GET['desc'], $errors)) break;

        Header("Location: ".$sess->url("notification_subscription.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($_GET['dele_id'])){
		if (!$data->unsubscribe_event($serweb_auth, $_GET['dele_id'], $errors)) break;

        Header("Location: ".$sess->url("notification_subscription.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

}while (false);

do{
	$subs_events=array();
	$other_events=array();

	if ($data){
		if (false === $subs_events = $data->get_events($serweb_auth, $errors)) break;
	}

	//prepare associative array of other_events from $config->sub_not
	foreach ($config->sub_not as $row){
		$other_events[$row->uri]['description'] = $row->desc;
		$other_events[$row->uri]['url_subsc'] = $sess->url("notification_subscription.php?kvrk=".uniqid("")."&desc=".RawURLEncode($row->desc)."&uri=".RawURLEncode($row->uri));
	}
	
	//unset subsribed events from other_events
	foreach($subs_events as $row){
		unset($other_events[$row['uri']]);
	}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

if(!$subs_events) $subs_events = array();
if(!$other_events) $other_events = array();

$smarty->assign_by_ref('parameters', $page_attributes);

$smarty->assign_by_ref('subs_events', $subs_events);
$smarty->assign_by_ref('other_events', $other_events);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('u_notification_subscription.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>
