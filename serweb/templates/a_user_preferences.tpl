{* Smarty *}
{* $Id: a_user_preferences.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="att_name">attribute name:</label></td>
	<td>{$form.att_name}</td>
	</tr>
	<tr>
	<td><label for="att_rich_type">attribute type:</label></td>
	<td>{$form.att_rich_type}</td>
	</tr>
	<tr>
	<td><label for="default_value">default value:</label></td>
	<td>{$form.default_value}</td>
	</tr>
	<tr>
	<td align="left">
	{if $url_edit_list}
		<a href="{$url_edit_list}"><img src="{$config->img_src_path}butons/b_edit_items_of_the_list.gif" width="165" height="16" border="0"></a>
	{else}
		&nbsp;
	{/if}
	</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$attributes item='row' name='attributes'}
	{if $smarty.foreach.attributes.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>attribute name</th>
	<th>attribute type</th>
	<th>default value</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.att_name|empty2nbsp}</td>
	<td align="left">{$row.att_type|empty2nbsp}</td>
	<td align="left">{$row.def_value|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_edit}">edit</a></td>
	<td align="center"><a href="{$row.url_dele}">delete</a></td>
	</tr>
	{if $smarty.foreach.attributes.last}
	</table>
	{/if}
{/foreach}

<br>
{include file='_tail.tpl'}