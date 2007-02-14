<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_time_zones.php,v 1.2 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for get list of timezones
 * 
 *	@package    serweb
 */ 
class CData_Layer_get_time_zones {
	var $required_methods = array();
	
	/**
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
