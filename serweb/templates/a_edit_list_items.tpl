{* Smarty *}
{* $Id: a_edit_list_items.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">edit items of the list</h2>

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="item_label">item label:</label></td>
	<td>{$form.item_label}</td>
	</tr>
	<tr>
	<td><label for="item_value">item value:</label></td>
	<td>{$form.item_value}</td>
	</tr>
	<tr>
	<td><label for="set_default">set as default:</label></td>
	<td>{$form.set_default}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$item_list item='row' name='item_list'}
	{if $smarty.foreach.item_list.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>item label</th>
	<th>item value</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.label|empty2nbsp}</td>
	<td align="left">{$row.value|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_edit}">edit</a></td>
	<td align="center"><a href="{$row.url_dele}">delete</a></td>
	</tr>

	{if $smarty.foreach.item_list.last}
	</table>
	{/if}
{/foreach}

<div class="swBackToMainPage"><a href="{url url='user_preferences.php' uniq=1}">back to editing attributes</a></div>

<br>
{include file='_tail.tpl'}
