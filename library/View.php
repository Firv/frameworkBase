<?php
require_once '../../library/View/AbstractView.php';

class View extends AbstractView
{
	const VIEW_EXTENSION = 'phtml';
	const PATH = 'webapp/web/views/';

	public function render($viewName)
	{
		$path = 'views/' . $viewName . '.' . self::VIEW_EXTENSION;
		
		if (file_exists($path) === false) {
			throw new Exception('El archivo de vista ' . $path . ' no existe');
			return false;
		}
		
		ob_start();
		include($path);
		$output = ob_get_contents();
		ob_get_clean();
		return $output;
	}
	
	public function getPath()
	{
		return self::PATH;
	}
}