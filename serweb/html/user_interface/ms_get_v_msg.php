<?
/*
 * $Id: ms_get_v_msg.php,v 1.1 2003/02/19 22:16:35 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}
	
	$q="select subject, file from ".$config->table_voice_silo." where mid=".$mid." and r_uri like 'sip:".$auth->auth["uname"]."@".$config->default_domain."%'";
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

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
</head>
<?
	print_html_body_begin(7, true, true, get_user_name($errors));
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>


<a href="<?$sess->purl("message_store.php?kvrk=".uniqid(""));?>">&lt;&lt;&lt; BACK</a><br>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
