<?
/*
 * $Id: voicemail.php,v 1.2 2003/03/17 20:01:25 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$f = new form;                  // create a form object

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	$f->add_element(array("type"=>"file",
	                             "name"=>"greeting",
	                             "value"=>"",
								 "extrahtml"=>"style='width:300px;'"));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_upload_greeting.gif",
								 "extrahtml"=>"alt='upload greeting'"));

	if (isset($okey_x)){						// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

		if (!is_uploaded_file($greeting)){
			$errors[]="you didn't select greeting file";
			break;
		}
		
		if (filesize($greeting)==0){
			$errors[]="greeting file is invalid";
			break;
		}

		if ($greeting_type != "audio/wav"){
			$errors[]="greeting file type must be audio/wav";
			break;
		}
		
		if (!copy ($greeting, $config->greetings_spool_dir.$config->default_domain."/".$auth->auth["uname"].".wav")){
			$errors[]="store greeting failed";
			break;
		}

        Header("Location: ".$sess->url("voicemail.php?kvrk=".uniqID("")."&message=".RawURLencode("Your greeting was succesfully stored")));
		page_close();
		exit;
	}

}while(false);

if (isset($okey_x)){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

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

<?$f->start("form");				// Start displaying form?>


<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td class="title" width="502">Customize greetings:</td></tr>
</table><br>

<table border="0" cellspacing="0" cellpadding="0" align="center" width="502">
<tr>
	<td align="left" class="f12"><?$f->show_element("greeting");?></td>
	<td align="right"><?$f->show_element("okey");?></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align="right"><a href="<?$sess->purl("play_greeting.php?kvrk=".uniqid(""));?>"><img src="<?echo $config->img_src_path;?>butons/b_download_greeting.gif" width="165" height="16" border="0"></a></td>
</tr>
</table>

<?$f->finish("

	if (f.greeting.value==''){
		alert(\"you didn't select greeting file\");
		f.greeting.focus();
		return (false);
	}

","");					// Finish form?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
