<?
/*
 * $Id: user_preferences.php,v 1.3 2004/03/11 22:30:00 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";
require "../user_preferences.php";

$usr_pref = new User_Preferences();

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="cannot connect to sql server"; break;}

	//delete attrib from DB
	if ($att_dele){
		//delete attribute from user_preferences table
		$q="delete from ".$config->table_user_preferences.
			" where attribute='$att_dele'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		//delete attribute form user_preferences_types table
		$q="delete from ".$config->table_user_preferences_types.
			" where att_name='$att_dele'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

        Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	$att_type_spec=null;
	
	//select values of edited attribute in order to fill its to the form
	if ($att_edit){
		$q="select att_name, att_rich_type, att_type_spec, default_value from ".$config->table_user_preferences_types.
			" where att_name='$att_edit'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		$row=mysql_fetch_object($res);
		
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
	                             "value"=>$row->att_name?$row->att_name:"",
								 "size"=>16,
								 "maxlength"=>32,
								 "minlength"=>1,
								 "length_e"=>"you must fill attribute name",
//	                             "valid_regex"=>"^[a-zA-Z_][a-zA-Z0-9_]*$",
//	                             "valid_e"=>"in attribut name use only charakters 'A-Z', '0-9' and '_'",
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"select",
    	                         "name"=>"att_rich_type",
	                             "value"=>$row->att_rich_type?$row->att_rich_type:"",
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

								 
	if (isset($okey_x)){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}
		
		//check and format default value of attribute
		if (!$usr_pref->format_inputed_value($default_value, $att_rich_type, $att_type_spec)){
			$errors[]="bad default value"; break;
		}

			/* Process data */           // Data ok;

		if ($att_edit) 
			$q="update ".$config->table_user_preferences_types." ".
				"set att_name='$att_name', att_rich_type='$att_rich_type', default_value='$default_value', ".
					"att_raw_type='".$usr_pref->att_types[$att_rich_type]->raw_type."'".
				"where att_name='$att_edit'";
		else 
			$q="insert into ".$config->table_user_preferences_types." (att_name, att_rich_type, default_value, att_raw_type) ".
				"values ('$att_name', '$att_rich_type', '$default_value', '".$usr_pref->att_types[$att_rich_type]->raw_type."')";

		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		//if name of attribute is changed, update user_preferences table
		if ($att_edit and $att_edit!=$att_name){
			$q="update ".$config->table_user_preferences." ".
				"set attribute='$att_name' where attribute='$att_edit'";

			$res=MySQL_Query($q);
			if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		}

		if (!$att_edit and $att_rich_type=="list") 
	        Header("Location: ".$sess->url("edit_list_items.php?attrib_name=".RawURLEncode($att_name)."&kvrk=".uniqID("")));
		else
	        Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));

		page_close();
		exit;
	}
}while (false);
								 
do{
	if ($db){
		// get attrib table
		if ($att_edit) $qw=" att_name != '$att_edit' "; else $qw="1";

		$q="select att_name, att_rich_type, att_type_spec, default_value from ".$config->table_user_preferences_types.
			" where ".$qw." order by att_name";
		$att_res=MySQL_Query($q);
		if (!$att_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	}
}while (false);

if ($okey_x){							//data isn't valid or error in sql
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

<?if (MySQL_num_rows($att_res)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>attribute name</th>
	<th>attribute type</th>
	<th>default value</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	while($row = MySQL_Fetch_Object($att_res)){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->att_name);?></td>
	<td align="left"><?echo nbsp_if_empty($usr_pref->att_types[$row->att_rich_type]->label);?></td>
	<td align="left"><?echo nbsp_if_empty($usr_pref->format_value_for_output($row->default_value, $row->att_rich_type, $row->att_type_spec));?></td>
	<td align="center"><a href="<?$sess->purl("user_preferences.php?kvrk=".uniqID("")."&att_edit=".RawURLEncode($row->att_name));?>">edit</a></td>
	<td align="center"><a href="<?$sess->purl("user_preferences.php?kvrk=".uniqID("")."&att_dele=".RawURLEncode($row->att_name));?>">delete</a></td>
	</tr>
	<?}?>
	</table>

<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
