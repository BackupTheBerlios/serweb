{* Smarty *}
{* $Id: a_aliases.tpl,v 1.2 2005/04/28 14:28:04 kozlik Exp $ *}

{include file='_head.tpl' no_select_tab=1}

<h2 class="swTitle">{$lang_str.change_aliases_of_user}: {$uname}</h2>

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="2" cellpadding="0" align="center">
	<tr>
	<td><label for="al_username">{$lang_str.ff_alias}({$lang_str.ff_username}):</label></td>
	<td>{$form.al_username}</td>
	</tr>
	<tr>
	<td><label for="al_domain">{$lang_str.ff_alias}({$lang_str.ff_domain}):</label></td>
	<td>{$form.al_domain}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>



{foreach from=$aliases item='row' name='aliases'}
	{if $smarty.foreach.aliases.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_alias}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="right" width="50%" style="padding-right:1em;">{$row.username}@{$row.domain}</td>
	<td align="center" style="width:6em;"><a href="{$row.url_edit}">{$lang_str.l_change}</a></td>
	<td align="center" style="width:6em;"><a href="{$row.url_dele}" onclick="return confirmDelete(this, '{$lang_str.realy_you_want_delete_this_alias}')">{$lang_str.l_delete}</a></td>
	</tr>
	{if $smarty.foreach.aliases.last}
	</table>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">{$lang_str.user_have_not_any_aliases}</div>
{/foreach}

<div class="swBackToMainPage"><a href="{url url='users.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

</div>
{include file='_tail.tpl'}
