<?
/*
 * $Id: list_of_admins.php,v 1.6 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin,change_priv");

if (!$sess->is_registered('sess_list_of_admins')) $sess->register('sess_list_of_admins');
if (!isset($sess_list_of_admins)) $sess_list_of_admins=new Cfusers(array('show_adminsonly'=>true, 'show_onlineonly'=>false, 'show_domain'=>true));

$sess_list_of_admins->init();

do{
	if (!$data = CData_Layer::create($errors)) break;

	$admins=array();

	$data->set_act_row($sess_list_of_admins->act_row);

	if (false === $admins = $data->get_admins($sess_list_of_admins, $config->domain, $errors)) break;

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">filter:</h2>

<?$sess_list_of_admins->print_form();?>

<h2 class="swTitle">List of users</h2>

<?if (is_array($admins) and count($admins)){?>
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>username</th>
	<th>domain</th>
	<th>name</th>
	<th>email</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	foreach($admins as $row){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->username);?></td>
	<td align="left"><?echo nbsp_if_empty($row->domain);?></td>
	<td align="left"><?echo nbsp_if_empty($row->name);?></td>
	<td align="left"><a href="mailto:<?echo $row->email_address;?>"><?echo $row->email_address;?></a></td>
	<td align="center"><a href="<?$sess->purl("admin_privileges.php?kvrk=".uniqid('')."&user_id=".rawURLEncode($row->username)."&user_domain=".rawURLEncode($row->domain));?>">change privileges</a></td>
	</tr>
	<?} //while?>
	</table>

	<? if ($data->get_num_rows()){?>
	<div class="swNumOfFoundRecords">Showed users <?echo $data->get_res_from()." - ".$data->get_res_to();?> from <?echo $data->get_num_rows();?></div>

	<div class="swSearchLinks">
	<?
		$url="list_of_admins.php?kvrk=".uniqid("")."&act_row=";
		print_search_links($data->get_act_row(), $data->get_num_rows(), $data->get_showed_rows(), $url);
	?></div>
	<?} // if ($num_rows)?>

<?}else{?>
<div class="swNumOfFoundRecords">No users found</div>
<?} // if (is_array($admins) and count($admins))?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>
