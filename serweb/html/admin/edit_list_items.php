<?
/*
 * $Id: edit_list_items.php,v 1.10 2004/08/26 13:09:20 kozlik Exp $
 */

$_data_layer_required_methods=array('update_att_type_spec', 'get_attribute');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

require "prepend.php";
require "../user_preferences.php";

$perm->check("admin");

set_global('item_edit');
set_global('attrib_name');

function update_items_in_db($item_list, $attrib_name, $default_value, $data, &$errors){
	global $config;

	if (!$data->update_att_type_spec($attrib_name, serialize($item_list), $default_value, $errors)) return false;
		
	return true;
}

function format_items_for_output($items, $item_edit, $attrib_name){
	global $sess;

	$out=array();
	$i=0;
	foreach($items as $item){
		if ($item->label==$item_edit) continue;
		
		$out[$i]['label'] = $item->label;
		$out[$i]['value'] = $item->value;
		$out[$i]['url_dele'] = $sess->url("edit_list_items.php?kvrk=".uniqID("")."&item_dele=".RawURLEncode($item->label)."&attrib_name=".RawURLEncode($attrib_name));
		$out[$i]['url_edit'] = $sess->url("edit_list_items.php?kvrk=".uniqID("")."&item_edit=".RawURLEncode($item->label)."&attrib_name=".RawURLEncode($attrib_name));
		$i++;
	}
	
	return $out;
}


do{
	//get attrib from DB
	if (false === $row = $data->get_attribute($attrib_name, $errors)) break;
	
	if ($row->att_rich_type!="list" and $row->att_rich_type!="radio"){
		//attrib isn't neither list of items nor radio -> nothing to edit -> go back to attributes editing page

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
								 "length_e"=>$lang_str['fe_not_filled_item_label'],
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"text",
    	                         "name"=>"item_value",
	                             "value"=>isset($it_val)?$it_val:"",
								 "size"=>16,
								 "maxlength"=>255,
								 "minlength"=>1,
								 "length_e"=>$lang_str['fe_not_filled_item_value'],
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

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

if(!$item_list) $item_list = array();

$smarty->assign_by_ref('parameters', $page_attributes);

$smarty->assign('item_list', format_items_for_output($item_list, $item_edit, $attrib_name));

$smarty->assign_phplib_form('form', $f, array('jvs_name'=>'form'));

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('a_edit_list_items.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>
