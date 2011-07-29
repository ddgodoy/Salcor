<?php
	define("SRT_ASC",1);
	define("SRT_DESC",-1);

	Class arr_multisort {
  var $arr = NULL;
  var $sortDef = NULL;

  function arr_multisort(){
    $this->arr = array();
    $this->sortDef = array();
  }
  function setArray(&$arr){
    $this->arr = $arr;
  }
  function addColumn($colName="",$colDir=SRT_ASC,$compareFunc=NULL){
    $idx = $this->_getColIdx($colName);
    if($idx < 0){
      $this->sortDef[] = array();
      $idx = count($this->sortDef)-1;
    }
    $this->sortDef[$idx]["colName"] = $colName;
    $this->sortDef[$idx]["colDir"] = $colDir;
    $this->sortDef[$idx]["compareFunc"] = $compareFunc;
  }
  function removeColumn($colName=""){
    $idx = $this->_getColIdx($colName);
    if($idx >= 0) array_splice($this->sortDef,$idx,1);
  }
  function resetColumns(){
    $this->sortDef = array();
  }
  function &sort(){
    usort($this->arr,array($this,"_compare"));
    return $this->arr;
  }
  function _getColIdx($colName){
    $idx = -1;
    for($i=0;$i<count($this->sortDef);$i++){
      $colDef = $this->sortDef[$i];
      if($colDef["colName"] == $colName) $idx = $i;
    }
    return $idx;
  }
  function _compare($a,$b,$idx = 0){
    if(count($this->sortDef) == 0) return 0;

    $colDef = $this->sortDef[$idx];
    $a_cmp  = $a[$colDef["colName"]];
    $b_cmp  = $b[$colDef["colName"]];

    if(is_null($colDef["compareFunc"])){
      $a_dt = strtotime($a_cmp);
      $b_dt = strtotime($b_cmp);

      if(($a_dt == -1) || ($b_dt == -1) || ($a_dt == false) || ($b_dt == false))
        $ret = $colDef["colDir"]*strnatcasecmp($a_cmp,$b_cmp);
      else{
        $ret = $colDef["colDir"]*(($a_dt > $b_dt)?1:(($a_dt < $b_dt)?-1:0));
      }
    }
    else{
      $code = '$ret = ' . $colDef["compareFunc"] . '("' . $a_cmp . '","' . $b_cmp . '");';
      eval($code);
      $ret = $colDef["colDir"]*$ret;
    }
    if($ret == 0){
      if($idx < (count($this->sortDef)-1))
        return $this->_compare($a,$b,$idx+1);
      else
        return $ret;
    }
    else
      return $ret;
  }
}
?>