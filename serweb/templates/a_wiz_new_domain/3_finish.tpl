{* Smarty *}
{* $Id: 3_finish.tpl,v 1.1 2005/11/03 11:02:11 kozlik Exp $ *}

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

<div class="swBackToMainPage"><a href="javascript: window.close();">{$lang_str.l_close_window}</a></div>

<br>
{include file='_tail.tpl'}



