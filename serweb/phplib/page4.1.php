<?php
/*
 * $Id: page4.1.php,v 1.2 2005/11/30 09:58:16 kozlik Exp $
 */ 

function page_open($feature, $what=null) {
	global $_PHPLIB;
	static $loaded_features = array();

	if (is_null($what)) $what = array('sess', 'auth', 'perm');
	if (is_string($what)) $what = array($what);
	

	if (in_array("sess", $what) and !in_array("sess", $loaded_features)){
		if (isset($feature["sess"])) {
			global $sess;
			$sess = new $feature["sess"];
			$sess->start();
	
			## Load the auto_init-File, if one is specified.
			if (($sess->auto_init != "") && !$sess->in) {
				$sess->in = 1;
				include($_PHPLIB["libdir"] . $sess->auto_init);
				if ($sess->secure_auto_init != "") {
					$sess->freeze();
				}
			} 

			$loaded_features[] = 'sess';
		}
	}

	if (in_array("auth", $what) and !in_array("auth", $loaded_features) and
	    in_array("sess", $loaded_features)){

		# the auth feature depends on sess
		if (isset($feature["auth"])) {
			if (isset($_SESSION['auth']) and is_object($_SESSION['auth'])) {
				$_SESSION['auth'] = $_SESSION['auth']->check_feature($feature["auth"]);
			} else {
				$_SESSION['auth'] = new $feature["auth"];
			}
			$_SESSION['auth']->start();

			$loaded_features[] = 'auth';
		}
	}

	if (in_array("perm", $what) and !in_array("perm", $loaded_features) and
	    in_array("auth", $loaded_features)){

		# the perm feature depends on auth (and sess)
		if (isset($feature["perm"])) {
			global $perm;
			
			if (!is_object($perm)) {
				$perm = new $feature["perm"];
				$perm->set_auth_obj($_SESSION['auth']);
	
				$loaded_features[] = 'perm';
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
