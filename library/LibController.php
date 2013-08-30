<?php
abstract class LibController
{
	protected $_view;
	
    protected function _getParam($key, $default = null)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        } elseif (isset($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }
	
	protected function _getParams()
    {
        $return = array();
        if (isset($_GET) && is_array($_GET)) {
            $return += $_GET;
        }
        if (isset($_POST) && is_array($_POST)) {
            $return += $_POST;
        }
        return $return;
    }
	
	protected function _redirect($url, array $options = array())
    {
        if (headers_sent($archivo, $linea)) {
            throw new Exception('No se puede redireccionar debido a que ya se envio una cabecera en '.$archivo.', linea '.$linea.'.');
        }

        // prevenir inyeccion en header
        $url = str_replace(array("\n", "\r"), '', $url);

        // redirect
        header("Location: $url");
        exit();
    }
	
	abstract public function indexAction();
}