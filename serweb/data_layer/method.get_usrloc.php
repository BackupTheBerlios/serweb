<?
/*
 * $Id: method.get_usrloc.php,v 1.2 2004/09/22 11:08:32 kozlik Exp $
 */

class CData_Layer_get_usrloc {
	var $required_methods = array('get_location');
	
	/*
	 * get all USRLOC entries of user@domain
	 */
	
	function get_usrloc($user, $domain, &$errors){
		global $config, $sess, $uid;

		$ul_name=$user."@".$domain."\n";
		$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
		$config->ul_table."\n".		//table
		$ul_name."\n";				//username

		$fifo_out=write2fifo($fifo_cmd, $err, $status);
		if ($err or !$fifo_out) {
			$errors=array_merge($errors, $err); // No!
			return false;
		}
		if (!$fifo_out) return false;

		if (substr($status,0,1)!="2" and substr($status,0,3)!="404") {$errors[]=$status; return false; }

		$out=array();	
		$out_arr=explode("\n", $fifo_out);

		$i=0;	
		foreach($out_arr as $val){
			if (!ereg("^[[:space:]]*$", $val)){
				if (ereg("<([^>]*)>;q=([0-9.]*);expires=(-?[0-9]*)", $val, $regs)){

					$expires=date('Y-m-d H:i',time()+$regs[3]);
			
					if (Substr($expires,0,10)==date('Y-m-d')) $date=Substr($expires,11,5);
					else $date=Substr($expires,0,10);

					$out[$i]['uri']=$regs[1];
					$out[$i]['q']=$regs[2];
					$out[$i]['expires']=$date;
					$out[$i]['geo_loc']=$this->get_location($regs[1], $errors);
	
					$out[$i]['url_dele'] = $sess->url("my_account.php?kvrk=".uniqID("").
														"&del_contact=".rawURLEncode($regs[1]).
														($uid?("&".userauth_to_get_param($uid, 'u')):""));
					$i++;
				}
				else { $errors[]="sorry error -- invalid output from fifo"; return false; }
			}
		}
		return $out;
	}
	
}
?>
