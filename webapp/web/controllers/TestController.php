<?php
//librerias externas
//require("../../library/Uploader.class.php"); //libreria para subir y redimensionar imagenes

//librerias propias
require_once '../../library/View.php';
require_once '../../library/TestLibController.php';

//data access object (DAOs)
//require_once 'models/dao/WebDao.php'; // definicion de acceso a datos y llenado de objeto

//entidades
//require_once 'models/entity/Web.php'; // definicion de entidades de objeto

class TestController extends TestLibController
{
	public function __construct()
	{
		$this->_view 				= new View();
		//$this->_modelWeb			= new WebDao(); //para acceso a datos, ya sea base de datos u objeto
	}

	public function indexAction()
	{
		switch($this->_getParam("action"))
		{
			case "test-Home":
			case "test-Test":
				return $this->_webTestAction();
				break;
			case "test-Prueba":
				return $this->_webPruebaAction();
				break;
			case "test-Error":
				return $this->_webErrorAction();
				break;
			default:
			return $this->_webPruebaAction();
		}
	}

	
	private function _webPruebaAction()
	{
		$this->_view->setAttribute("textoTituloPagina", 'Hola Mundo');
		$this->_view->setAttribute("path", $this->_view->getPath());
		return $this->_view->render('test/body');
	}
	
	private function _webTestAction()
	{
		$this->_view->setAttribute("textoTituloPagina", 'Hola Mundo');
		$this->_view->setAttribute("path", $this->_view->getPath());
		return $this->_view->render('holaMundo');
	}

	private function _webErrorAction()
	{
		$this->_view->setAttribute("textoTituloPagina", 'Error 404 - Pagina no encontrada');
		$this->_view->setAttribute("textoError", '<h1>No encontrada<br /><span>:(</span></h1><p>Lo siento, pero la p&aacute;gina que intentas ver, no existe.</p><p>Puede ser producto de:</p><ul><li>una direcci&oacute;n mal escrita</li><li>un enlace fuera de fecha (caducado)</li></ul>');
		$this->_view->setAttribute("path", $this->_view->getPath());
		return $this->_view->renderError('error',404);
	}	
	
}