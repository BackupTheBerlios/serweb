{* Smarty *}
{* $Id: _errors.tpl,v 1.2 2006/03/08 15:36:27 kozlik Exp $ *}

{foreach from=$errors item='row'}
	<div class="errors">{$row|escape}</div>
{/foreach}
