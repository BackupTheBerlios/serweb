<?
/*
 * $Id: play_greeting.php,v 1.1 2003/03/17 18:18:35 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

do{

	@$fp=fopen($config->greetings_spool_dir.$config->default_domain."/".$auth->auth["uname"].".wav", 'rb');
	if (!$fp){
		//try open default greeting
		@$fp=fopen($config->greetings_spool_dir."default.wav", 'rb');
		if (!$fp){$errors[]="Can't open greeting"; break;}
	}

	Header("Content-Disposition: attachment;filename=".RawURLEncode("greeting.wav"));
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
	print_html_body_begin(8, true, true, get_user_name($errors));
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>


<a href="<?$sess->purl("voicemail.php?kvrk=".uniqid(""));?>">&lt;&lt;&lt; BACK</a><br>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
