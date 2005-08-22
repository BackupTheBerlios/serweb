<?php
/*
 * $Id: get_js.php,v 1.1 2005/08/22 14:35:12 kozlik Exp $
 */

Header("content-type: text/js");

require("../../modules/".$_GET['mod']."/".$_GET['js'])

?>
