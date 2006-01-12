{* Smarty *}
{* $Id: 3_finish.tpl,v 1.2 2006/01/12 16:45:51 kozlik Exp $ *}

{literal}
<style type="text/css">
	#uname, #domain, #fname, #lname, #email, #phone, #timezone {width:250px;}
</style>	
{/literal}


{include file='_head.tpl'}

{if !$parameters.errors}
	<br>

	<p>{$lang_str.domain_setup_success}</p>
	<br>
	<br>
{/if}

<div class="swBackToMainPage"><a href="javascript: opener.location.reload(); window.close();">{$lang_str.l_close_window}</a></div>

<br>
{include file='_tail.tpl'}



