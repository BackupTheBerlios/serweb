<?
/*
 * $Id: send_im.php,v 1.20 2005/03/02 15:33:55 kozlik Exp $
 */

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

require "prepend.php";

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object
$close_window=0;
$errors = array();

do{
	$f->add_element(array("type"=>"text",
	                             "name"=>"sip_address",
	                             "value"=>isset($_GET['sip_addr'])?$_GET['sip_addr']:"",
								 "size"=>16,
								 "maxlength"=>128,
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>$lang_str['fe_not_valid_sip'],
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
			$errors[]=$lang_str['fe_no_im'];
			break;
		}

		if (strlen($instant_message)>$config->im_length){
			$errors[]=$lang_str['fe_im_too_long'];
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
		    "Content-Type: text/plain; charset=".$lang_set['charset']."\n.\n".
		    str_Replace("\n.\n","\n. \n",$instant_message)."\n.\n\n";


		write2fifo($fifo_cmd, $errors, $status);
		if ($errors) break;
		/* we accept any status code beginning with 2 as ok */
		if (substr($status,0,1)!="2") {$errors[]=$status; break; }

        Header("Location: ".$sess->url("send_im.php?kvrk=".uniqID("")."&m_msg_send=".RawURLencode($sip_address)."&close_window=1"));
		page_close();
		exit;
	}

}while (false);

if (isset($_POST['okey_x'])){							//data isn't valid or error in sql
	$num_chars=$config->im_length-strlen($instant_message); //element is disable, set value manualy
	$f->load_defaults();				// Load form with submitted data
}

if (isset($_GET['m_msg_send'])){
	$message['short'] = $lang_str['msg_im_send_s'];
	$message['long']  = $lang_str['msg_im_send_l']." ".$_GET['m_msg_send'];
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
			alert("<?echo addslashes($lang_str['max_length_of_im']);?> "+max_length);
			return 0;
		}
		else
			f.num_chars.value=max_length-im_len;
	}

	function display_window(){
		var left=window.screen.width-350;
		wait_win=window.open('',"wait_win","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,top=50,width=300,height=130,left="+left);
		wait_win.document.write('<html><head><title><?echo addslashes($lang_str['please_wait']);?></title></head><body><div align="center"><?echo addslashes($lang_str['sending_message']);?><br><?echo addslashes($lang_str['please_wait']);?></div><div align="center"><img src="<?echo $config->img_src_path;?>send_im.gif" border="0"></div></body></html>');
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
							alert('".addslashes($lang_str['fe_no_im'])."');
							f.instant_message.focus();
							return (false);
						}

						display_window();
				  "));

$smarty->assign_by_ref('lang_str', $lang_str);

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
