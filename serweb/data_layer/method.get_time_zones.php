<?
/*
 * $Id: method.get_time_zones.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_time_zones {
	var $required_methods = array();
	
	/*
	 * get list of timezones from zone.tab
	 */

	function get_time_zones(&$errors){
		global $config;

		@$fp=fopen($config->zonetab_file, "r");
		if (!$fp) {$errors[]="Cannot open zone.tab file"; return array();}
		$out=array();

		while (!feof($fp)){
			$line=FgetS($fp, 512);
			if (substr($line,0,1)=="#") continue; //skip comments
			if (!$line) continue; //skip blank lines

			$line_a=explode("\t", $line);

			$line_a[2]=trim($line_a[2]);
			if ($line_a[2]) $out[]=$line_a[2];
		}

		fclose($fp);
		sort($out);
		return $out;
	}
	
}
?>
