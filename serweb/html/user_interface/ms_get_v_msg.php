<?
/*
 * $Id: ms_get_v_msg.php,v 1.4 2004/03/11 22:30:00 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	$q="select subject, file from ".$config->table_voice_silo.
		" where mid=".$mid." and r_uri like 'sip:".$auth->auth["uname"]."@".$config->default_domain."%'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	if (!MySQL_num_rows($res)) {$errors[]="Message not found or you haven't access to read message"; break;}
	$row=MySQL_Fetch_Object($res);

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
$page_attributes['user_name']=get_user_name($errors);
$page_attributes['selected_tab']="message_store.php";
print_html_body_begin($page_attributes);
?>

<div class="swBackToMainPage"><a href="<?$sess->purl("message_store.php?kvrk=".uniqid(""));?>">&lt;&lt;&lt; BACK</a></div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
