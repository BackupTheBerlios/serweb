{* Smarty *}
{* $Id: a_credentials.tpl,v 1.3 2006/09/08 12:27:35 kozlik Exp $ *}

{literal}
<style type="text/css">
	#cr_uname, #cr_realm, #cr_passw{
		width:150px;
	}
</style>
{/literal}

{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.change_credentials_of_user}: {$uname|escape}@{$domain|escape}</h2>

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="cr_uname">{$lang_str.ff_username}:</label></td>
	<td>{$form.cr_uname}</td>
	</tr>
	<tr>
	<td><label for="cr_domain">{$lang_str.ff_domain}:</label></td>
	<td>{$form.cr_domain}</td>
	</tr>
	<tr>
	<td><label for="cr_passw">{$lang_str.ff_password}:</label></td>
	<td>{$form.cr_passw}</td>
	</tr>
	<tr>
	<td><label for="cr_for_ser">{$lang_str.ff_for_ser}:</label></td>
	<td>{$form.cr_for_ser}</td>
	</tr>
	<tr>
	<td><label for="cr_for_serweb">{$lang_str.ff_for_serweb}:</label></td>
	<td>{$form.cr_for_serweb}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="left">&nbsp;</td>
	<td>{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>
<br />

{foreach from=$credentials item='row' name='cr'}
	{if $smarty.foreach.cr.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_username}</th>
	<th>{$lang_str.th_domain}</th>
	<th>{$lang_str.th_password}</th>
	<th>{$lang_str.th_for_ser}</th>
	<th>{$lang_str.th_for_serweb}</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	{/if}

	<tr valign="top" class="{cycle values='swTrOdd,swTrEven'}">
	<td align="left">{$row.uname|empty2nbsp}</td>
	<td align="left">{$row.domainname|empty2nbsp}</td>
	<td align="left">{if $row.password}{$row.password}{else}------{/if}</td>
	<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.for_ser}</td>
	<td align="center" class="swValignMid">{include file="includes/yes_no.tpl" ok=$row.for_serweb}</td>
	<td align="center"><a href="{$row.url_edit}" class="actionsrow">{$lang_str.l_edit}</a></td>
	<td align="center"><a href="{$row.url_dele}"  class="actionsrow" onclick="return confirmDelete(this, '{$lang_str.realy_want_you_delete_this_credential}')">{$lang_str.l_delete}</a></td>
	</tr>

	{if $smarty.foreach.cr.last}
	</table>
	{/if}
{/foreach}

{if !$clear_text_pw}
	<br />
	<div class="swWarningBox">{$lang_str.warning_credential_changed_domain}</div>
{/if}

<div class="swBackToMainPage"><a href="{url url='users.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

<br />
{include file='_tail.tpl'}

<?php

?>
