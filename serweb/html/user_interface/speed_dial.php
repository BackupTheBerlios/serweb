<?
/*
 * $Id: speed_dial.php,v 1.9 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('get_user_real_name', 'update_sd_request', 'del_sd_request', 
									'get_sd_request', 'get_sd_requests');

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

set_global('edit_sd');
set_global('edit_sd_dom');

do{
	if (isset($_GET['dele_sd'])){
		if (!$data->del_SD_request($serweb_auth, $_GET['dele_sd'], $_GET['dele_sd_dom'], $errors)) break;

        Header("Location: ".$sess->url("speed_dial.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if ($edit_sd){
		if (false === $row = $data->get_SD_request($serweb_auth, $edit_sd, $edit_sd_dom, $errors)) break;
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"usrnm_from_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->username_from_req_uri)?$row->username_from_req_uri:"",
                                     "valid_regex"=>"^".$config->speed_dial_initiation,
                                     "valid_e"=>"username from request uri must start by: ".$config->speed_dial_initiation,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"domain_from_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->domain_from_req_uri)?$row->domain_from_req_uri:$serweb_auth->domain,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"new_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->new_request_uri)?$row->new_request_uri:"",
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>"not valid sip address",
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_sd",
	                             "value"=>$edit_sd?$edit_sd:""));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_sd_dom",
	                             "value"=>$edit_sd_dom?$edit_sd_dom:""));
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

		if (!$data->update_SD_request($serweb_auth, $edit_sd, $_POST['new_uri'], $_POST['usrnm_from_uri'], $_POST['domain_from_uri'], $errors)) break;

        Header("Location: ".$sess->url("speed_dial.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	$requests=array();

	if ($data){
		// get speed dial requests
		if (false === $requests = $data->get_SD_requests($serweb_auth, $edit_sd?$edit_sd:NULL, $edit_sd_dom?$edit_sd_dom:NULL, $errors)) break;

	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>
<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $errors);
print_html_body_begin($page_attributes);
?>

<div class="swForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="right"><label for="usrnm_from_uri">username from request uri:</label></td>
	<td><?$f->show_element("usrnm_from_uri");?></td>
	</tr>
	<tr>
	<td align="right"><label for="domain_from_uri">domain from request uri:</label></td>
	<td><?$f->show_element("domain_from_uri");?></td>
	</tr>
	<tr>
	<td align="right"><label for="new_uri">new request uri:</label></td>
	<td><?$f->show_element("new_uri");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","sip_address_completion(f.new_uri);");					// Finish form?>
</div>

<?if (is_array($requests) and count($requests)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>request uri</th>
	<th>new request uri</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	foreach($requests as $row){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->username_from_req_uri."@".$row->domain_from_req_uri);?></td>
	<td align="left"><?echo nbsp_if_empty($row->new_request_uri);?></td>
	<td align="center"><a href="<?$sess->purl("speed_dial.php?kvrk=".uniqID("")."&edit_sd=".$row->username_from_req_uri."&edit_sd_dom=".$row->domain_from_req_uri);?>">edit</a></td>
	<td align="center"><a href="<?$sess->purl("speed_dial.php?kvrk=".uniqID("")."&dele_sd=".$row->username_from_req_uri."&dele_sd_dom=".$row->domain_from_req_uri);?>">delete</a></td>
	</tr>
	<?}//while?>
	</table>
<?}else{?>

<div class="swNumOfFoundRecords">No speed dials defined</div>

<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
