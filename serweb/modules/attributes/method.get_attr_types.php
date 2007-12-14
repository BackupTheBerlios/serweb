<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_attr_types.php,v 1.7 2007/12/14 18:41:12 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	Data layer container holding the method for get attribute types
 * 
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 
class CData_Layer_get_attr_types{
	var $required_methods = array();
	
	/**
	 * return list of all attribute types
	 * 
	 *
	 *  Possible options:
	 *	- order_by (string)      name of column the result is sorted by
	 *	- order_desc (bool)      sort the result in descending order
	 *	- filter (array)         filter criteria
	 *	- group_by_groups (bool) if true, the result entries are grouped by 
	 *	  attribute type groups
	 *	- use_pager (bool)       if true, the number of entries is limited by
	 *	  the paging feature
	 *
	 *	@param	array	$opt	options
	 *	@return bool
	 */
	 
	function get_attr_types($opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$t_at = &$config->data_sql->attr_types->table_name;
		/* col names */
		$c = &$config->data_sql->attr_types->cols;
		/* default flag values */
		$dfv = &$config->data_sql->user_attrs->flag_values;
		/* flag values */
		$fv = &$config->data_sql->attr_types->flag_values;
		/* priority values */
		$pv = &$config->data_sql->attr_types->priority_values;

	    $o_order_by = (isset($opt['order_by'])) ? $opt['order_by'] : "order";
	    $o_order_desc = (!empty($opt['order_desc'])) ? "desc" : "";

		/* construct where clause */
		$qw = array();

		if (!empty($opt['filter']['order']))     $qw[] = $opt['filter']['order']->to_sql($c->order);
		if (!empty($opt['filter']['at_name']))   $qw[] = $opt['filter']['at_name']->to_sql($c->name);
		if (!empty($opt['filter']['rich_type'])) $qw[] = $opt['filter']['rich_type']->to_sql($c->rich_type);
		if (!empty($opt['filter']['desc']))      $qw[] = $opt['filter']['desc']->to_sql($c->desc);
		if (!empty($opt['filter']['group']))     $qw[] = $opt['filter']['group']->to_sql($c->group);

//		if (!empty($opt['filter']['priority_r'])) $qw[] = $opt['filter']['priority_r']->to_sql_bool($c->priority."&".$pv['URI']);
		if (!empty($opt['filter']['priority_u'])) $qw[] = $opt['filter']['priority_u']->to_sql_bool($c->priority."&".$pv['USER']);
		if (!empty($opt['filter']['priority_d'])) $qw[] = $opt['filter']['priority_d']->to_sql_bool($c->priority."&".$pv['DOMAIN']);
		if (!empty($opt['filter']['priority_g'])) $qw[] = $opt['filter']['priority_g']->to_sql_bool($c->priority."&".$pv['GLOBAL']);

		if (!empty($opt['filter']['d_flags_s']))  $qw[] = $opt['filter']['d_flags_s']->to_sql_bool($c->default_flags."&".$dfv['DB_LOAD_SER']);
		if (!empty($opt['filter']['d_flags_sw'])) $qw[] = $opt['filter']['d_flags_sw']->to_sql_bool($c->default_flags."&".$dfv['DB_FOR_SERWEB']);

		if (!empty($opt['filter']['flags_r']))    $qw[] = $opt['filter']['flags_r']->to_sql_bool($c->flags."&".$fv['DB_FILL_ON_REG']);
		if (!empty($opt['filter']['flags_m']))    $qw[] = $opt['filter']['flags_m']->to_sql_bool($c->flags."&".$fv['DB_MULTIVALUE']);
		if (!empty($opt['filter']['flags_e']))    $qw[] = $opt['filter']['flags_e']->to_sql_bool($c->flags."&".$fv['DB_REQUIRED']);

		if ($qw) $qw = " where ".implode(' and ', $qw);
		else $qw = "";


		/* construct 'order by' rules */
		$qo = array();
		if (!empty($opt['group_by_groups'])) $qo[] = $c->group;

		if ($o_order_by) {
			if (isset($c->$o_order_by)) $qo[] = $c->$o_order_by." ".$o_order_desc;
			elseif(substr($o_order_by, 0, 8) == "d_flags_"){
				switch (substr($o_order_by, 8)){
					case "s":
						$qo[] = "(".$c->default_flags." & ".$dfv['DB_LOAD_SER'].") ".$o_order_desc;
						break;
					case "sw":
						$qo[] = "(".$c->default_flags." & ".$dfv['DB_FOR_SERWEB'].") ".$o_order_desc;
						break;
					default:
						sw_log("Unknown column '".$o_order_by."' in table 'attr_types'", PEAR_LOG_INFO);
				}
			}
			elseif(substr($o_order_by, 0, 9) == "priority_"){
				switch (substr($o_order_by, 9)){
					case "r":
						$qo[] = "(".$c->priority." & ".$pv['URI'].") ".$o_order_desc;
						break;
					case "u":
						$qo[] = "(".$c->priority." & ".$pv['USER'].") ".$o_order_desc;
						break;
					case "d":
						$qo[] = "(".$c->priority." & ".$pv['DOMAIN'].") ".$o_order_desc;
						break;
					case "g":
						$qo[] = "(".$c->priority." & ".$pv['GLOBAL'].") ".$o_order_desc;
						break;
					default:
						sw_log("Unknown column '".$o_order_by."' in table 'attr_types'", PEAR_LOG_INFO);
				}
			}
			elseif(substr($o_order_by, 0, 6) == "flags_"){
				switch (substr($o_order_by, 6)){
					case "r":
						$qo[] = "(".$c->flags." & ".$fv['DB_FILL_ON_REG'].") ".$o_order_desc;
						break;
					case "m":
						$qo[] = "(".$c->flags." & ".$fv['DB_MULTIVALUE'].") ".$o_order_desc;
						break;
					case "e":
						$qo[] = "(".$c->flags." & ".$fv['DB_REQUIRED'].") ".$o_order_desc;
						break;
					default:
						sw_log("Unknown column '".$o_order_by."' in table 'attr_types'", PEAR_LOG_INFO);
				}
			
			}
			else {
				sw_log("Unknown column '".$o_order_by."' in table 'attr_types'", PEAR_LOG_INFO);
			}
		}

		if (!empty($opt['use_pager'])){
			$q="select count(*)
		        from ".$t_at." ".$qw;

			$res=$this->db->query($q);
			if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
			$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
			$this->set_num_rows($row[0]);
			$res->free();
	
			/* if act_row is bigger then num_rows, correct it */
			$this->correct_act_row();
		}

		$q="select ".$c->name.", ".$c->raw_type.", ".$c->rich_type.", ".$c->type_spec.", 
		           ".$c->desc.", ".$c->default_flags.", ".$c->flags.", ".$c->priority.", 
		           ".$c->access.", ".$c->order.", ".$c->group."
		    from ".$t_at.$qw;

		if ($qo) $q .= " order by ".implode(", ", $qo);

		$q .= !empty($opt['use_pager']) ? $this->get_sql_limit_phrase() : "";
	
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
	
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out[$row[$c->name]] = &Attr_type::factory($row[$c->name],
			                                           $row[$c->raw_type],
												       $row[$c->rich_type],
												       is_string($row[$c->type_spec])? unserialize($row[$c->type_spec]) : null,
												       $row[$c->desc],
												       $row[$c->default_flags],
												       $row[$c->flags],
													   $row[$c->priority],
													   $row[$c->access],
													   $row[$c->order]);
			$out[$row[$c->name]]->set_group($row[$c->group]);
		}
		$res->free();
		return $out;

	}
	
}
?>
