<?
/*
 * $Id: caller_screening.php,v 1.6 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

set_global('edit_caller');

do{
	if (!$data = CData_Layer::create($errors)) break;

	if (isset($_GET['dele_caller'])){
		if (!$data->del_CS_caller($auth->auth["uname"], $config->domain, $_GET['dele_caller'], $errors)) break;

        Header("Location: ".$sess->url("caller_screening.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if ($edit_caller){
		if (false === $row = $data->get_CS_caller($auth->auth["uname"], $config->domain, $edit_caller, $errors)) break;
	}

	//create array of options of select
	$opt=array();
	foreach($config->calls_forwarding["screening"] as $k => $v){
		$opt[]=array("label" => $v->label, "value" => $k);
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"uri_re",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->uri_re)?$row->uri_re:"",
								 "minlength"=>1,
								 "length_e"=>"you must fill caller uri",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"select",
	                             "name"=>"action_key",
								 "size"=>1,
	                             "value"=>isset($row->action)?(
								 			Ccall_fw::get_key($config->calls_forwarding["screening"],
																$row->action,
																$row->param1,
																$row->param2)
											):"",
								 "options"=>$opt,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_caller",
	                             "value"=>$edit_caller?$edit_caller:""));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));


	if (isset($_POST['okey_x'])){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;
		if (!$data->update_CS_caller($auth->auth["uname"], $config->domain, $edit_caller, $_POST['uri_re'], $_POST['action_key'], $errors)) break;

        Header("Location: ".$sess->url("caller_screening.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	$caller_uris=array();
	
	if ($data){
		// get screenings
		if (false === $caller_uris = $data->get_CS_callers($auth->auth["uname"], $config->domain, $edit_caller?$edit_caller:NULL, $errors)) break;

	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=$data->get_user_name($errors);
print_html_body_begin($page_attributes);
?>

<div class="swForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="uri_re">caller uri (regular expression):</label></td>
	<td><?$f->show_element("uri_re");?></td>
	</tr>
	<tr>
	<td><label for="action_key">action:</label></td>
	<td><?$f->show_element("action_key");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","");					// Finish form?>
</div>

<?if (is_array($caller_uris) and count($caller_uris)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>caller uri</th>
	<th>action</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	foreach($caller_uris as $row){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->uri_re);?></td>
	<td align="left"><?echo nbsp_if_empty(Ccall_fw::get_label($config->calls_forwarding["screening"], $row->action, $row->param1, $row->param2));?></td>
	<td align="center"><a href="<?$sess->purl("caller_screening.php?kvrk=".uniqID("")."&edit_caller=".$row->uri_re);?>">edit</a></td>
	<td align="center"><a href="<?$sess->purl("caller_screening.php?kvrk=".uniqID("")."&dele_caller=".$row->uri_re);?>">delete</a></td>
	</tr>
	<?}//while?>
	</table>
<?}else{?>

<div class="swNumOfFoundRecords">No caller screenings defined</div>

<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
