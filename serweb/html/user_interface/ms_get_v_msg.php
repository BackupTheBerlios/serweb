<?
/*
 * $Id: ms_get_v_msg.php,v 1.6 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	if (!$data = CData_Layer::create($errors)) break;

	if (!$data->get_VM($auth->auth["uname"], $config->domain, $_GET['mid'], $errors)) break;

	page_close();
	exit;

}while(false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=$data->get_user_name($errors);
$page_attributes['selected_tab']="message_store.php";
print_html_body_begin($page_attributes);
?>

<div class="swBackToMainPage"><a href="<?$sess->purl("message_store.php?kvrk=".uniqid(""));?>">&lt;&lt;&lt; BACK</a></div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
