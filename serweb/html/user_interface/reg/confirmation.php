<?
/*
 * $Id: confirmation.php,v 1.25 2004/08/26 09:23:34 kozlik Exp $
 */

$_data_layer_required_methods=array('move_user_from_pending_to_subscriber', 'get_new_alias_number', 'add_new_alias',
									'del_user_from_pending', 'del_user_from_subscriber',);

$_phplib_page_open = array("sess" => "phplib_Session");

include "reg_jab.php";
require "prepend.php";

put_headers();

if (isset($_GET['ok'])) $ok=$_GET['ok']; else $ok=null;
if (isset($_GET['nr'])) $nr=$_GET['nr']; else $nr=null;

$remove_from_subscriber=false;
do{
	if (isset($nr)){  // Is there data to process?

		if (false === $user_id=$data->move_user_from_pending_to_subscriber($nr, $errors)) break;
		if (true === $user_id) {$ok=1; break;}
		$remove_from_subscriber=true;
		
		// generate alias number 
		if (false === $alias=$data->get_new_alias_number($user_id->domain, $errors)) break;

		// add alias to fifo
		$message=$data->add_new_alias($user_id, $alias, $user_id->domain, $errors);
		if ($errors) break;

		$remove_from_subscriber=false;
		if (!$data->del_user_from_pending($nr, $errors)) break;

		if ($config->setup_jabber_account) {
			# Jabber Gateway registration
			$res = reg_jab($user_id->uname);
			if($res!=0) {
				$res=$res+1; Header("Location: confirmation.php?ok=$res");
			} else {
				Header("Location: confirmation.php?ok=1");
			}
		} else {
				Header("Location: confirmation.php?ok=1");
		}

		page_close();
		exit;
	}
}while (false);

if ($remove_from_subscriber) $data->del_user_from_subscriber($nr, $errors);

if ($ok>=2 && $ok<10)
	error_log("SERWEB:jabber registration failed: <".$user_id->uname."> [$ok]\n");

/* ----------------------- HTML begin ---------------------- */ 
print_html_head();
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign('domain',$config->domain);
$smarty->assign('result',$ok);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('ur_confirmation.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>

