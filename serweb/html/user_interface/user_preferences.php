<?
/*
 * $Id: user_preferences.php,v 1.10 2004/08/09 23:04:57 kozlik Exp $
 */

$_data_layer_required_methods=array('get_attributes', 'get_att_values', 'update_attribute_of_user', 'get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

require "prepend.php";
require "../user_preferences.php";

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object
$usr_pref = new User_Preferences();

function format_attributes_for_output($attributes, &$js_on_subm){
	global $sess;

	$out=array();
	$i=0;
	foreach($attributes as $att){
		if ($att->att_rich_type == "sip_adr") $js_on_subm.="sip_address_completion(f.".$att->att_name.");";

		$out[$i]['att_name'] = $att->att_name;
		$i++;
	}
	
	return $out;
}



do{
	$attributes=array();

	//get list of attributes
	if (false === $attributes = $data->get_attributes(NULL, $errors)) break;

	// get attributes values
	if (false === $data->get_att_values($serweb_auth, $attributes, $errors)) break;

	// add elements to form object
	foreach($attributes as $att){
		$usr_pref->form_element($f, $att->att_name, $att->att_value, $att->att_rich_type, $att->att_type_spec);
	}

	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));

	if (isset($_POST['okey_x'])){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			// Process data            // Data ok;

		//check values of attributes and format its
		foreach($attributes as $att){
			if (!$usr_pref->format_inputed_value($HTTP_POST_VARS[$att->att_name], $att->att_rich_type, $att->att_type_spec)){
				$errors[]=$lang_str['fe_invalid_value_of_attribute']." ".$att->att_name; break;
			}

			//if att value is changed
			if ($HTTP_POST_VARS[$att->att_name] != $HTTP_POST_VARS["_hidden_".$att->att_name]){
				if (false === $data->update_attribute_of_user($serweb_auth, $att->att_name, $HTTP_POST_VARS[$att->att_name], $errors)) break;
			}
		}

		if (isset($errors) and $errors) break;

        Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

}while (false);


if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>
<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>

<?
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

if(!$attributes) $attributes = array();

$smarty->assign_by_ref('parameters', $page_attributes);

$js_on_subm="";
$smarty->assign('attributes', format_attributes_for_output($attributes, $js_on_subm));

$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'), array('before'=>$js_on_subm));

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('u_user_preferences.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>
