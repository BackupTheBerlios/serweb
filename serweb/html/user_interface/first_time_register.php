<?
/*
 * $Id: first_time_register.php,v 1.2 2004/08/09 23:04:57 kozlik Exp $
 */

$_data_layer_required_methods=array('get_user_details_from_ldap', 'get_new_alias_number', 'add_new_alias',
									'register_user', 'get_aliases', 'get_user_details_from_ldap');

$_phplib_page_open = array("sess" => "phplib_Session_Pre_Auth",
						   "auth" => "phplib_Pre_Auth");
 
require "prepend.php";

do{
	$errors = array();
	
	$d=&$config->data_ldap->subscriber;

	if (isset($_GET['register']) and $_GET['register']){ // Should start register process?

		if (false === $usr_details = $data->get_user_details_from_ldap($serweb_auth, $errors)) break;

		if (false === $alias = $data->get_new_alias_number($serweb_auth->domain, $errors)) break;
		
		if (false === $data->add_new_alias($serweb_auth, $alias, $serweb_auth->domain, $errors)) break;

		if (false === $data->register_user($usr_details[$d['uuid']], $usr_details[$d['username']], $usr_details[$d['domain']], 
						$usr_details[$d['fname']], $usr_details[$d['lname']], $config->default_timezone, $errors)) break;
		

        Header("Location: ".$sess->url("first_time_register.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
	
	else{
		$aliases=array();
		$usr_details=array();
	
		// get aliases
		if (false === $aliases_res = $data->get_aliases($serweb_auth, $errors)) break;
		foreach($aliases_res as $row) $aliases[]=$row->username;
		
		// get user details
		if (false === $usr_details = $data->get_user_details_from_ldap($serweb_auth, $errors)) break;
		
	}
}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
unset ($page_attributes['tab_collection']);
$page_attributes['logout']=false;
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign('sip_address', 'sip:'.$serweb_auth->uname.'@'.$serweb_auth->domain);
$smarty->assign('sip_passw', $usr_details[$d['cpass']]);
$smarty->assign('aliases', implode(", ", $aliases));

$smarty->display('u_first_time_register.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>
