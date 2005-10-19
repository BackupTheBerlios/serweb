{* Smarty *}
{* $Id: a_domain_edit.tpl,v 1.1 2005/10/19 10:33:07 kozlik Exp $ *}

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
	<td>{$dom_id}</td>
	</tr>
	<tr valign="top">
	<td><label>{$lang_str.d_name}:</label></td>
	<td align="left">
	{foreach from=$dom_names item='row' name='names'}
		{if $smarty.foreach.names.first}
		<table border="0" cellspacing="0" cellpadding="2" align="left" style="margin-left:0px">
		{/if}
		<tr>
		<td align="left">{$row.name}</td>
		<td><a href="{$row.url_dele}" onclick="return confirmDelete(this, '{$lang_str.realy_delete_domain}')">{$lang_str.l_delete}</a></td>
		</tr>
		{if $smarty.foreach.names.last}
		</table>
		{/if}
	{/foreach}
	</td>
	</tr>
	<tr>
	<td><label for="do_new_name">{$lang_str.new_dom_name}:</label></td>
	<td>{$form.do_new_name} {$form.do_okey_add}</td>
	</tr>
	<tr>
	<td><label for="do_customer">{$lang_str.owner}:</label></td>
	<td>{$form.do_customer}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

<div class="swBackToMainPage"><a href="{url url='list_of_domains.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

<br>
{include file='_tail.tpl'}
