<?
/*
 * $Id: send_im.php,v 1.17 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('get_user_real_name');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object
$close_window=0;

do{
	$f->add_element(array("type"=>"text",
	                             "name"=>"sip_address",
	                             "value"=>isset($_GET['sip_addr'])?$_GET['sip_addr']:"",
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
								 "extrahtml"=>"onBlur='countit(this.form);' onChange='countit(this.form);' onClick='countit(this.form);' onFocus='countit(this.form);' onKeyUp='countit(this.form);'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"num_chars",
								 "value"=>$config->im_length,
								 "size"=>5,
								 "maxlength"=>5,
								 "extrahtml"=>"disabled class='swFormElementInvisible' style='border:0; width:33px'"));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_send.gif",
								 "extrahtml"=>"alt='send'"));


	if (isset($_POST['okey_x'])){						// Is there data to process?
		$close_window=1;
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
		$fifo_cmd=":t_uac_dlg:".$config->reply_fifo_filename."\n".
		    "MESSAGE\n".
			$sip_address."\n".
			".\n".
			"From: sip:".$serweb_auth->uname."@".$serweb_auth->domain."\n".
			"To: <".$sip_address.">\n".
		    "p-version: ".$config->psignature."\n".
		    "Contact: <".$config->web_contact.">\n".
		    "Content-Type: text/plain; charset=UTF-8\n.\n".
		    str_Replace("\n.\n","\n. \n",$instant_message)."\n.\n\n";


		write2fifo($fifo_cmd, $errors, $status);
		if ($errors) break;
		/* we accept any status code beginning with 2 as ok */
		if (substr($status,0,1)!="2") {$errors[]=$status; break; }

		$message="message was sent successfully to address ".$sip_address;

        Header("Location: ".$sess->url("send_im.php?kvrk=".uniqID("")."&message=".RawURLencode($status)."&close_window=1"));
		page_close();
		exit;
	}

}while (false);

if (isset($_POST['okey_x'])){							//data isn't valid or error in sql
	$num_chars=$config->im_length-strlen($instant_message); //element is disable, set value manualy
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>
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

	function display_window(){
		var left=window.screen.width-350;
		wait_win=window.open('',"wait_win","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,top=50,width=300,height=130,left="+left);
		wait_win.document.write('<html><head><title>please wait!</title></head><body><div align="center">sending message<br>please wait!</div><div align="center"><img src="<?echo $config->img_src_path;?>send_im.gif" border="0"></div></body></html>');
		wait_win.document.close();
	}

	function close_window(){
		wait_win=window.open('',"wait_win","width=1,height=1,top=0,left=0");	//get reference to window
		wait_win.close();						//close the window
	}

//-->
</script>
<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>

<?
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;


$smarty->assign_by_ref('parameters', $page_attributes);

$smarty->assign_phplib_form('form', $f, 
			array('jvs_name'=>'form'), 
			array('before'=>'sip_address_completion(f.sip_address);',
			      'after'=>"
						if (f.instant_message.value==''){
							alert(\"you didn't write message\");
							f.instant_message.focus();
							return (false);
						}
					
						display_window();
				  "));

$smarty->display('u_send_im.tpl');

?>

<?print_html_body_end();?>
<? if ($close_window or (isset($_GET['close_window']) and $_GET['close_window'])){?>
<script language="JavaScript">
<!--
	close_window();
//-->
</script>
<?}?>
</html>
<?page_close();?>
