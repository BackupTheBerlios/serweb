<?
/*
 * $Id: ser_moni_update.php,v 1.1 2003/03/17 18:18:35 kozlik Exp $
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
	var $marginal_preiod_begin;
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
		$marginal_preiod_begin = $now - $config->ser_moni_marginal_period_length;	// m
		$aggregation_from = $now - $config->ser_moni_aggregation_interval;			// x

		$this->now = date("Y-m-d H:i:s", $now);
		$this->marginal_preiod_begin = date("Y-m-d H:i:s", $marginal_preiod_begin);
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
		
		$q="select id from ".$config->table_ser_mon." where param='".$this->param."' and time <= '".$this->marginal_preiod_begin."' order by time desc";
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
			"values ('".$this->now."', ".($this->get_last_id()+1).", '".$this->param."', ".$new_value.", ".$increment.")";
		$res=MySQL_Query($q);
		if (!$res) { echo "insert_new_value: error in SQL query, line: ".__LINE__."\n"; return false;}
		return true;
	}
	
	function drop_old_values(){
		global $config;

		$q="delete from ".$config->table_ser_mon." where param='".$this->param."' and time<'".$this->aggregation_from."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "drop_old_values: error in SQL query, line: ".__LINE__."\n"; return false;}
		return true;
	}
	
	function update_aggregations($new_value){
		global $config;
	
		$q="select s_value, s_increment, mv, last_aggregated_increment from ".$config->table_ser_mon_agg." where param='".$this->param."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "update_aggregations: error in SQL query, line: ".__LINE__."\n"; return false;}
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

		$last_value=$this->get_last_value();
		if (is_null($last_value)) $increment=0;
		else $increment=abs($new_value - $this->get_last_value());

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
				$q="select id, increment from ".$config->table_ser_mon." where param='".$this->param."' and time <= '".$this->marginal_preiod_begin."' order by id";
			}
		}
		else{
			$q="select id, increment from ".$config->table_ser_mon." where param='".$this->param."' and time <= '".$this->marginal_preiod_begin."' and id > ".$last_agg_inc." order by id";
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

		$num_of_marginal_periods=($config->ser_moni_aggregation_interval-$config->ser_moni_marginal_period_length)/$config->ser_moni_marginal_period_length;
		
		$ad=$s_increment/$num_of_marginal_periods;
		$av=$s_value/$this->count_values();
		
		if ($sql_act=="insert"){
			$this->insert_aggregations_sql($s_value, $s_increment, $last_agg_inc, $av, $mv, $ad, $new_value, $this->now);
		}
		else {
			$this->update_aggregations_sql($s_value, $s_increment, $last_agg_inc, $av, $mv, $ad, $new_value, $this->now);
		}
		
		return true;
		
	}
	
	function update_aggregations_sql($s_value, $s_increment, $last_aggregated_increment, $av, $mv, $ad, $lv, $lastupdate){
		global $config;

		$q="update ".$config->table_ser_mon_agg.
		   " set s_value=".$s_value.", s_increment=".$s_increment.", last_aggregated_increment=".$last_aggregated_increment.
		   		", av=".$av.", mv=".$mv.", ad=".$ad.", lv=".$lv.", lastupdate='".$lastupdate."' ".
		   " where param='".$this->param."'";
		$res=MySQL_Query($q);
		if (!$res) { echo "update_aggregations_sql: error in SQL query, line: ".__LINE__."\n"; return false;}

		return true;
	}
	
	function insert_aggregations_sql($s_value, $s_increment, $last_aggregated_increment, $av, $mv, $ad, $lv, $lastupdate){
		global $config;

		$q="insert into ".$config->table_ser_mon_agg." (param, s_value, s_increment, last_aggregated_increment, av, mv, ad, lv, lastupdate) ".
		   "values ('".$this->param."', ".$s_value.", ".$s_increment.", ".$last_aggregated_increment.", ".$av.", ".$mv.", ".$ad.", ".$lv.", '".$lastupdate."')";
		$res=MySQL_Query($q);
		if (!$res) { echo "insert_aggregations_sql: error in SQL query, line: ".__LINE__."\n"; return false;}

		return true;
	}

}
?>