<?
/*
 * $Id: voicemail.php,v 1.5 2004/03/24 21:39:47 kozlik Exp $
 */

require "prepend.php";

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

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=get_user_name($errors);
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">Customize greetings:</h2>

<div class="swForm">
<?$f->start("form");				// Start displaying form?>
<table border="0" cellspacing="0" cellpadding="0" align="center" class="swWidthAsTitle">
<tr>
	<td align="left"><?$f->show_element("greeting");?></td>
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
</div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
