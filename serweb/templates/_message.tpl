{* Smarty *}
{* $Id: _message.tpl,v 1.3 2006/03/08 15:36:27 kozlik Exp $ *}

{*
{if $message}
	<div class="message">{$message.short}</div>
{/if}
*}

{foreach from=$message item='row' key='key'}
	{if $key === "short"}				{* backward compatibility - if only one message is given *}
	{elseif $key === "long"}
	<div class="message">{$row|escape}</div>
	{else}
	<div class="message">{$row.long|escape}</div>
	{/if}
{/foreach}

