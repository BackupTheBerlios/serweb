<?
/*
 * $Id: ms_get_v_msg.php,v 1.5 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	if (!$db = connect_to_db($errors)) break;

	$q="select subject, file from ".$config->table_voice_silo.
		" where mid=".$mid." and r_uri like 'sip:".$auth->auth["uname"]."@".$config->default_domain."%'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); break;}
	if (!$res->numRows()) {$errors[]="Message not found or you haven't access to read message"; break;}
	$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
	$res->free();

	@$fp=fopen($config->voice_silo_dir.$row->file,'r');

	if (!$fp){$errors[]="Can't open message"; break;}

	Header("Content-Disposition: attachment;filename=".RawURLEncode(($row->subject?$row->subject:"received message").".wav"));
	Header("Content-type: audio/wav");

	@fpassthru($fp);
	@fclose($fp);

	page_close();
	exit;

}while(false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=get_user_name($db, $errors);
$page_attributes['selected_tab']="message_store.php";
print_html_body_begin($page_attributes);
?>

<div class="swBackToMainPage"><a href="<?$sess->purl("message_store.php?kvrk=".uniqid(""));?>">&lt;&lt;&lt; BACK</a></div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
