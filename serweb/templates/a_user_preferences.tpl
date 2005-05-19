{* Smarty *}
{* $Id: a_user_preferences.tpl,v 1.4 2005/05/19 18:26:07 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="up_att_name">{$lang_str.ff_att_name}:</label></td>
	<td>{$form.up_att_name}</td>
	</tr>
	<tr>
	<td><label for="up_att_rich_type">{$lang_str.ff_att_type}:</label></td>
	<td>{$form.up_att_rich_type}</td>
	</tr>
	<tr>
	<td><label for="up_default_value">{$lang_str.ff_att_default_value}:</label></td>
	<td>{$form.up_default_value}</td>
	</tr>
	<tr>
	<td align="left">
	{if $url_edit_list}
		<a href="{$url_edit_list}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_edit_items_of_the_list.gif" width="165" height="16" border="0"></a>
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
	<th>{$lang_str.th_att_name}</th>
	<th>{$lang_str.th_att_type}</th>
	<th>{$lang_str.th_att_default_value}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.att_name|empty2nbsp}</td>
	<td align="left">{$row.att_type|empty2nbsp}</td>
	<td align="left">{$row.def_value|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_edit}">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele}">{$lang_str.l_delete}</a></td>
	</tr>
	{if $smarty.foreach.attributes.last}
	</table>
	{/if}
{/foreach}

<br>
{include file='_tail.tpl'}
