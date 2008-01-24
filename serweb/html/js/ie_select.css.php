<?php
/**
 *  Emulating Disabled Options in IE
 *  Internet Explorer 6 does not implement disabled OPTION's in a SELECT. 
 *  THis is workaround to deal with it.
 *
 *  Originally designed by Apptaro:
 *  http://apptaro.seesaa.net/article/21140090.html
 */

Header("Content-type: text/css");

/**  */
require("../set_dirs.php");

require ($_SERWEB["serwebdir"] . "../config/config_paths.php");

?>
select, option {
  behavior: url(<?php echo htmlspecialchars($config->js_src_path); ?>ie_select.htc);
}
