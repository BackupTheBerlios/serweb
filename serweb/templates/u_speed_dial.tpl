{* Smarty *}
{* $Id: u_speed_dial.tpl,v 1.1 2004/08/09 23:04:57 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="right"><label for="usrnm_from_uri">{$lang_str.ff_username_from_request_uri}:</label></td>
	<td>{$form.usrnm_from_uri}</td>
	</tr>
	<tr>
	<td align="right"><label for="domain_from_uri">{$lang_str.ff_domain_from_request_uri}:</label></td>
	<td>{$form.domain_from_uri}</td>
	</tr>
	<tr>
	<td align="right"><label for="new_uri">{$lang_str.ff_new_request_uri}:</label></td>
	<td>{$form.new_uri}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$speed_dials item='row' name='speed_dials'}
	{if $smarty.foreach.speed_dials.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_request_uri}</th>
	<th>{$lang_str.th_new_request_uri}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.username_from_req_uri}@{$row.domain_from_req_uri}</td>
	<td align="left">{$row.new_request_uri|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_edit}">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele}">{$lang_str.l_delete}</a></td>
	</tr>
	{if $smarty.foreach.speed_dials.last}
	</table>
	{/if}
{foreachelse}
	<div class="swNumOfFoundRecords">{$lang_str.no_speed_dials_defined}</div>
{/foreach}

<br>
{include file='_tail.tpl'}

