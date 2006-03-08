{* Smarty *}
{* $Id: a_customers.tpl,v 1.2 2006/03/08 15:36:27 kozlik Exp $ *}


{include file='_head.tpl'}

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

{foreach from=$customers item='row' name='customers'}
	{if $smarty.foreach.customers.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.ff_customer_name}</th>
	<th>{$lang_str.ff_address}</th>
	<th>{$lang_str.ff_phone}</th>
	<th>{$lang_str.ff_email}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.name|escape|empty2nbsp}</td>
	<td align="left">{$row.address|escape|truncate:45:"..."|empty2nbsp}</td>
	<td align="left">{$row.phone|escape|truncate:45:"..."|empty2nbsp}</td>
	<td align="left">{if $row.email}<a href="mailto:{$row.email}">{$row.email|escape|truncate:45:"..."}{else}&nbsp;{/if}</td>
	<td align="center"><a href="{$row.url_edit}">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele}">{$lang_str.l_delete}</a></td>
	</tr>
	{if $smarty.foreach.customers.last}
	</table>
	{/if}
{foreachelse}
	<div class="swNumOfFoundRecords">{$lang_str.no_customers}</div>
{/foreach}

<div class="swSearchLinks">&nbsp;{pager page=$pager class_text='swNavText' class_num='swNav' class_numon='swNavActual' txt_prev='&lt;&lt;&lt;' txt_next='&gt;&gt;&gt;'}</div>

<br>
{include file='_tail.tpl'}
