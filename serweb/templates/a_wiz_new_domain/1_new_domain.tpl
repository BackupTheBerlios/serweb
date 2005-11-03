{* Smarty *}
{* $Id: 1_new_domain.tpl,v 1.1 2005/11/03 11:02:11 kozlik Exp $ *}

{literal}
<style type="text/css">
	#do_new_name {width:150px;}
	#do_customer {width:155px;}  
</style>	
{/literal}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.create_new_domain}</h2>

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label>{$lang_str.d_id}:</label></td>
	<td colspan="2">{$dom_id}</td>
	</tr>
	<tr valign="top">
	<td><label>{$lang_str.d_name}:</label></td>
	<td align="left" colspan="2">
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
	<td>{$form.do_new_name}</td>
	<td>{$form.do_okey_add}</td>
	</tr>
	<tr>
	<td><label for="do_customer">{$lang_str.owner}:</label></td>
	<td>{$form.do_customer}</td>
	<td>&nbsp;<a href="{url url='1_new_customer.php' uniq=1}">{$lang_str.l_create_new_customer}</a></td>
	</tr>
	<tr>
	<td colspan="2">&nbsp;</td>
	<td >{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

<div class="swBackToMainPage"><a href="javascript: window.close();">{$lang_str.l_close_window}</a></div>

<br>
{include file='_tail.tpl'}
