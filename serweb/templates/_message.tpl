{* Smarty *}
{* $Id: _message.tpl,v 1.2 2004/08/25 10:19:48 kozlik Exp $ *}

{*
{if $message}
	<div class="message">{$message.short}</div>
{/if}
*}

{foreach from=$message item='row' key='key'}
	{if $key === "short"}				{* backward compatibility - if only one message is given *}
	{elseif $key === "long"}
	<div class="message">{$row}</div>
	{else}
	<div class="message">{$row.long}</div>
	{/if}
{/foreach}

