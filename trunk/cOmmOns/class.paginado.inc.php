<?php
	/*
	class.PaginadoSimple.inc.php
	Realiza los calculos necesarios para paginar el contenido de una Página Web
	@author Javier O. Fabián <JavierOFabian@hotmail.com>
	@date 2005-01-23 11:50
	*/
	class clsPaginadoMySQL extends clsPaginadoSimple{
		var $sqlFrom;
		var $sql;
		var $oMySQL;
		var $sqlDistinct;
		var $registros;

		function clsPaginadoMySQL ($sql, &$oMySQL, $paginaActual = 0, $registrosAMostrar = 10, $paginasAMostrar = 10, $nombreVariablePaginado = 'page'){
			$this->sqlFrom= stristr($sql, 'from');
			$this->sql    = $sql;
			$this->sql    = $sql;
			$this->oMySQL = &$oMySQL;

			if (stristr($this->sql, "distinct") === FALSE)
				$this->sqlDistinct = FALSE;
			else
				$this->sqlDistinct = TRUE;

			$this->pagActual= $paginaActual;
			$this->regToShow= $registrosAMostrar;
			$this->prmPage  = $nombreVariablePaginado;
			$this->pagToShow= $paginasAMostrar;
		}
		function doPaginar(){
			if ($this->sqlDistinct){
				$result = $this->oMySQL->Query($this->sql);
			} else {
				$result = $this->oMySQL->Query("SELECT COUNT(*) as Cantidad ".$this->sqlFrom);
			}
			if ($result){
				if ($this->sqlDistinct){
					$cantidad = mysql_num_rows($result);
				} else {
					list($cantidad) = mysql_fetch_row($result);
				}
				parent::clsPaginadoSimple($this->pagActual, $cantidad, $this->regToShow, $this->pagToShow, $this->prmPage);
				parent::doPaginar();

				$this->registros = $this->oMySQL->Query($this->sql." LIMIT {$this->regStart}, {$this->regToShow}");
			}
		}
	}
	class clsPaginadoSimple{
		var $regAmount;
		var $regToShow;
		var $regStart;
		var $pagActual;
		var $pagToShow;
		var $pagLast;
		var $pagStart;
		var $pagEnd;
		var $parametros;
		var $links;
		var $linksPaginas;
		var $prmPage = '';

		function clsPaginadoSimple($paginaActual = 0, $cantidadRegistros = 0, $registrosAMostrar = 10, $paginasAMostrar = 10, $nombreVariablePaginado = 'page'){
			$this->setCantidadRegistros($cantidadRegistros);
			$this->setPaginaActual($paginaActual);
			$this->setRegistrosAMostrar($registrosAMostrar);
			$this->setPaginasAMostrar($paginasAMostrar-1);
			$this->prmPage = $nombreVariablePaginado;
			$this->recoverParametros();

			$this->links = array('first'=>'','previous'=>'','last'=>'','next'=>'');
			$this->pagLast = 0;
			$this->pagStart= 0;
			$this->pagEnd  = 0;
			$this->regStart= 0;
		}
		function getPaginaActual(){
			return $this->pagActual;
		}
		function getCantidadRegistros(){
			return $this->regAmount;
		}
		function setPaginasAMostrar($valor){
			$this->pagToShow = (integer) $valor;
		}
		function setRegistrosAMostrar($valor){
			$this->regToShow = (integer) $valor;
		}
		function setCantidadRegistros($valor){
			$this->regAmount = (integer) $valor;
		}
		function setPaginaActual($valor){
			$paginaActual = (integer) $valor;
			if ($paginaActual > 0)
				$this->pagActual = $paginaActual;
			else 
				$this->pagActual = 1;
		}
		function recoverParametros(){
			$this->parametros = '';
			foreach ($_POST as $key => $value){
				if($key != $this->prmPage){$this->parametros .= "$key=$value&";}
			}
			foreach ($_GET as $key => $value){
				if($key != $this->prmPage){$this->parametros .= "$key=$value&";}
			}
		}
		function addParametro($nombre, $valor){
			if (!empty($nombre) && !empty($valor)){
				$this->parametros .= "$nombre=$valor&";
			}
		}
		function removeParametro($nombre){
			if (!empty($nombre)){
				$pos = strpos($this->parametros, $nombre."=");
				if ($pos !== false){
					$pos2 = strpos($this->parametros,"&",$pos);
					$this->parametros = substr_replace($this->parametros, '', $pos, ($pos2 - $pos) + 1);
				}
			}
		}
		function replaceParametro($nombre, $valor){
			if (!empty($nombre) && !empty($valor)){
				$this->removeParametro($nombre);
				$this->addParametro($nombre, $valor);
			}
		}
		function doPaginar(){
			$this->pagLast = ceil($this->regAmount / $this->regToShow);

			if ($this->pagLast > 1){
				if ($this->pagActual > $this->pagLast){
					$this->pagActual = $this->pagLast;
				}
				elseif ($this->pagActual < 1){
					$this->pagActual = 1;
				}
				$this->regStart = ($this->pagActual - 1) * $this->regToShow;

				if ($this->pagActual > 1){
					$this->links['first']   = "?{$this->parametros}{$this->prmPage}=1";
					$this->links['previous']= "?{$this->parametros}{$this->prmPage}=".($this->pagActual - 1);
				}
				if ($this->pagActual < $this->pagLast){
						$this->links['last'] = "?{$this->parametros}{$this->prmPage}={$this->pagLast}";
						$this->links['next'] = "?{$this->parametros}{$this->prmPage}=".($this->pagActual + 1);
				}
				if ($this->pagActual <= $this->pagToShow && $this->pagLast > ($this->pagToShow)){
					$this->pagStart = 1;
					$this->pagEnd = $this->pagToShow + 1;
				}
				elseif ($this->pagLast > ($this->pagToShow)){
					$this->pagStart = $this->pagActual - floor($this->pagToShow/2);
					$this->pagEnd = $this->pagActual + ceil($this->pagToShow/2);
					if ($this->pagEnd > $this->pagLast){
						$this->pagStart -= ($this->pagEnd - $this->pagLast);
						$this->pagEnd = $this->pagLast;
					}
				} else {
					$this->pagStart = 1;
					$this->pagEnd = $this->pagLast;
				}
				for($i=$this->pagStart;$i<=$this->pagEnd;$i++){
					$this->linksPaginas[$i] = "?{$this->parametros}{$this->prmPage}={$i}";
				}
			}
		}
	}
?>