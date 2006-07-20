<?php

/**
* setvar
*
* Easier way to assign variables in templates.
*
* Instead of {assign var=key value='one two'} do
* {setvar key='one two'}
*/

function smarty_function_setvar($params, &$smarty)
{
$smarty->assign($params);
}

?>
