<?
/*
 * $Id: play_greeting.php,v 1.6 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('play_greeting', 'get_user_real_name');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	if (false === $data->play_greeting($serweb_auth, $errors)) break;

	page_close();
	exit;

}while(false);


/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
$page_attributes['selected_tab']="voicemail.php";
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$smarty->assign_by_ref('parameters', $page_attributes);

$smarty->display('u_play_greeting.tpl');

?>
<?print_html_body_end();?>
</html>
<?page_close();?>
