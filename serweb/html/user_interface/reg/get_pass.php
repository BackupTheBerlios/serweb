<?
/*
 * $Id: get_pass.php,v 1.13 2004/08/10 17:33:50 kozlik Exp $
 */

$_data_layer_required_methods=array('get_sip_user');
$_phplib_page_open = array("sess" => "phplib_Session");

require "prepend.php";

do{
	if (isset($_POST['okey_x'])){								// Is there data to process?
		if (!$data = CData_Layer::create($errors)) break;

		if (false === $sip_user = $data->get_sip_user($_POST['uname'], $config->domain, $errors)) break;

		$pre_uid=$sip_user['uuid'];
		$pre_uid_expires=time()+$config->pre_uid_expires;

		$my_sess=new phplib_Session();
		$my_sess->set_container();
		$my_sess->name=$my_sess->classname;
		$my_sess->id = $my_sess->that->ac_newid(md5(uniqid($my_sess->magic)), $my_sess->name);
		$my_sess->register("pre_uid");
		$my_sess->register("pre_uid_expires");
		$my_sess->freeze();

		$mail_body=str_replace("#session#", $my_sess->name."=".$my_sess->id, $config->mail_forgot_pass);

		if (!send_mail($sip_user['email'], $config->forgot_pass_subj, $mail_body)){
			$errors[]="Sorry, there was an error when sending mail. Please try again later."; break;
		}

        Header("Location: ".$sess->url("../index.php?kvrk=".uniqID("")."&message=".RawURLEncode("Login information was send to your email address")));
		page_close();
		exit;

	}
}while (false);

$f = new form;                   // create a form object

$f->add_element(array("type"=>"text",
                             "name"=>"uname",
							 "size"=>20,
							 "maxlength"=>50,
                             "value"=>"",
							 "minlength"=>1,
							 "length_e"=>"you must fill username",
							 "extrahtml"=>"autocomplete='off' style='width:250px;'"));
$f->add_element(array("type"=>"submit",
                             "name"=>"okey",
                             "src"=>$config->img_src_path."butons/b_get_pass.gif",
							 "extrahtml"=>"alt='get password'"));


if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form', 'form_name'=>'login_form'), array());
$smarty->assign('domain',$config->domain);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('ur_get_pass.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>
