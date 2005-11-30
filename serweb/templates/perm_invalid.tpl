{* Smarty *}
{* $Id: perm_invalid.tpl,v 1.1 2005/11/30 11:17:25 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="frameHeading">Permission denied</div>
<br />


<div class="frameBodyPadding" style="font-weight:bolder; color:red;">
	Sorry, your session has been authenticated with a user name 
	{$auth_uname}@{$auth_realm}.
</div>
<div class="frameBodyPadding">
	Your permissions are: '{if $perm_have}{$perm_have}{else}none{/if}'. But they 
	are not sufficient to access this page.
</div>

<br>
{include file='_tail.tpl'}
