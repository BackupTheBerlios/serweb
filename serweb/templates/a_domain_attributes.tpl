{* Smarty *}
{* $Id: a_domain_attributes.tpl,v 1.2 2006/03/08 15:36:27 kozlik Exp $ *}

{include file='_head.tpl'}

{if $attributes}
	<div class="swForm">
	{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
{/if}

{include file="_attr_form.tpl" attributes=$attributes form=$form}

{if $attributes}
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
	{$form.finish}
	</div>
{else}
	<div class="swNumOfFoundRecords">{$lang_str.no_attributes_defined}</div>
{/if}

<div class="swBackToMainPage"><a href="{url url='list_of_domains.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

<br>
{include file='_tail.tpl'}

