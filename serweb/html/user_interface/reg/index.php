<?
/*
 * $Id: index.php,v 1.17 2004/11/10 13:13:06 kozlik Exp $
 */

$_data_layer_required_methods=array('get_time_zones',  'is_user_exists', 'add_user_to_subscriber');
$_phplib_page_open = array("sess" => "phplib_Session");

require "prepend.php";

$errors = array();

do{
	$f = new form;                   // create a form object

	$opt=$data->get_time_zones($errors);
	$options[]=array("label"=>$lang_str['choose_timezone'],"value"=>"");
	foreach ($opt as $v) $options[]=array("label"=>$v,"value"=>$v);

	$f->add_element(array("type"=>"select",
								 "name"=>"timezone",
								 "options"=>$options,
								 "size"=>1,
	                             "valid_e"=>$lang_str['fe_not_choosed_timezone'],
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"uname",
								 "size"=>23,
								 "maxlength"=>50,
	                             "value"=>"",
								 "minlength"=>1,
								 "length_e"=>$lang_str['fe_not_filled_username'],
	                             "valid_regex"=>$reg_validate_username,
	                             "valid_e"=>$lang_str['fe_uname_not_follow_conventions'],
								 "extrahtml"=>"autocomplete'off' style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"passwd",
	                             "value"=>"",
								 "size"=>23,
								 "maxlength"=>25,
								 "pass"=>1,
								 "minlength"=>1,
								 "length_e"=>$lang_str['fe_not_filled_password'],
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"passwd_r",
	                             "value"=>"",
								 "size"=>23,
								 "maxlength"=>25,
								 "pass"=>1,
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>23,
								 "maxlength"=>25,
	                             "value"=>"",
								 "minlength"=>1,
								 "length_e"=>$lang_str['fe_not_filled_your_fname'],
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>23,
								 "maxlength"=>45,
	                             "value"=>"",
								 "minlength"=>1,
								 "length_e"=>$lang_str['fe_not_filled_your_lname'],
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"email",
								 "size"=>23,
								 "maxlength"=>50,
	                             "value"=>"",
	                             "valid_regex"=>$reg_validate_email,
	                             "valid_e"=>$lang_str['fe_not_valid_email'],
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"phone",
								 "size"=>23,
								 "maxlength"=>15,
	                             "value"=>"",
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"textarea",
	                             "name"=>"terms",
	                             "value"=>$config->terms_and_conditions,
								 "rows"=>8,
								 "cols"=>38,
	                             "wrap"=>"soft",
								 "extrahtml"=>"style='width:415px;'"));
	$f->add_element(array("type"=>"checkbox",
	                             "name"=>"accept",
	                             "value"=>1,
								 "extrahtml"=>"style=''"));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_register.gif",
								 "extrahtml"=>"alt='register'"));

	if (isset($_POST['okey_x'])){				// Is there data to process?

		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

		if ($passwd and ($passwd != $passwd_r)){
			$errors[]=$lang_str['fe_passwords_not_match']; break;
		}

		if (!$accept){
			$errors[]=$lang_str['fe_not_accepted_terms']; break;
		}

			/* Process data */           // Data ok;


		$user_exists=$data->is_user_exists($uname, $config->default_domain, $errors);
		if ($errors) break;
		if ($user_exists) {$errors[]=$lang_str['fe_uname_already_choosen_1']." '$uname' ".$lang_str['fe_uname_already_choosen_2']; break;}

		$confirm=md5(uniqid(rand()));

		if (!$data->add_user_to_subscriber($uname, $config->realm, $passwd, $fname, $lname, $phone, $email, $timezone, $confirm, $config->data_sql->table_pending, $errors)) break;

		$sip_address="sip:".$uname."@".$config->default_domain;

		$mail_body=str_replace("#confirm#", $confirm, $config->mail_register);
		$mail_body=str_replace("#sip_address#", $sip_address, $mail_body);

		if (!send_mail($email, $config->register_subj, $mail_body)){
			$errors[]=$lang_str['err_sending_mail']; break;
		}

        Header("Location: ".$sess->url("finish.php?sip_address=".RawURLEncode($sip_address)));
		page_close();
		exit;
	}
}while (false);



if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$terms=$config->terms_and_conditions;
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form', 'form_name'=>'login_form'),
		array('after'=>"
			if (f.passwd.value!=f.passwd_r.value){
				alert('".addslashes($lang_str['fe_passwords_not_match'])."');
				f.passwd.focus();
				return (false);
			}

			if (!f.accept.checked){
				alert('".addslashes($lang_str['fe_not_accepted_terms'])."');
				f.accept.focus();
				return (false);
			}
		"));
$smarty->assign('domain',$config->domain);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('ur_index.tpl');

?>
<?print_html_body_end();?>
</html>
<?page_close();?>
