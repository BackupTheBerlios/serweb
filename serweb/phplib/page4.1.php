<?php
/*
 * $Id: page4.1.php,v 1.1 2005/11/23 09:59:27 kozlik Exp $
 */ 

function page_open($feature) {
	global $_PHPLIB;
	
	# enable sess and all dependent features.
	if (isset($feature["sess"])) {
		global $sess;
		$sess = new $feature["sess"];
		$sess->start();
		
		# the auth feature depends on sess
		if (isset($feature["auth"])) {
			if (isset($_SESSION['auth']) and is_object($_SESSION['auth'])) {
				$_SESSION['auth'] = $_SESSION['auth']->check_feature($feature["auth"]);
			} else {
				$_SESSION['auth'] = new $feature["auth"];
			}
			$_SESSION['auth']->start();
			
			
			# the perm feature depends on auth and sess
			if (isset($feature["perm"])) {
				global $perm;
				
				if (!is_object($perm)) {
					$perm = new $feature["perm"];
					$perm->set_auth_obj($_SESSION['auth']);
				}
			}
		}
		
		## Load the auto_init-File, if one is specified.
		if (($sess->auto_init != "") && !$sess->in) {
			$sess->in = 1;
			include($_PHPLIB["libdir"] . $sess->auto_init);
			if ($sess->secure_auto_init != "") {
				$sess->freeze();
			}
		} 
	}
}

function page_close() {
	global $sess;
	if (is_object($sess)) {
		$sess->freeze();
	}
}

?>
