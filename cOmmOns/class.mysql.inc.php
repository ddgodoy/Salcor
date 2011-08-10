<?php
	class clsMyDB{
		var $Host = '';
		var $User = '';
		var $Pass = '';
		var $Database = '';
		var $linkDB;
		var $Errores = array();

		function clsMyDB($host='localhost', $user='salcoricoxu', $pass='salcoricoxp', $database='salcoricox')
		{
			if (strtolower($_SERVER['SERVER_NAME']) == 'localhost') {
				$user = 'root';
				$pass = '123';
				$database = 'salcor';
			}
			$this->Connect($host, $user, $pass, $database);
		}
		//
		function Connect($host = '', $user = '', $pass = '', $database=''){
			$this->Errores = array();
			$this->Host = $host;
			$this->User = $user;
			$this->Pass = $pass;
			$this->Database = $database;

			if (empty($host)){$host = 'localhost';}
			$this->linkDB = mysql_connect($host, $user, $pass);
			
			if (!$this->linkDB){$this->Errores['Connect'] = 'No pude conectarme a MySQL';}
			if (!empty($database)){$this->SelectDB($database);}
		}
		function selectDB($database){
			$this->Database = '';
			if ($this->linkDB){
				if (mysql_select_db($database, $this->linkDB)){$this->Database = $database;}
				else {$this->Errores['Select_DB'] = 'No pude seleccionar la BD';}
			}
		}
		function isConnected(){
			if (!$this->linkDB){
				$this->Errores['Connect'] = 'No hay una conexi�n disponible'; return FALSE;
			}
			if (empty($this->Database)){
				$this->Errores['Select_DB'] = 'No hay una BD disponible'; return FALSE;
			}
			return TRUE;
		}
		function Query($consulta){
			if (!$this->isConnected()){return FALSE;}
			$this->clearErrores();
			$result = mysql_query($consulta, $this->linkDB);
			
			if (!$result){
				$this->Errores['Query'] = 'No es posible ejecutar la consulta!'; return FALSE;
			} elseif (mysql_num_rows($result) == 0){
				$this->Errores['Query'] = 'La consulta no devolvi� registros!'; return FALSE;
			}
			return $result;
		}
		function Command($command){
			if (!$this->isConnected()){return FALSE;}
			$this->clearErrores();
			$result = mysql_query($command, $this->linkDB);
			
			if (!$result){$this->Errores['Command'] = 'No pude ejecutar el comando.'; return FALSE;}
			return $result;
		}
		function insertId(){
			if (!$this->isConnected()){return FALSE;}
			return mysql_insert_id($this->linkDB);
		}
		function Close(){
			if (!$this->isConnected()){return TRUE;}
			return mysql_close($this->linkDB);
		}
		function affectedRows(){
			if (!$this->isConnected()){return FALSE;}
			return mysql_affected_rows($this->linkDB);
		}
		function clearErrores(){$this->Errores = array();}
		function hasErrores(){
			if (empty($this->Errores)){return FALSE;}
			else {return TRUE;}
		}
		function getErrores(){
			$error = '';
			foreach($this->Errores as $descripcion){$error .= $descripcion.'<br>';}
			return $error;
		}
//---------------------------------------------------------
		function forSave($param){$param = (string) $param;$param = addslashes($param);return $param;}
		function forEdit($param){$param = htmlentities(stripslashes($param)); return $param;}
		function forShow($param){return nl2br($this->forEdit($param));}
		function forHtml($param){$param = stripslashes($param); return nl2br($param);}
//---------------------------------------------------------
		function convertDate($mysqlDate, $format = ''){
			if (empty($format)) {$format = 'd/m/Y';}
			list($year, $month, $day) = explode('-', $mysqlDate);
			
			$year = (integer) $year;
			$month= (integer) $month;
			$day  = (integer) $day;

			if ($year > 1969) {
				$timestamp = strtotime("$month/$day/$year");
				$Fecha = date($format, $timestamp);
			} else {
				if (substr(PHP_OS, 0, 3) == "WIN") {
					$timestamp = strtotime("$month/$day/1999");
					$Fecha = date($format, $timestamp);
					$Fecha = str_replace('1999', $year, $Fecha);
				} else {
					$timestamp = $this->safestrtotime("$month/$day/$year");
					$Fecha = date($format, $timestamp);
				}
			}
			return $Fecha;
		}
//---------------------------------------------------------
		function verifyDate($fecha, $formato = 0, $minYear = '', $maxYear = ''){
			if(empty($minYear)){$minYear = 1900;}
			if(empty($maxYear)){$maxYear = date('Y');}
			preg_match("/^([0-9]{1,2})[\-\/]{1}([0-9]{1,2})[\-\/]{1}([0-9]{4})$/", $fecha, $aFecha);

			if (!empty($aFecha)){
				if (($aFecha[3] > $maxYear) || ($aFecha[3] < $minYear)){return FALSE;}
				if($formato == 0){
					if (!checkdate($aFecha[2], $aFecha[1], $aFecha[3])){return FALSE;}
					$fecha = "$aFecha[3]-$aFecha[2]-$aFecha[1]";
				}
				elseif($formato == 1){
					if (!checkdate($aFecha[1], $aFecha[2], $aFecha[3])){return FALSE;}
					$fecha = "$aFecha[3]-$aFecha[1]-$aFecha[2]";
				} else {return FALSE;}
			} else {return FALSE;}
			return $fecha;
		}
//---------------------------------------------------------
		function safestrtotime($strInput){
		   $yearSkew = '';
		   $iVal = -1;
		   for ($i=1900; $i<=1969; $i++){
		       $strYear = (string)$i;
		       if (!(strpos($strInput, $strYear)===false)){
		           $replYear = $strYear;
		           $yearSkew = 1970 - $i;
		           $strInput = str_replace($strYear, "1970", $strInput);
		       }
		   }
		   $iVal = strtotime($strInput);
		   if ($yearSkew > 0){
		       $numSecs = (60 * 60 * 24 * 365 * $yearSkew);
		       $iVal = $iVal - $numSecs;
		       $numLeapYears = 0;
		       for ($j=$replYear; $j<=1969; $j++){
		           $thisYear = $j;
		           $isLeapYear = false;

		           if (($thisYear % 4) == 0){$isLeapYear = true;}
		           if (($thisYear % 100) == 0){$isLeapYear = false;}
		           if (($thisYear % 1000) == 0){$isLeapYear = true;}
		           if ($isLeapYear == true){$numLeapYears++;}
		       }
		       $iVal = $iVal-(60*60*24*$numLeapYears);
		   }
		   return($iVal);
		}
//---------------------------------------------------------
		function ValidarMail($Email){
			$result = array();
			if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $Email)){
	  			$result[0] = false;
	        	$result[1] = "El email parece incorrecto (revise el @ y los puntos)";
	        	return $result;
			}
			$result[0] = true; $result[1] = "$Email es v�lido.";
	    	return $result;
		}
//---------------------------------------------------------
		function redimensionar($imagen_original, $imagen_thumb, $ruta_destino, $extension, $ancho, $alto){
			$ext = $extension;
			$uploaddir = $ruta_destino;
			$uploadfile = $imagen_original;
			$uploadthumb = $imagen_thumb;

			$picsize = getimagesize($uploaddir.$uploadfile);
			$source_x= $picsize[0];
			$source_y= $picsize[1];
			$wscale  = $source_x / $ancho;
			$hscale  = $source_y / $alto;

			$scale = 1;
		    if (($hscale > 1) || ($wscale > 1)){
		        $scale = ($hscale > $wscale)?$hscale:$wscale;
		    }
		    $dest_x = floor($source_x / $scale);
		    $dest_y = floor($source_y / $scale);

		    $target_id = imagecreatetruecolor($dest_x, $dest_y);
		    $source_id = $ext=='.gif'?imagecreatefromgif($uploaddir.$uploadfile):imagecreatefromjpeg($uploaddir.$uploadfile);		    
			$target_pic= imagecopyresampled($target_id,$source_id,0,0,0,0,$dest_x,$dest_y,$source_x,$source_y);
			imagejpeg($target_id, $uploaddir.$uploadthumb, 90);
		}
	}
?>