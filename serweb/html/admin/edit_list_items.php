<?
/*
 * $Id: edit_list_items.php,v 1.7 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";
require "../user_preferences.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

set_global('item_edit');
set_global('attrib_name');

function update_items_in_db($item_list, $attrib_name, $default_value, $data, &$errors){
	global $config;

	if (!$data->update_att_type_spec($attrib_name, serialize($item_list), $default_value, $errors)) return false;
		
	return true;
}

do{
	if (!$data = CData_Layer::create($errors)) break;

	//get attrib from DB
	if (false === $row = $data->get_attribute($attrib_name, $errors)) break;
	
	if ($row->att_rich_type!="list"){
		//attrib isn't list of items -> nothing to edit -> go back to attributes editing page

		Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	$item_list=unserialize(is_string($row->att_type_spec)?$row->att_type_spec:"");
	$default_value=$row->default_value;

	//if user want delete item
	if (isset($_GET['item_dele'])){
		//find item in array and unset it
		foreach($item_list as $key=>$row){
			if ($row->label==$_GET['item_dele']){
				unset($item_list[$key]);
				break;
			}
		}
		//update array in DB
		if (!update_items_in_db($item_list, $attrib_name, null, $data, $errors)) break;

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

	$f->add_element(array("type"=>"checkbox",
    	                         "name"=>"set_default",
	                             "value"=>"1",
								 "checked"=>(isset($it_val) and $it_val==$default_value)?1:0));

	$f->add_element(array("type"=>"hidden",
	                             "name"=>"item_edit",
	                             "value"=>$item_edit?$item_edit:""));

	$f->add_element(array("type"=>"hidden",
	                             "name"=>"attrib_name",
	                             "value"=>$attrib_name));

	$f->add_element(array("type"=>"submit",
    	                         "name"=>"okey",
        	                     "src"=>$config->img_src_path."butons/b_".($item_edit?"save":"add").".gif",
								 "extrahtml"=>"alt='".($item_edit?"save":"add")."'"));

	if (isset($_POST['okey_x'])){				// Is there data to process?
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
		if (!update_items_in_db($item_list, $attrib_name, $set_default?$item_value:null, $data, $errors)) break;

        Header("Location: ".$sess->url("edit_list_items.php?attrib_name=".RawURLEncode($attrib_name)."&kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);


if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['selected_tab']="user_preferences.php";
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">edit items of the list</h2>

<div class="swForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="item_label">item label:</label></td>
	<td><?$f->show_element("item_label");?></td>
	</tr>
	<tr>
	<td><label for="item_value">item value:</label></td>
	<td><?$f->show_element("item_value");?></td>
	</tr>
	<tr>
	<td><label for="set_default">set as default:</label></td>
	<td><?$f->show_element("set_default");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","");					// Finish form?>
</div>

<?if (is_array($item_list) and count($item_list)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>item label</th>
	<th>item value</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	foreach($item_list as $row){
		if ($row->label==$item_edit) continue;
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->label);?></td>
	<td align="left"><?echo nbsp_if_empty($row->value);?></td>
	<td align="center"><a href="<?$sess->purl("edit_list_items.php?kvrk=".uniqID("")."&item_edit=".RawURLEncode($row->label)."&attrib_name=".RawURLEncode($attrib_name));?>">edit</a></td>
	<td align="center"><a href="<?$sess->purl("edit_list_items.php?kvrk=".uniqID("")."&item_dele=".RawURLEncode($row->label)."&attrib_name=".RawURLEncode($attrib_name));?>">delete</a></td>
	</tr>
	<?}?>
	</table>

<?}?>

<div class="swBackToMainPage"><a href="<?$sess->purl($config->admin_pages_path."user_preferences.php?kvrk=".uniqid(""));?>" class="f14">back to editing attributes</a></div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
