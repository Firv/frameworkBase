<?php
	session_start();
	error_reporting(E_ALL | E_STRICT);

	date_default_timezone_set('America/Santiago');

	$className	= "Controller";
	$control	= "";
	
	
	$control	= getControlador(false);
	$className	= $control."Controller";

	$requerido = "controllers/".$control."Controller.php";

	if ( file_exists ( $requerido ) )
	{
		require_once $requerido;
		$controlador = new $className();
	}
	else
	{
		require_once "controllers/Controller.php";
		$controlador = new Controller();
	}

	echo $controlador->indexAction();

	function getControlador($debug = true){
		
		$retorno = "";
	
		$domain = $_SERVER['HTTP_HOST'];
		$url = "http://" . $domain . $_SERVER['REQUEST_URI'];
		if ($debug)
		{
			echo "Domain: ".$domain."<br>";
			echo "URL: ".$url."<br>";
			echo "REQUEST_URI: ".$_SERVER['REQUEST_URI']."<br>";
			echo "QUERY_STRING: ".$_SERVER['QUERY_STRING']."<br>";
			echo "<br><br>";
		}
		
	// magia para tener distintos controladores
		if (isset($_GET["action"]))
		{
			$accion = $_GET["action"];
		}
		elseif (isset($_POST["action"]))
		{
			$accion = $_POST["action"];
		}
		$pos = strpos($accion, "-");
		$largo = strlen($accion);

		if ($largo == $pos+1) $pos = false;
		
		if ($pos === false) //no hay "-" en la accion
		{
			if ($debug) echo "no hay caracter";
			$retorno = "";
		}
		else
		{
			if ($debug) {
				echo "La cadena '-' fue encontrada en la cadena '$accion' de largo '$largo'";
				echo " y existe en la posicion '$pos',";
			}
			$retorno = substr($accion,0,$pos);
		}
		
		$largo = strlen($retorno);
		
		if ($debug) {
			echo "<br><br>";
			echo "el control es [$retorno]";
			echo "<br><br>";
			echo "largo de '$retorno' es ".$largo;
		}
		
		if ($largo > 0)
		{
			$retorno = ucfirst($retorno);
			if ($debug) {
				echo "<br><br>";
				echo "nuevo control es '$retorno'";
			}
		}
		
		return $retorno;
	}