<?
/*
 * $Id: read_ser_moni.php,v 1.1 2004/10/12 14:42:20 kozlik Exp $
 */

require "prepend.php";
require "ser_moni_update.php";

do{
	if (!$db = connect_to_db($errors)) break;

	$time=date("Y-m-d H:i:s");
	$q_part1="time";
	$q_part2="'$time'";

	/* Transaction Statistics */

	/* construct FIFO command */
	$fifo_cmd=":t_stats:".$config->reply_fifo_filename."\n\n";

	$fifo_out=write2fifo($fifo_cmd, $errors, $status);
	if ($errors) break;
	/* we accept any 2xx as ok */
	if (substr($status,0,1)!="2") {$errors[]=$status; break; }

	if (!$fifo_out) {$errors[]="No out from fifo when reading transaction statistics"; break; }

	$out_arr=explode("\n", $fifo_out);
	
	$out_l=reset($out_arr);
	//	example: "Current: 1 (434 waiting) Total: 80845 (71 local)"
	if (ereg("[^0-9]*([0-9]+)[^0-9]*([0-9]+)[^0-9]*([0-9]+)[^0-9]*([0-9]+)", $out_l, $regs)){

		$sm=new Ser_moni("ts_current", $db);
		$sm->update($regs[1]);
		
		$sm=new Ser_moni("ts_waiting", $db);
		$sm->update($regs[2]);

		$sm=new Ser_moni("ts_total", $db);
		$sm->update($regs[3]);

		$sm=new Ser_moni("ts_total_local", $db);
		$sm->update($regs[4]);

//		$q_part1.=", ts_current, ts_waiting, ts_total, ts_total_local";
//		$q_part2.=", ".$regs[1].", ".$regs[2].", ".$regs[3].", ".$regs[4];
	}
	else { $errors[]="Invalid output from fifo - transaction statistic line 1"; break; }

	$out_l=next($out_arr);
	//	example: "Replied localy: 63106"
	if (ereg("[^0-9]*([0-9]+)", $out_l, $regs)){

		$sm=new Ser_moni("ts_replied", $db);
		$sm->update($regs[1]);

//		$q_part1.=", ts_replied";
//		$q_part2.=", ".$regs[1];
	}
	else { $errors[]="Invalid output from fifo - transaction statistic line 2"; break; }
	
	$out_l=next($out_arr);
	//	example: "Completion status 6xx: 81, 5xx: 1253, 4xx: 62758, 3xx: 48,2xx: 18205"
	if (ereg("[^0-9]*6xx: ([0-9]+)[^0-9]*5xx: ([0-9]+)[^0-9]*4xx: ([0-9]+)[^0-9]*3xx: ([0-9]+)[^0-9]*2xx: ([0-9]+)", $out_l, $regs)){

		$sm=new Ser_moni("ts_6xx", $db);
		$sm->update($regs[1]);

		$sm=new Ser_moni("ts_5xx", $db);
		$sm->update($regs[2]);

		$sm=new Ser_moni("ts_4xx", $db);
		$sm->update($regs[3]);

		$sm=new Ser_moni("ts_3xx", $db);
		$sm->update($regs[4]);

		$sm=new Ser_moni("ts_2xx", $db);
		$sm->update($regs[5]);

//		$q_part1.=", ts_6xx, ts_5xx, ts_4xx, ts_3xx, ts_2xx";
//		$q_part2.=", ".$regs[1].", ".$regs[2].", ".$regs[3].", ".$regs[4].", ".$regs[5];
	}
	else { $errors[]="Invalid output from fifo - transaction statistic line 3"; break; }


	/* Stateless Server Statistics */

	/* construct FIFO command */
	$fifo_cmd=":sl_stats:".$config->reply_fifo_filename."\n\n";

	$fifo_out=write2fifo($fifo_cmd, $errors, $status);
	if ($errors) break;
	/* we accept any 2xx as ok */
	if (substr($status,0,1)!="2") {$errors[]=$status; break; }

	if (!$fifo_out) {$errors[]="No out from fifo when reading Stateless Server Statistics"; break; }

	$out_arr=explode("\n", $fifo_out);

	$out_l=reset($out_arr);
	//	example: "200: 414748 202: 0 2xx: 0"
	if (ereg("200: ([0-9]+) 202: ([0-9]+) 2xx: ([0-9]+)", $out_l, $regs)){

		$sm=new Ser_moni("sl_200", $db);
		$sm->update($regs[1]);

		$sm=new Ser_moni("sl_202", $db);
		$sm->update($regs[2]);

		$sm=new Ser_moni("sl_2xx", $db);
		$sm->update($regs[3]);

//		$q_part1.=", sl_200, sl_202, sl_2xx";
//		$q_part2.=", ".$regs[1].", ".$regs[2].", ".$regs[3];
	}
	else { $errors[]="Invalid output from fifo - stateless statistic line 1"; break; }

	$out_l=next($out_arr);
	//	example: "300: 0 301: 0 302: 0 3xx: 0"
	if (ereg("300: ([0-9]+) 301: ([0-9]+) 302: ([0-9]+) 3xx: ([0-9]+)", $out_l, $regs)){

		$sm=new Ser_moni("sl_300", $db);
		$sm->update($regs[1]);

		$sm=new Ser_moni("sl_301", $db);
		$sm->update($regs[2]);

		$sm=new Ser_moni("sl_302", $db);
		$sm->update($regs[3]);

		$sm=new Ser_moni("sl_3xx", $db);
		$sm->update($regs[4]);

//		$q_part1.=", sl_300, sl_301, sl_302, sl_3xx";
//		$q_part2.=", ".$regs[1].", ".$regs[2].", ".$regs[3].", ".$regs[4];
	}
	else { $errors[]="Invalid output from fifo - stateless statistic line 2"; break; }

	$out_l=next($out_arr);
	//	example: "400: 0 401: 458724 403: 294 404: 122330 407: 62328 408: 0 483: 26 4xx: 89502"
	if (ereg("400: ([0-9]+) 401: ([0-9]+) 403: ([0-9]+) 404: ([0-9]+) 407: ([0-9]+) 408: ([0-9]+) 483: ([0-9]+) 4xx: ([0-9]+)", $out_l, $regs)){

		$sm=new Ser_moni("sl_400", $db);
		$sm->update($regs[1]);

		$sm=new Ser_moni("sl_401", $db);
		$sm->update($regs[2]);

		$sm=new Ser_moni("sl_403", $db);
		$sm->update($regs[3]);

		$sm=new Ser_moni("sl_404", $db);
		$sm->update($regs[4]);

		$sm=new Ser_moni("sl_407", $db);
		$sm->update($regs[5]);

		$sm=new Ser_moni("sl_408", $db);
		$sm->update($regs[6]);

		$sm=new Ser_moni("sl_483", $db);
		$sm->update($regs[7]);

		$sm=new Ser_moni("sl_4xx", $db);
		$sm->update($regs[8]);

//		$q_part1.=", sl_400, sl_401, sl_403, sl_404, sl_407, sl_408, sl_483, sl_4xx";
//		$q_part2.=", ".$regs[1].", ".$regs[2].", ".$regs[3].", ".$regs[4].", ".$regs[5].", ".$regs[6].", ".$regs[7].", ".$regs[8];
	}
	else { $errors[]="Invalid output from fifo - stateless statistic line 3"; break; }

	$out_l=next($out_arr);
	//	example: "500: 0 5xx: 0"
	if (ereg("500: ([0-9]+) 5xx: ([0-9]+)", $out_l, $regs)){

		$sm=new Ser_moni("sl_500", $db);
		$sm->update($regs[1]);

		$sm=new Ser_moni("sl_5xx", $db);
		$sm->update($regs[2]);

//		$q_part1.=", sl_500, sl_5xx";
//		$q_part2.=", ".$regs[1].", ".$regs[2];
	}
	else { $errors[]="Invalid output from fifo - stateless statistic line 4"; break; }

	$out_l=next($out_arr);
	//	example: "6xx: 0"
	if (ereg("6xx: ([0-9]+)", $out_l, $regs)){
		$sm=new Ser_moni("sl_6xx", $db);
		$sm->update($regs[1]);

//		$q_part1.=", sl_6xx";
//		$q_part2.=", ".$regs[1];
	}
	else { $errors[]="Invalid output from fifo - stateless statistic line 5"; break; }

	$out_l=next($out_arr);
	//	example: "xxx: 0"
	if (ereg("xxx: ([0-9]+)", $out_l, $regs)){
		$sm=new Ser_moni("sl_xxx", $db);
		$sm->update($regs[1]);

//		$q_part1.=", sl_xxx";
//		$q_part2.=", ".$regs[1];
	}
	else { $errors[]="Invalid output from fifo - stateless statistic line 6"; break; }

	$out_l=next($out_arr);
	//	example: "failures: 10"
	if (ereg("[^0-9]*([0-9]+)", $out_l, $regs)){
		$sm=new Ser_moni("sl_failures", $db);
		$sm->update($regs[1]);

//		$q_part1.=", sl_failures";
//		$q_part2.=", ".$regs[1];
	}
	else { $errors[]="Invalid output from fifo - stateless statistic line 7"; break; }

	
//	$q="insert into ".$config->table_ser_mon." ($q_part1) values ($q_part2)";
//	$res=mySQL_query($q);
//	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	
	
	/* UsrLoc Stats */

	/* construct FIFO command */
	$fifo_cmd=":ul_stats:".$config->reply_fifo_filename."\n\n";

	$fifo_out=write2fifo($fifo_cmd, $errors, $status);
	if ($errors) break;
	/* we accept any 2xx as ok */
	if (substr($status,0,1)!="2") {$errors[]=$status; break; }

	
	if (!$fifo_out) {$errors[]="No out from fifo when reading UsrLoc Statistics"; break; }

	$out_arr=explode("\n", $fifo_out);

	$out_l=reset($out_arr);
	//skip fist line
	while ($out_l=next($out_arr)){
	//	example: "'aliases' 408 0"
		if (ereg("'([^']*)' ([0-9]+) ([0-9]+)", $out_l, $regs)){

			$sm=new Ser_moni("ul_".$regs[1]."_reg", $db);
			$sm->update($regs[2]);
			
			$sm=new Ser_moni("ul_".$regs[1]."_exp", $db);
			$sm->update($regs[3]);
		
		
//			$q="insert into ".$config->table_ser_mon_ul." (time, domain, registered, expired) values ('$time', '".$regs[1]."', ".$regs[2].", ".$regs[3].")";
//			$res=mySQL_query($q);
//			if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		}
		else { $errors[]="Invalid output from fifo - UsrLoc statistic"; break; }
	}

} while (false);

if (is_array($errors)) foreach($errors as $val) echo "error: ".$val."\n";
?>