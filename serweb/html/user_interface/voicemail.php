<?
/*
 * $Id: voicemail.php,v 1.8 2004/08/09 12:21:28 kozlik Exp $
 */

$_data_layer_required_methods=array('store_greeting', 'get_user_real_name');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$f = new form;                  // create a form object

do{
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

		if (false === $data->store_greeting($serweb_auth, $greeting, $errors)) break;

        Header("Location: ".$sess->url("voicemail.php?kvrk=".uniqID("")."&m_file_uploaded=1"));
		page_close();
		exit;
	}

}while(false);

if (isset($okey_x)){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

if (isset($_GET['m_file_uploaded'])){
	$message['short']="Greeting stored";
	$message['long']="Your greeting was succesfully stored";
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->img_src_path = $config->img_src_path;

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref("config", $cfg);		

$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'), 
		array('after'=>"
			if (f.greeting.value==''){
				alert(\"you didn't select greeting file\");
				f.greeting.focus();
				return (false);
			}
		"));

$smarty->display('u_voicemail.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>
