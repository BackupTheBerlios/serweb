{* Smarty *}
{* $Id: a_domain_edit.tpl,v 1.7 2006/05/23 09:13:38 kozlik Exp $ *}

{literal}
<style type="text/css">
	#do_new_name {width:150px;}
	#do_customer {width:155px;}  
</style>	
{/literal}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label>{$lang_str.d_id}:</label></td>
	<td>{$dom_id|escape}</td>
	</tr>
	<tr valign="top">
	<td><label>{$lang_str.d_name}:</label></td>
	<td align="left">
	{foreach from=$dom_names item='row' name='names'}
		{if $smarty.foreach.names.first}
		<table border="0" cellspacing="0" cellpadding="2" align="left" style="margin-left:0px">
		{/if}
		<tr>
		<td align="left">{if $row.canon}<strong>{$row.name|escape}</strong>{else}{$row.name|escape}{/if}</td>
		<td>{if !$row.canon}<a href="{$row.url_set_canon}" class="actionsrow">{$lang_str.l_set_canon}</a>{else}&nbsp;{/if}</td>
		<td>{if $row.allow_dele}<a href="{$row.url_dele}" class="actionsrow" onclick="return confirmDelete(this, '{$lang_str.realy_delete_domain}')">{$lang_str.l_delete}</a>{else}&nbsp;{/if}</td>
		</tr>
		{if $smarty.foreach.names.last}
		</table>
		{/if}
	{/foreach}
	</td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
		<td><label for="do_new_name">{$lang_str.new_dom_name}:</label></td>
		<td>{$form.do_new_name} {$form.do_okey_add}</td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
	<td><label for="do_customer">{$lang_str.owner}:</label></td>
	<td>{$form.do_customer}</td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

	<tr>
	<td>&nbsp;</td>
	<td>{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>
<br /><br /><br />
<h2 class="swTitle">{$lang_str.admins_of_domain}</h2>

{foreach from=$admins item='row' name='admins'}
	{if $smarty.foreach.admins.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	{/if}
	<tr>
	<td align="left">{$row.username|escape}@{$row.domain|escape}</td>
	<td><a href="{$row.url_unset_admin}" class="actionsrow">{$lang_str.l_unassign_admin	}</a></td>
	</tr>
	{if $smarty.foreach.admins.last}
	</table><br />
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.no_admins}</div>
{/foreach}

<div><a href="javascript: open_wizard_win('{url url=$admin_select_url uniq=1}');">{$lang_str.assign_admin_to_domain}</a></div>

<div class="swBackToMainPage"><a href="{url url='list_of_domains.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

<br>
{include file='_tail.tpl'}
