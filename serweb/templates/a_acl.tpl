{* Smarty *}
{* $Id: a_acl.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<h2 class="swTitle">Access control list of user: {$uname}</h2>

{foreach from=$ACL_control item='row' name='acl_control'}
	{if $smarty.foreach.acl_control.first}
	<div class="swForm">
	{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	{/if}

	{* concatenate 'chk_' and $row in order to get name of form element *}
	{assign var='f_element' value="chk_$row"}
	
	<tr>
	<td><label for="{$f_element}">{$row}</label></td>
	<td>{$form.$f_element}</td>
	</tr>

	{if $smarty.foreach.acl_control.last}
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
	{$form.finish}
	</div>
	{/if}
{foreachelse}
<div class="swNumOfFoundRecords">You haven't any privileges to control ACL</div>
{/foreach}

<div class="swBackToMainPage"><a href="{url url='users.php' uniq=1}">back to main page</a></div>

{include file='_tail.tpl'}
