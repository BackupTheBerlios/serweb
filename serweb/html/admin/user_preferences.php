<?
/*
 * $Id: user_preferences.php,v 1.10 2004/08/26 13:09:20 kozlik Exp $
 */

$_data_layer_required_methods=array('del_attribute', 'get_attribute', 'update_attribute', 'get_attributes');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

require "prepend.php";
require "../user_preferences.php";

$usr_pref = new User_Preferences();

$perm->check("admin");

set_global('att_edit');
set_global('att_name');
set_global('att_rich_type');
set_global('default_value');

function format_attributes_for_output($attributes, $usr_pref){
	global $sess;

	$out=array();
	$i=0;
	foreach($attributes as $att){
		$out[$i]['att_name'] = $att->att_name;
		$out[$i]['att_type'] = $usr_pref->att_types[$att->att_rich_type]->label;
		$out[$i]['def_value'] = $usr_pref->format_value_for_output($att->default_value, $att->att_rich_type, $att->att_type_spec);
		$out[$i]['url_dele'] = $sess->url("user_preferences.php?kvrk=".uniqID("")."&att_dele=".RawURLEncode($att->att_name));
		$out[$i]['url_edit'] = $sess->url("user_preferences.php?kvrk=".uniqID("")."&att_edit=".RawURLEncode($att->att_name));
		$i++;
	}
	
	return $out;
}



do{
	//delete attrib from DB
	if (isset($_GET['att_dele'])){
		if (!$data->del_attribute($_GET['att_dele'], $errors)) break;

        Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	$att_type_spec=null;
	
	//select values of edited attribute in order to fill its to the form
	if ($att_edit){
		if (false === $row = $data->get_attribute($att_edit, $errors)) break;
		
		$att_type_spec=$row->att_type_spec;
		
		//if $att_type is not set by http_post (form not submited yet) set it by value in DB
		if (!isset($att_rich_type)) $att_rich_type=$row->att_rich_type;
	}

	//create array of options of select
	$opt=array();
	foreach($usr_pref->att_types as $k => $v){
		$opt[]=array("label" => $v->label, "value" => $k);
	}
	
	$f = new form;                   // create a form object

	$f->add_element(array("type"=>"text",
	                             "name"=>"att_name",
	                             "value"=>isset($row->att_name)?$row->att_name:"",
								 "size"=>16,
								 "maxlength"=>32,
								 "minlength"=>1,
								 "length_e"=>$lang_str['fe_not_filled_name_of_attribute'],
//	                             "valid_regex"=>"^[a-zA-Z_][a-zA-Z0-9_]*$",
//	                             "valid_e"=>"in attribut name use only charakters 'A-Z', '0-9' and '_'",
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"select",
    	                         "name"=>"att_rich_type",
	                             "value"=>isset($row->att_rich_type)?$row->att_rich_type:"",
								 "size"=>1,
								 "options"=>$opt,
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"text",
    	                         "name"=>"default_value",
	                             "value"=>isset($row->default_value)?$row->default_value:"",
								 "size"=>16,
								 "maxlength"=>255,
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"hidden",
	                             "name"=>"att_edit",
	                             "value"=>$att_edit?$att_edit:""));

	$f->add_element(array("type"=>"submit",
    	                         "name"=>"okey",
        	                     "src"=>$config->img_src_path."butons/b_".($att_edit?"save":"add").".gif",
								 "extrahtml"=>"alt='".($att_edit?"save":"add")."'"));

								 
	if (isset($_POST['okey_x'])){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}
		
		//check and format default value of attribute
		if (!$usr_pref->format_inputed_value($default_value, $att_rich_type, $att_type_spec)){
			$errors[]="bad default value"; break;
		}

			/* Process data */           // Data ok;

		if (!$data->update_attribute($att_edit, $att_name, $att_rich_type, $usr_pref->att_types[$att_rich_type]->raw_type, $default_value, $errors)) break;

		if (!$att_edit and ($att_rich_type=="list" or $att_rich_type=="radio")) 
	        Header("Location: ".$sess->url("edit_list_items.php?attrib_name=".RawURLEncode($att_name)."&kvrk=".uniqID("")));
		else
	        Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));

		page_close();
		exit;
	}
}while (false);
								 
do{
	$attributes = array();
	if ($data){
		if (false === $attributes = $data->get_attributes($att_edit?$att_edit:NULL, $errors)) break;
	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->img_src_path = $config->img_src_path;

if(!$attributes) $attributes = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref("config", $cfg);		

$smarty->assign('attributes', format_attributes_for_output($attributes, $usr_pref));

$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'));

if($att_edit and ($att_rich_type=="list" or $att_rich_type=="radio"))
	$smarty->assign('url_edit_list', $sess->url("edit_list_items.php?attrib_name=".RawURLEncode($att_edit)."&kvrk=".uniqID("")));

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('a_user_preferences.tpl');
?>

<?print_html_body_end();?>
</html>
<?page_close();?>
