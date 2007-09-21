{* Smarty *}
{* $Id: _errors.tpl,v 1.3 2007/09/21 14:21:21 kozlik Exp $ *}

{foreach from=$errors item='row'}
	<div class="errors">{$row|escape|nl2br}</div>
{/foreach}
