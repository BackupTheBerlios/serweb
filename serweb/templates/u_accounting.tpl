{* Smarty *}
{* $Id: u_accounting.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

{foreach from=$acc_res item='row' name='accounting'}
	{if $smarty.foreach.accounting.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>destination</th>
	<th>time</th>
	<th>length of call</th>
	<th>hang up</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">
	<a href="{$row.url_ctd}">{$row.sip_to|empty2nbsp}</a></td>
	<td align="left">{$row.time|empty2nbsp}</td>
	<td align="left">{$row.length|empty2nbsp}</td>
	<td align="center">{$row.hangup}</td>
	</tr>
	{if $smarty.foreach.accounting.last}
	</table>

	<div class="swNumOfFoundRecords">Calls {$pager.from} - {$pager.to} from {$pager.items}</div>

	<div class="swSearchLinks">&nbsp;
	{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">No calls</div>
{/foreach}

{if $come_from_admin_interface}
	<br>
	<div class="swBackToMainPage"><a href="{$url_admin}">back to main page</a></div>
{/if}

<br>
{include file='_tail.tpl'}


{*
<?if (is_array($res) and count($res)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>destination</th>
	<th>time</th>
	<th>length of call</th>
	<th>hang up</th>
	</tr>
	<?$odd=0;
	foreach($res as $row){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left">
	<a href="javascript: open_ctd_win2('<?echo rawURLEncode($row->to_uri);?>', '<?echo RawURLEncode("sip:".$serweb_auth->uname."@".$serweb_auth->domain); ?>');">
	<?echo htmlspecialchars(ereg_replace("(.*)(;tag=.*)","\\1",$row->sip_to));?></a></td>
	<td align="left"><?echo nbsp_if_empty($row->time);?></td>
	<td align="left"><?echo nbsp_if_empty($row->length);?></td>
	<td align="center"><?echo nbsp_if_empty($row->hangup);?></td>
	</tr>
	<?}//while?>
	</table>

<?}?>

<? if ($data->get_num_rows()){?>
<div class="swNumOfFoundRecords">Calls <?echo $data->get_res_from()." - ".$data->get_res_to();?> from <?echo $data->get_num_rows();?></div>
<?}else{?>
<div class="swNumOfFoundRecords">No calls</div>
<?}?><br>

<div class="swSearchLinks">&nbsp;
<?
	$url="accounting.php?kvrk=".uniqid("").($uid?("&".userauth_to_get_param($uid, 'u')):"")."&act_row=";
	print_search_links($data->get_act_row(), $data->get_num_rows(), $data->get_showed_rows(), $url);
?>
</div>



<? if ($perm->have_perm("admin") and $uid){?>
	<br>
	<div class="swBackToMainPage"><a href="<?$sess->purl($config->admin_pages_path."users.php?kvrk=".uniqid(""));?>" class="f14">back to main page</a></div>
<?}?>
<br>
*}