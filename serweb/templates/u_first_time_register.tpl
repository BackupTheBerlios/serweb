{* Smarty *}
{* $Id: u_first_time_register.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

{if $parameters.errors}
<p>We are sorry, there were errors while initiating your account</p>
{else}
<p>Welcome! Thank you for registering!</p>
<p>Your SIP address is: <strong>{$sip_address}</strong></p>
<p>Your SIP phone number is: <strong>{$aliases}</strong></p>

<div class="swBackToMainPage"><a href="{url url='my_account.php' uniq=1}" class="f14">Continue by click this link</a></div>
{/if}
<br>
{include file='_tail.tpl'}

