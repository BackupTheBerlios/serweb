<?
/*
 * $Id: index.php,v 1.18 2004/09/21 10:18:56 kozlik Exp $
 */

$_data_layer_required_methods=array('domain_exists', 'is_user_registered', 'get_privileges_of_user');
$_phplib_page_open = array("sess" => "phplib_Session");

require "prepend.php";

do{
	if (isset($_POST['okey_x'])){								// Is there data to process?
		if ($sess->is_registered('auth')) $sess->unregister('auth');

		//if fully quantified username is given
		if ($config->fully_qualified_name_on_login) {
			// parse username and domain from it
			if (ereg("^([^@]+)@(.+)", $_POST['uname'], $regs)){
				$username=$regs[1];
				$domain=$regs[2];

				if ($config->check_supported_domain_on_login){
					if (true !== $data->domain_exists($domain, $errors)){
						$errors[]=$lang_str['bad_username'];
						break;
					}
				}
			}
			else {
				$errors[]=$lang_str['bad_username'];
				break;
			}
		}
		else{
			$username=$_POST['uname'];
			$domain=$config->domain;
		}

		if (false === $uuid = $data->check_passw_of_user($username, $domain, $_POST['passw'], $errors)) break;

		//check for admin privilege
		if (false === $privileges = $data->get_privileges_of_user(
					new Cserweb_auth($uuid, $_POST['uname'], $config->domain), 
					array('change_privileges','is_admin'), 
					$errors)
			) break;

		$is_admin=false;
		foreach($privileges as $row)
			if ($row->priv_name=='is_admin' and $row->priv_value) $is_admin=true;

		if (!$is_admin) {$errors[]=$lang_str['bad_username']; break;}

		$sess->register('pre_uid');
		$pre_uid=$uuid;

		if (isset($_POST['remember_uname']) and $_POST['remember_uname']) 
			setcookie('serwebuser', $_POST['uname'], time()+31536000); //cookie expires in one year
		else
			setcookie('serwebuser', '', time()); //delete cookie

        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

$f = new form;                   // create a form object

$cookie_uname="";
if (isset($_COOKIE['serwebuser'])) $cookie_uname=$_COOKIE['serwebuser'];

$f->add_element(array("type"=>"text",
                             "name"=>"uname",
							 "size"=>20,
							 "maxlength"=>50,
                             "value"=>$cookie_uname,
							 "minlength"=>1,
							 "length_e"=>$lang_str['fe_not_filled_username'],
							 "extrahtml"=>"autocomplete='off' style='width:250px;'".
							 	($config->fully_qualified_name_on_login ? " onBlur='login_completion(this)'" : "")));

$f->add_element(array("type"=>"text",
                             "name"=>"passw",
                             "value"=>"",
							 "size"=>20,
							 "maxlength"=>25,
							 "pass"=>1,
							 "extrahtml"=>"style='width:250px;'"));

$f->add_element(array("type"=>"checkbox",
                             "name"=>"remember_uname",
                             "value"=>"1",
							 "checked"=>$cookie_uname?1:0));

$f->add_element(array("type"=>"submit",
                             "name"=>"okey",
                             "src"=>$config->img_src_path."butons/b_login.gif",
							 "extrahtml"=>"alt='login'"));


if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$passw="";
	$f->load_defaults();				// Load form with submitted data
}

if (isset($_GET['logout'])){
	$message['short'] = $lang_str['msg_logout_s'];
	$message['long']  = $lang_str['msg_logout_l'];
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>login_completion.js.php"></script>
<?
unset ($page_attributes['tab_collection']);
$page_attributes['logout']=false;
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$js_on_submit='';
if ($config->fully_qualified_name_on_login) $js_on_submit='login_completion(f.uname);';

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form', 'form_name'=>'login_form'), array('before'=>$js_on_submit));
$smarty->assign('domain',$config->domain);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('a_index.tpl');
?>

<?print_html_body_end();?>
<script language="JavaScript">
<!--
  if (document.forms['login_form'][0].value != '') {
      document.forms['login_form'][1].focus();
  } else {
      document.forms['login_form'][0].focus();
  }
// -->
</script>
</html>
<?page_close();?>
