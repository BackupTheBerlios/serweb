#!/usr/local/bin/php -q
<?
//$Id: check.php,v 1.1 2004/08/25 10:19:48 kozlik Exp $

if ($argc != 2 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) {?>
Usage: <?php echo $argv[0]; ?> <file>

<file> is the language file that you want to check
<?
	exit;
}

$fp=fopen($argv[1],'r');

//read all keys of $lang_str into array
$line_nr=0;
$lang_struct=array();
$duplicites=false;
while (!feof($fp)){
	$line=fgets($fp, 4096);
	$line_nr++;

//parse key of $lang_str array	
	if (!ereg('^[[:blank:]]*\\$lang_str\[\'([^\']+)\'\]', $line, $regs)) continue;

	$key=$regs[1];
	
	if (isset($lang_struct[$key])){
		echo "duplicated key: ".$key.", lines: ".$lang_struct[$key]." and ".$line_nr."\n";
		$duplicites=true;
		continue;
	}

	$lang_struct[$key]=$line_nr;
}

fclose($fp);

if (!$duplicites) echo "No duplicated keys found\n";
?>