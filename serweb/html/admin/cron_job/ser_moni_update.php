<?
/*
 * $Id: ser_moni_update.php,v 1.2 2003/04/10 23:36:42 kozlik Exp $
 */

/*

- cas (diskretne)
  - soucasny cas: n (tj. celkovy pocet samplu)
  - zacatek marginalni periody (tj. posledni doby, o ktere si udrzuji
    detailni prehled): m
  - od kdy se uchovavaji hodnoty: x (se starsimi hodnotami se nepocita)
  - pocet samplu za hodinu: #=n-m
  - samplovaci perioda (okno): T (1 hodina, pokryva #+1 samplu)

- vstup do cron-jobu (ktery je zpusteny #-krat za hodinu)
  - soucasna hodnota (sample): v[n]

- pomocne promenne popisujici stav v "minulcy kolech" jsou ulozene v mysql
  a aktualizuji se v kazdem kole
  - posledni cas: n
    aktualizace: n=n+1
  - posledni hodnota v[n] -  pro pocitani nove odchylky 
    aktualizace: nova merena hodnota
  - prirustky za posledni T d[m+1..n]: abs(v[m+1]-v[m]), ..., abs(v[n]-v[n-1])
     pro pocitani celkove odchylky za posledni T 
    aktualizace: prida se abs(v[n+1]-v[n]) - pred aktualizace v[n] 
                 d[m+1] se vynda
  - historicke hodnoty se agreguji aby jednotlive samply nezabiraly misto     a nemusela se po kazde  znova pocitat jejich suma
     - suma veskerych predeslych samplu: sv =SUM(v[x..n])
       aktualizace: zvysi se o v[n+1] a snizi o v[x-1]
     - suma prirustku pred T: sd=SUM(d[x+1..m])
       aktualizace: zvysi se o d[m+1] a snizi o d[x]

 - na webu se zobrazuje:
   - celkove hodnoty
      - soucasna hodnota: v[n]
      - prumerna hodnota: av=(sv+v[n])/(n-x)
   - diferencialni hodnoty
     - pocet zmen za posledni hodinu: mv=SUM(d[m+1..n])
     - prumerny pocet zmen na hodinu: sd/(m-n)

*/
 
class Ser_moni {
	var $param;
	var $last_id;
	var $last_value;
	var $last_agg_increment_id;
	
	var $now;
	var $marginal_period_begin;
	var $aggregation_from;
	
	function Ser_moni ($param){
		$this->param=$param;
		$this->last_id=null;
		$this->last_value=null;
		$this->last_agg_increment_id=null;
	}
	
	function update ($new_value){
		global $config;

		$now = time(); 																// n
		$marginal_period_begin = $now - $config->ser_moni_marginal_period_length;	// m
		$aggregation_from = $now - $config->ser_moni_aggregation_interval;			// x

		$this->now = date("Y-m-d H:i:s", $now);
		$this->marginal_period_begin = date("Y-m-d H:i:s", $marginal_period_begin);
		$this->aggregation_from = date("Y-m-d H:i:s", $aggregation_from);
		
		$this->insert_new_value($new_value);
		$this->update_aggregations($new_value);
		$this->drop_old_values();
		
		$this->last_id++;
		$this->last_value=$new_value;
	}
	
	function get_last_id (){
		global $config;

		if (! is_null($this->last_id)) return $this->last_id;
		
		$q="select max(id) from ".$config->table_ser_mon." where param='".$this->param."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "get_last_id: error in SQL query, line: ".__LINE__."\n"; return -1;}
		$row=MySQL_Fetch_Row($res);
		$this->last_id=$row[0];

		if (is_null($this->last_id)) $this->last_id=0;		// if no matching rows in database
		
		return $this->last_id;
	}

	function get_last_value (){
		global $config;

		if (! is_null($this->last_value)) return $this->last_value;
		
		$q="select value from ".$config->table_ser_mon." where param='".$this->param."' and id=".$this->get_last_id();
		$res=MySQL_Query($q);
		if (!$res) { echo "get_last_value: error in SQL query, line: ".__LINE__."\n"; return -1;}

		if (!MySQL_num_rows($res)) return null;				// if no matching rows in database
	
		$row=MySQL_Fetch_Row($res);
		$this->last_value=$row[0];

		return $row[0];
	}

	// get ID of last aggregated increment
	function get_last_agg_increment_id (){
		global $config;

		if (! is_null($this->last_agg_increment_id)) return $this->last_agg_increment_id;
		
		$q="select last_aggregated_increment from ".$config->table_ser_mon_agg." where param='".$this->param."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "get_last_agg_increment_id: error in SQL query, line: ".__LINE__."\n"; return -1;}

		if (!MySQL_num_rows($res)) return -1;				// if no matching rows in database
		
		$row=MySQL_Fetch_Row($res);
		$this->last_agg_increment_id=$row[0];

		return $row[0];
	}

	function calculate_agg_increment_id (){
		global $config;
		
		$q="select id from ".$config->table_ser_mon." where param='".$this->param."' and time <= '".$this->marginal_period_begin."' order by time desc";
		$res=MySQL_Query($q);
		if (!$res) { echo "calculate_agg_increment_id: error in SQL query, line: ".__LINE__."\n"; return -1;}

		if (!MySQL_num_rows($res)) return -1;				// if no matching rows in database
		
		$row=MySQL_Fetch_Row($res);
		return $row[0];
	}

	function count_values (){
		global $config;
		
		$q="select count(*) from ".$config->table_ser_mon." where param='".$this->param."' and time>'".$this->aggregation_from."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "count_values: error in SQL query, line: ".__LINE__."\n"; return -1;}
		$row=MySQL_Fetch_Row($res);
		return $row[0];
	}

	function insert_new_value($new_value){
		global $config;

		$last_value=$this->get_last_value();
		if (is_null($last_value)) $increment=0;
		else $increment=abs($new_value - $this->get_last_value());
		
		$q="insert into ".$config->table_ser_mon." (time, id, param, value, increment) ".
			"values ('".$this->now."', ".					//time
						($this->get_last_id()+1).", '".		//id
						$this->param."', ".					//name of param
						$new_value.", ".					//value of param
						$increment.")";						//diferent between this value and last value
		$res=MySQL_Query($q);
		if (!$res) { echo "insert_new_value: error in SQL query, line: ".__LINE__."\n"; return false;}
		return true;
	}

	//drop values older then "aggregation_from"
	function drop_old_values(){
		global $config;

		$q="delete from ".$config->table_ser_mon." where param='".$this->param."' and time<'".$this->aggregation_from."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "drop_old_values: error in SQL query, line: ".__LINE__."\n"; return false;}
		return true;
	}

	//return how old is first value in database in seconds
	function get_how_old_is_first_value(){
		global $config;
	
		$q="select unix_timestamp('".$this->now."') - unix_timestamp(min(time)) ".
			"from ".$config->table_ser_mon." where param='".$this->param."' and time >= '".$this->aggregation_from."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "get_how_old_is_first_value: error in SQL query, line: ".__LINE__."\n"; return 0;}

		if (!MySQL_num_rows($res)) return 0;				// if no rows in database
		
		$row=MySQL_Fetch_Row($res);

		if (is_null($row[0])) $row[0]=0;

		return $row[0];
	
	}
	
	
	//return minimum and maximum value of this param
	//return object
	// 		$row->min
	//		$row->max
	function get_min_max_value(){
		global $config;
	
		$q="select min(value) as min, max(value) as max from ".$config->table_ser_mon." where param='".$this->param."' and time >= '".$this->aggregation_from."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "get_min_max_value: error in SQL query, line: ".__LINE__."\n"; return false;}

		if (!MySQL_num_rows($res)) return false;				// if no rows in database
		
		$row=MySQL_Fetch_Object($res);

		if (is_null($row->min)) $row->min=0;
		if (is_null($row->max)) $row->max=0;

		return $row;
	}

	//return minimum and maximum number of increments per marginal period
	//return object
	// 		$row->min
	//		$row->max
	function get_min_max_increment(){
		global $config;


		$tmp_table = uniqid("tmp");
		$marginal_funct="truncate((unix_timestamp('".$this->now."')-unix_timestamp(time))/".$config->ser_moni_marginal_period_length.", 0)";

		//create temporary table where we store number of increments per marginal period
		
		$q=	" create temporary table ".$tmp_table.
			" select sum(increment) as s_increment, ".$marginal_funct." as marg_f ".
			" from ".$config->table_ser_mon.
			" where param='".$this->param."' and time >= '".$this->aggregation_from."'".
			" group by marg_f ";
		$res=MySQL_Query($q);
		if (!$res) { echo "get_min_max_increment: error in SQL query, line: ".__LINE__."\n"; return false;}

		//get number of oldest m. period
		
		$q= "select max(marg_f) from ".$tmp_table;
		$res=MySQL_Query($q);
		if (!$res) { echo "get_min_max_increment: error in SQL query, line: ".__LINE__."\n"; return false;}
		$row=MySQL_Fetch_Row($res);
		$old_m_f=$row[0];
		

		//select minimum and maximum number of increments per marginal period
		//without oldest m. period - it may not be whole
		
		$q=	" select min(s_increment) as min, max(s_increment) as max ".
			" from ".$tmp_table.
			" where marg_f!=".$old_m_f;
		$res=MySQL_Query($q);
		if (!$res) { echo "get_min_max_increment: error in SQL query, line: ".__LINE__."\n"; return false;}

		if (!MySQL_num_rows($res)) return false;				// if no rows in database
		
		$row=MySQL_Fetch_Object($res);

		//drop temporary table
		$q= "drop table ".$tmp_table;
		$res=MySQL_Query($q);
		if (!$res) { echo "get_min_max_increment: error in SQL query, line: ".__LINE__."\n";}

		if (is_null($row->min)) $row->min=0;
		if (is_null($row->max)) $row->max=0;
		
		return $row;
	}
	
	//update table with aggregated values	
	function update_aggregations($new_value){
		global $config;
		
	/*
		DB fields:
		----------
		
		param						- name of param
		s_value						- aggregated values form "aggregation_from" to "now"
		s_increment					- aggregated increments from "aggregation_from" to "marginal_period_begin"
		last_aggregated_increment	- ID of last increment added to s_increment
		av							- average value
		mv							- aggregated increments form "marginal_period_begin" to "now"
		ad							- average number of increments per marginal period
		lv							- last value
		min_val						- minimum value
		max_val						- maximum value
		min_inc						- minimum number of increments per marginal period
		max_inc						- maximum number of increments per marginal period
		lastupdate					- when this row was updated
		
	*/
	
		$q="select s_value, s_increment, mv, last_aggregated_increment from ".$config->table_ser_mon_agg." where param='".$this->param."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "update_aggregations: error in SQL query, line: ".__LINE__."\n"; return false;}

		/*check if there is entry for this param in the table*/
		if (MySQL_num_rows($res)){
			$row=MySQL_Fetch_Object($res);
			$sql_act="update";
			
			$s_value=$row->s_value;
			$s_increment=$row->s_increment;
			$mv=$row->mv;
			$last_agg_inc=$row->last_aggregated_increment;
		}
		else{
			$sql_act="insert"; //no record for this param in table
			
			$s_value=0;
			$s_increment=0;
			$mv=0;
			$last_agg_inc=-1;
		}

		// last value of this param
		$last_value=$this->get_last_value();
		
		// $increment is diference between new value and last value
		if (is_null($last_value)) $increment=0;
		else $increment=abs($new_value - $this->get_last_value());

		// get ID of last aggregated increment
		$last_agg_inc=$this->get_last_agg_increment_id();

		
		//select values and incremets which will be deleted and subtract them from aggregated values and increments
		
		$q="select value, increment from ".$config->table_ser_mon." where param='".$this->param."' and time < '".$this->aggregation_from."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "update_aggregations: error in SQL query, line: ".__LINE__."\n"; return false;}

		$old_values=0;
		$old_increments=0;
		while ($row=MySQL_fetch_object($res)){
			$old_values+=$row->value;
			$old_increments+=$row->increment;
		}	

		//select increments which now will be before T
		//and subtract them from mv and add them to s_increment

		$m_incremetns=0;
		if ($last_agg_inc<0) {	//no icrements has been aggregated yet, check if now is time to do it
			$last_agg_inc=$this->calculate_agg_increment_id(); 
			if ($last_agg_inc<0){ // no still is no time to begin aggregating increments
				$q="";
			} else { //yes there is increments in database which were done before T
				$q="select id, increment from ".$config->table_ser_mon." where param='".$this->param."' and time <= '".$this->marginal_period_begin."' order by id";
			}
		}
		else{
			$q="select id, increment from ".$config->table_ser_mon." where param='".$this->param."' and time <= '".$this->marginal_period_begin."' and id > ".$last_agg_inc." order by id";
		}
		
		if ($q){
			$res=MySQL_Query($q);
			if (!$res) { echo "update_aggregations: error in SQL query, line: ".__LINE__."\n"; return false;}

			while ($row=MySQL_fetch_object($res)){
				$m_increments+=$row->increment;
				$last_agg_inc=$row->id;
			}	
		}
		
		//calculate aggregated values
		
		$s_value+=$new_value-$old_values;
		$s_increment+=$m_increments-$old_increments;
		$mv+=$increment-$m_increments;
		
	
		$this->last_agg_increment_id=$last_agg_inc;


//		$num_of_marginal_periods=($config->ser_moni_aggregation_interval-$config->ser_moni_marginal_period_length)/$config->ser_moni_marginal_period_length;
		$num_of_marginal_periods=max(1, ($this->get_how_old_is_first_value()-$config->ser_moni_marginal_period_length)/$config->ser_moni_marginal_period_length);

		$ad=$s_increment/$num_of_marginal_periods;
			
		$av=$s_value/$this->count_values();

		$mm_val = $this->get_min_max_value();
		$mm_inc = $this->get_min_max_increment();
		
		if ($mm_val){
			$min_val = round(max(0, $mm_val->min - (($mm_val->max - $mm_val->min) / 4)));
			$max_val = $mm_val->max;
		}
		else{
			$min_val = 0;
			$max_val = 0;
		}

		if ($mm_inc){
			$min_inc = round(max(0, $mm_inc->min - (($mm_inc->max - $mm_inc->min) / 4)));
			$max_inc = $mm_inc->max;
		}
		else{
			$min_inc = 0;
			$max_inc = 0;
		}
		
		if ($sql_act=="insert"){
			$this->insert_aggregations_sql($s_value, $s_increment, $last_agg_inc, $av, $mv, $ad, $new_value, $min_val, $max_val, $min_inc, $max_inc, $this->now);
		}
		else {
			$this->update_aggregations_sql($s_value, $s_increment, $last_agg_inc, $av, $mv, $ad, $new_value, $min_val, $max_val, $min_inc, $max_inc, $this->now);
		}
		
		return true;
		
	}
	
	function update_aggregations_sql($s_value, $s_increment, $last_aggregated_increment, $av, $mv, $ad, $lv, $min_val, $max_val, $min_inc, $max_inc, $lastupdate){
		global $config;

		$q="update ".$config->table_ser_mon_agg.
		   " set s_value=".$s_value.", s_increment=".$s_increment.", last_aggregated_increment=".$last_aggregated_increment.
		   		", av=".$av.", mv=".$mv.", ad=".$ad.", lv=".$lv.
				", min_val=".$min_val.", max_val=".$max_val.", min_inc=".$min_inc.", max_inc=".$max_inc.
				", lastupdate='".$lastupdate."' ".
		   " where param='".$this->param."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "update_aggregations_sql: error in SQL query, line: ".__LINE__."\n"; return false;}

		return true;
	}
	
	function insert_aggregations_sql($s_value, $s_increment, $last_aggregated_increment, $av, $mv, $ad, $lv, $min_val, $max_val, $min_inc, $max_inc, $lastupdate){
		global $config;

		$q="insert into ".$config->table_ser_mon_agg." (param, s_value, s_increment, last_aggregated_increment, av, mv, ad, lv, min_val, max_val, min_inc, max_inc, lastupdate) ".
		   "values ('".$this->param."', ".$s_value.", ".$s_increment.", ".$last_aggregated_increment.", ".$av.", ".$mv.", ".$ad.", ".$lv.", ".
		   			$min_val.", ".$max_val.", ".$min_inc.", ".$max_inc.", '".$lastupdate."')";
		$res=MySQL_Query($q);
		if (!$res) { echo "insert_aggregations_sql: error in SQL query, line: ".__LINE__."\n"; return false;}

		return true;
	}

}
?>