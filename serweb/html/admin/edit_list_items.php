<?
/*
 * $Id: edit_list_items.php,v 1.1 2004/02/24 08:53:08 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";
require "../user_preferences.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

function update_items_in_db($item_list, $attrib_name){
	global $config;

	$q="update ".$config->table_user_preferences_types.
		" set att_type_spec='".serialize($item_list)."' ".
		" where att_name='$attrib_name'";
	
	$res=MySQL_Query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
}

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="cannot connect to sql server"; break;}

	//get attrib from DB
	$q="select att_name, att_type, att_type_spec from ".$config->table_user_preferences_types.
			" where att_name='$attrib_name'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	$row=mysql_fetch_object($res);

	if ($row->att_type!="list"){ 
		//attrib isn't list of items -> nothing to edit -> go back to attributes editing page
        
		Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
	
	$item_list=unserialize($row->att_type_spec);

	//if user want delete item
	if ($item_dele){
		//find item in array and unset it
		foreach($item_list as $key=>$row){
			if ($row->label==$item_dele){
				unset($item_list[$key]);
				break;
			}
		}
		//update array in DB
		update_items_in_db($item_list, $attrib_name);

        Header("Location: ".$sess->url("edit_list_items.php?attrib_name=".RawURLEncode($attrib_name)."&kvrk=".uniqID("")));
		page_close();
		exit;
	}
	
	//if user want edit item
	if ($item_edit){
		//find value of item in order to we can fill it in the form
		foreach($item_list as $row){
			if ($row->label==$item_edit){
				$it_val=$row->value;				
				break;
			}
		}
	}
	
	$f = new form;                   // create a form object

	$f->add_element(array("type"=>"text",
	                             "name"=>"item_label",
	                             "value"=>$item_edit?$item_edit:"",
								 "size"=>16,
								 "maxlength"=>255,
								 "minlength"=>1,
								 "length_e"=>"you must fill attribute name",
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"text",
    	                         "name"=>"item_value",
	                             "value"=>isset($it_val)?$it_val:"",
								 "size"=>16,
								 "maxlength"=>255,
								 "minlength"=>1,
								 "length_e"=>"you must fill item value",
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"hidden",
	                             "name"=>"item_edit",
	                             "value"=>$item_edit?$item_edit:""));

	$f->add_element(array("type"=>"hidden",
	                             "name"=>"attrib_name",
	                             "value"=>$attrib_name));

	$f->add_element(array("type"=>"submit",
    	                         "name"=>"okey",
        	                     "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));

	if (isset($okey_x)){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}
		

			/* Process data */           // Data ok;

		//if user want edit item
		if ($item_edit){
			//find item in array and replace it by new item
			foreach($item_list as $key=>$row){
				if ($row->label==$item_edit){
					$item_list[$key]=new UP_List_Items($item_label, $item_value);
					break;
				}
			}
		}
		else //insert new item
			$item_list[]=new UP_List_Items($item_label, $item_value);

		//update array in DB
		update_items_in_db($item_list, $attrib_name);

        Header("Location: ".$sess->url("edit_list_items.php?attrib_name=".RawURLEncode($attrib_name)."&kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);
								 

if ($okey_x){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?echo $config->title;?></title>
<?print_html_head();?>
</head>
<?
	print_admin_html_body_begin("user_preferences.php");
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);

	ptitle("edit items of the list");
?>

<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="right" class="f12b">item label:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("item_label");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">item value:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("item_value");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","");					// Finish form?>

<?if (is_array($item_list) and count($item_list)){?>

<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="205">item label</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="205">item value</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="50">&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="50">&nbsp;</td>
	</tr>
	<tr><td colspan="7" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?$odd=0;
	foreach($item_list as $row){
		if ($row->label==$item_edit) continue;
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'bgcolor="#FFFFFF"':'bgcolor="#EAF0F4"';?>>
	<td align="left" class="f12" width="205">&nbsp;<?echo $row->label;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="205">&nbsp;<?echo $row->value;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="50"><a href="<?$sess->purl("edit_list_items.php?kvrk=".uniqID("")."&item_edit=".RawURLEncode($row->label)."&attrib_name=".RawURLEncode($attrib_name));?>">edit</a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="50"><a href="<?$sess->purl("edit_list_items.php?kvrk=".uniqID("")."&item_dele=".RawURLEncode($row->label)."&attrib_name=".RawURLEncode($attrib_name));?>">delete</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>

<?}?>

<br><a href="<?$sess->purl($config->admin_pages_path."user_preferences.php?kvrk=".uniqid(""));?>" class="f14">back to editing attributes</a><br>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
