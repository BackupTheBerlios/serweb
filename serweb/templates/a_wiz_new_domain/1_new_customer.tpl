{* Smarty *}
{* $Id: 1_new_customer.tpl,v 1.1 2005/11/03 11:02:11 kozlik Exp $ *}


{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.create_new_customer}</h2>

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="cu_name">{$lang_str.ff_customer_name}:</label></td>
	<td>{$form.cu_name}</td>
	</tr>
	<tr>
	<td><label for="cu_name">{$lang_str.ff_address}:</label></td>
	<td>{$form.cu_address}</td>
	</tr>
	<tr>
	<td><label for="cu_name">{$lang_str.ff_phone}:</label></td>
	<td>{$form.cu_phone}</td>
	</tr>
	<tr>
	<td><label for="cu_name">{$lang_str.ff_email}:</label></td>
	<td>{$form.cu_email}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

<div class="swBackToMainPage"><a href="{url url='1_new_domain.php' uniq=1}">{$lang_str.l_back}</a></div>

<br>
{include file='_tail.tpl'}
