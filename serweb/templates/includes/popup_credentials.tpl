{* Smarty *}
{* $Id: popup_credentials.tpl,v 1.3 2007/10/04 10:58:56 kozlik Exp $ *}
{foreach from=$credentials item='row' name='cr_cr'}
<div>{$row.uname|escape|escape|empty2nbsp}@{if $row.domainname}{$row.domainname|escape|escape}{else}&amp;lt;no domain&amp;gt;{/if}</div>
{foreachelse}
	<div>{$lang_str.user_has_no_credentials}</div>
{/foreach}
