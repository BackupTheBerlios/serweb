<?
/*
 * $Id: play_greeting.php,v 1.5 2004/04/14 20:51:31 kozlik Exp $
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

do{
	if (!$data = CData_Layer::create($errors)) break;
}while(false);


/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=$data->get_user_name($errors);
$page_attributes['selected_tab']="voicemail.php";
print_html_body_begin($page_attributes);
?>

<div class="swBackToMainPage"><a href="<?$sess->purl("voicemail.php?kvrk=".uniqid(""));?>">&lt;&lt;&lt; BACK</a></div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
