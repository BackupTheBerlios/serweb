<?
require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}
	
	$f->add_element(array("type"=>"text",
	                             "name"=>"sip_address",
								 "size"=>16,
								 "maxlength"=>128,
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>"not valid sip address",
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"textarea",
	                             "name"=>"instant_message",
								 "rows"=>6,
								 "cols"=>40,
								 "wrap"=>"soft",
								 "extrahtml"=>"onBlur='countit(this.form)' onChange='countit(this.form)' onClick='countit(this.form)' onFocus='countit(this.form)' onKeyUp='countit(this.form)'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"num_chars",
								 "value"=>$config->im_length,
								 "size"=>5,
								 "maxlength"=>5,
								 "extrahtml"=>"disabled style='border:0; background-color:#FFFFFF; font-size:12px; width:33px'"));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_send.gif",
								 "extrahtml"=>"alt='send'"));
	
	
	if (isset($okey_x)){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}
		
		if (!$instant_message){
			$errors[]="you didn't write massage";
			break;
		}
		
		if (strlen($instant_message)>$config->im_length){
			$errors[]="instant message is too long";
			break;
		}

		/* Process data */           // Data ok; 

		/* construct FIFO command */
		$fifo_cmd=":t_uac_from:".$config->reply_fifo_filename."\n".
			"sip:".$auth->auth["uname"]."@".$config->default_domain."\n".
		    "MESSAGE\n".
			$sip_address."\n".
		    "p-version: ".$config->psignature."\n".
		    "Contact: ".$config->web_contact."\n".
		    "Content-Type: text/plain; charset=UTF-8\n\n".
		    str_Replace("\n.\n","\n. \n",$instant_message)."\n.\n\n";


		write2fifo($fifo_cmd, $errors);

		if ($errors) break;		
		
        Header("Location: ".$sess->url("send_im.php?kvrk=".uniqID("")."&message=".RawURLencode("message was send successfully to address ".$sip_address)));
		page_close();
		exit;
	}
	else{			// no data

	
	}
						 
}while (false);

if ($okey_x){							//data isn't valid or error in sql
	$num_chars=$config->im_length-strlen($instant_message); //element is disable, set value manualy
	$f->load_defaults();				// Load form with submitted data
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
<script language="JavaScript">
<!--
	var max_length=<?echo $config->im_length;?>;
	var im_len=0;
	
	function countit(f){
		im_len=f.instant_message.value.length;

		if (im_len>max_length){
			f.instant_message.value=f.instant_message.value.substr(0, max_length);
			alert("Max length of instant message is "+max_length);
			return 0;
		}
		else
			f.num_chars.value=max_length-im_len;
	}
	
	function sip_address_completion(adr){
		var default_domain='<?echo $config->default_domain;?>';
		
		var re = /^<?echo str_replace('/','\/',$reg->user);?>$/i;
//		var re = new RegExp("^<?echo $reg->user;?>$","i");
		if (re.test(adr.value)) {
			adr.value=adr.value+'@'+default_domain;
		}

		var re = /^<?echo str_replace('/','\/',$reg->address);?>$/i
//		var re = new RegExp("^<?echo $reg->address;?>$","i");
		var re2= /^sip:/i;
		if (re.test(adr.value) && !re2.test(adr.value)) {
			adr.value='sip:'+adr.value;
		}
	}
//-->
</script>
</head>
<?
	print_html_body_begin(5, true, true);
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="left" class="f12b">
		<table border="0" cellspacing="0" cellpadding="0" align="left">
		<tr>
		<td class="f12b">sip address of recipient: &nbsp;&nbsp;</td>
		<td class="f12b"><?$f->show_element("sip_address");?></td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td align="left" class="f12b">text of message:</td>
	</tr>
	<tr>
	<td><?$f->show_element("instant_message");?></td>
	</tr>
	<tr><td class="f12">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr><td class="f12">Remaining </td><td><?$f->show_element("num_chars");?></td><td class="f12">characters</td></tr>
		</table>
	</td></tr>
	<tr>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("
	if (f.instant_message.value==''){
		alert(\"you didn't write massage\");
		f.instant_message.focus();
		return (false);
	}
","sip_address_completion(f.sip_address);");					// Finish form?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
