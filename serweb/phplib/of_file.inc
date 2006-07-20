<?php
/* OOHForms: file
 *
 * Copyright (c) 1998 by Jay Bloodworth
 *
 * $Id: of_file.inc,v 1.4 2006/07/20 16:11:37 kozlik Exp $
 */

class of_file extends of_element {

  var $isfile = true;
  var $size;

  function of_file($a) {
    $this->setup_element($a);
  }

  function self_get($val,$which, &$count) {
    $str = "";
    
    $str .= "<input type='hidden' name='MAX_FILE_SIZE' value=$this->size>\n";
    $str .= "<input type='file' name='$this->name' id='$this->name'";
    if ($this->extrahtml)
      $str .= " $this->extrahtml";
    $str .= " class=\"inpFile";
    if (!empty($this->class)){
		$str .= " ".implode(" ", (array)$this->class);
	}
    $str .= "\"";

    if (!empty($this->disabled)){
	    $str .= " disabled";
    }

    $str .= ">";
    
    $count = 2;
    return $str;
  }

} // end FILE
?>
