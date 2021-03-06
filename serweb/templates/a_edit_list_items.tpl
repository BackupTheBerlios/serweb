{* Smarty *}
{* $Id: a_edit_list_items.tpl,v 1.3 2005/05/19 17:55:51 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.edit_items_of_the_list}</h2>

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="up_item_label">{$lang_str.ff_item_label}:</label></td>
	<td>{$form.up_item_label}</td>
	</tr>
	<tr>
	<td><label for="up_item_value">{$lang_str.ff_item_value}:</label></td>
	<td>{$form.up_item_value}</td>
	</tr>
	<tr>
	<td><label for="up_set_default">{$lang_str.ff_set_as_default}:</label></td>
	<td>{$form.up_set_default}</td>
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
	<th>{$lang_str.th_item_label}</th>
	<th>{$lang_str.th_item_value}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.label|empty2nbsp}</td>
	<td align="left">{$row.value|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_edit}">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele}">{$lang_str.l_delete}</a></td>
	</tr>

	{if $smarty.foreach.item_list.last}
	</table>
	{/if}
{/foreach}

<div class="swBackToMainPage"><a href="{url url='user_preferences.php' uniq=1}">{$lang_str.l_back_to_editing_attributes}</a></div>

<br>
{include file='_tail.tpl'}
