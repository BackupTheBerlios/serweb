<?
/*
 * $Id: user_preferences.php,v 1.7 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";
require "../user_preferences.php";

$usr_pref = new User_Preferences();

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

set_global('att_edit');
set_global('att_name');
set_global('att_rich_type');
set_global('default_value');

do{
	if (!$data = CData_Layer::create($errors)) break;

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
								 "length_e"=>"you must fill attribute name",
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

		if (!$att_edit and $att_rich_type=="list") 
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
?>

<div class="swForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="att_name">attribute name:</label></td>
	<td><?$f->show_element("att_name");?></td>
	</tr>
	<tr>
	<td><label for="att_rich_type">attribute type:</label></td>
	<td><?$f->show_element("att_rich_type");?></td>
	</tr>
	<tr>
	<td><label for="default_value">default value:</label></td>
	<td><?$f->show_element("default_value");?></td>
	</tr>
	<tr>
	<td align="left"><?
		if($att_edit and $att_rich_type=="list"){?>
			<a href="<?$sess->purl("edit_list_items.php?attrib_name=".RawURLEncode($att_edit)."&kvrk=".uniqID(""))?>"><img src="<?echo $config->img_src_path;?>butons/b_edit_items_of_the_list.gif" width="165" height="16" border="0"></a><?
		}else echo "&nbsp;";?></td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","");					// Finish form?>
</div>

<?if (is_array($attributes) and count($attributes)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>attribute name</th>
	<th>attribute type</th>
	<th>default value</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	foreach($attributes as $row){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->att_name);?></td>
	<td align="left"><?echo nbsp_if_empty($usr_pref->att_types[$row->att_rich_type]->label);?></td>
	<td align="left"><?echo nbsp_if_empty($usr_pref->format_value_for_output($row->default_value, $row->att_rich_type, $row->att_type_spec));?></td>
	<td align="center"><a href="<?$sess->purl("user_preferences.php?kvrk=".uniqID("")."&att_edit=".RawURLEncode($row->att_name));?>">edit</a></td>
	<td align="center"><a href="<?$sess->purl("user_preferences.php?kvrk=".uniqID("")."&att_dele=".RawURLEncode($row->att_name));?>">delete</a></td>
	</tr>
	<?}//while?>
	</table>

<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
