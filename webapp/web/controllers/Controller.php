<?php
//librerias externas
//require("../../library/Uploader.class.php"); //libreria para subir y redimensionar imagenes

//librerias propias
require_once '../../library/View.php';
require_once '../../library/LibController.php';

//data access object (DAOs)
//require_once 'models/dao/WebDao.php'; // definicion de acceso a datos y llenado de objeto

//entidades
//require_once 'models/entity/Web.php'; // definicion de entidades de objeto

class Controller extends LibController
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
			case "home":
			case "test":
				return $this->_webTestAction();
				break;
			default:
				return $this->_webTestAction();
		}
	}

	private function _webTestAction()
	{
		$this->_view->setAttribute("textoTituloPagina", 'Hola Mundo');
		return $this->_view->render('holaMundo');
	}

}