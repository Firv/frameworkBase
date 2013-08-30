<?php 
/**
 * Uploader es una clase para transferir o subir archivos, ya sean imagenes u otro tipo.
 * Tambien se incluyen metodos exclusivos para redimensionar o cortar imagenes.
 * Para PHP 5
 * @author Fabián Riveros
 * @link http://www.inunorivneo.cl
 * @version 1.0
 * 
 */
class Uploader{
	private $source;			// Array, el objeto archivo como $_FILES['element']; o String, ubicacion (url) del archivo.
	private $destDir;			// String, directorio destino del archivo.
	private $resizeDir;			// String, directorio para imagenes redimensionadas.
	private $cropDir;			// String, directorio para imagenes cortadas.
	private $info = '';			// String, almacena estado exitoso del archivo o imagen subida.
	private $errorMsg = '';		// String, almacena estado erroneo del archivo o imagen subida.
	private $newWidth;			// Integer, nuevo ancho para imagenes cortadas o redimensionadas.
	private $newHeight;			// Integer, nuevo alto para imagenes cortadas o redimensionadas.
	private $width;				// Integer, nuevo ancho para imagenes cortadas Y redimensionadas.
	private $height;			// Integer, nuevo alto para imagenes cortadas Y redimensionadas.
	private $top = 0;			// Integer, posicion de ARRIBA para imagenes cortadas.
	private $left = 0;			// Integer, posicion de IZQUIERDA para imagenes cortadas.
	private $quality = 60;		// Integer, calidad JPG (entre 0 - 100 %). Usado para imagenes cortadas o redimensionadas.
	private $autoName = true;	// Boolean, setea si aplicar autonombre al archivo subido para prevenir duplicado de archivos.
	private $fileName;			// String, archiva el nuevo nombre generado para un archivo con nombre automatico.
	
	public function getFileName() {
		return $this->fileName;
	}
	
	public function setFileName($fileName) {
		$this->fileName = $fileName;
	}

	public function getAutoName() {
		return $this->autoName;
	}
	
	public function setAutoName($autoName) {
		$this->autoName = $autoName;
	}

	public function getQuality() {
		return $this->quality;
	}
	
	public function setQuality($quality) {
		$this->quality = $quality;
	}

	public function getLeft() {
		return $this->left;
	}
	
	public function setLeft($left) {
		$this->left = $left;
	}

	public function getTop() {
		return $this->top;
	}
	
	public function setTop($top) {
		$this->top = $top;
	}

	public function getNewHeight() {
		return $this->newHeight;
	}
	
	public function setNewHeight($newHeight) {
		$this->newHeight = $newHeight;
	}

	public function getHeight() {
		return $this->height;
	}
	
	public function setHeight($height) {
		$this->height = $height;
	}

	public function getWidth() {
		return $this->width;
	}
	
	public function setWidth($width) {
		$this->width = $width;
	}
	
	public function getNewWidth() {
		return $this->newWidth;
	}
	
	public function setNewWidth($newWidth) {
		$this->newWidth = $newWidth;
	}

	public function getErrorMsg() {
		return $this->errorMsg;
	}
	
	public function setErrorMsg($errorMsg) {
		$this->errorMsg = $errorMsg;
	}

	public function getInfo() {
		return $this->info;
	}
	
	public function setInfo($info) {
		$this->info = $info;
	}

	public function getCropDir() {
		return $this->cropDir;
	}
	
	public function setCropDir($cropDir) {
		$this->cropDir = $cropDir;
	}

	public function getResizeDir() {
		return $this->resizeDir;
	}
	
	public function setResizeDir($resizeDir) {
		$this->resizeDir = $resizeDir;
	}

	public function getDestDir() {
		return $this->destDir;
	}
	
	public function setDestDir($destDir) {
		$this->destDir = $destDir;
	}
	
	public function getSource() {
		return $this->source;
	}
	
	public function setSource($source) {
		$this->source = $source;
	}
	
	public function getFileUploaded(){
		return $this->destDir . $this->fileName;
	}

	public function getFileModified(){
		return $this->fileName;
	}
	
	public function __construct(){
		//nada aun
	}

	/**
	 * Sube el archivo al servidor
	 * @param Array $_FILES[]
	 */
	public function upload($source){
		if($source != ""){
			$this->source = $source;
		}
		if(is_array($this->source)){
			if($this->fileExists()){
				return false;
			}
			return $this->copyFile();
		}
		else {
			if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $this->source)){
				$this->copyExternalFile();
			} else {
				return $this->source;
			}
		}
	}
	
	/**
	 * Copia el archivo subido a su directorio destino.
	 */
	private function copyFile(){
		if(!$this->isWritable()){
			$this->errorMsg .= 'Error, el directorio: ('.$this->destDir.') no es escribible. Arregla los permisos para habilitar la subida.';
			return false;
		}
		if($this->autoName==true){
			$ext = explode('.',$this->source['name']);
			$this->fileName = date("U").'.'. $ext[(sizeof($ext)-1)];
		}
		else {
			$this->fileName = $this->source['name'];
		}
		if(copy($this->source['tmp_name'],$this->destDir . $this->fileName)){
			// Done.
			$this->info .= 'El archivo fue subido correctamente.';
		}
		else {
			$this->errorMsg .= 'Error, el archivo no se subio correctamente debido a un error interno. Intentalo nuevamente, si te sale este error denuevo, contacta al administrador del sitio.';
		}
	}
	
	/**
	 * Copia un archivo externo desde url.
	 * @return boolean
	 */
	private function copyExternalFile(){
		$file_name = date("U").basename($this->source);
		if(copy($this->source,$this->destDir . $file_name)){
			// Done.
			$this->info .= 'El archivo fue subido correctamente';
			return $file_name;
		}
		else {
			$this->errorMsg .= 'Error, el archivo no se subio correctamente debido a un error interno. Intentalo nuevamente, si te sale este error denuevo, contacta al administrador del sitio.';
			return false;
		}
	}
	/**
	 * Verifica si el archivo fue subido.
	 * @return boolean
	 */
	private function uploaded(){
		if($this->source['tmp_name']=="" || $this->source['error'] !=0){
			$this->errorMsg .= 'Error, el archivo no se subio correctamente, por favor intenta denuevo.';
			return false;
		}
		else 
			return true;
	}
	/**
	 * Prepara el directorio.
	 */
	private function preDir(){
		if($this->destDir!="" && substr($this->destDir, -1,1) != "/"){
			$this->destDir = $this->destDir . '/';
		}
		if($this->resizeDir!="" && substr($this->resizeDir, -1,1) != "/"){
			$this->destDir = $this->resizeDir . '/';
		}
		if($this->cropDir!="" && substr($this->cropDir, -1,1) != "/"){
			$this->destDir = $this->cropDir . '/';
		}
	}
	/**
	 * Verifica si el direcorio es escribible o no.
	 * @return boolean
	 */
	private function isWritable(){
		$err = false;
		if(!is_writeable($this->destDir) && $this->destDir!=""){
			$this->errorMsg .= 'Error, el directorio ('.$this->destDir.') no es escribible. Archivo no puede ser subido';
			$err = true;
		}
		if(!is_writeable($this->resizeDir) && $this->resizeDir!=""){
			$this->errorMsg .= 'Error, el directorio ('.$this->resizeDir.') no es escribible. Archivo no puede ser redimensioando';
			$err = true;
		}
		if(!is_writeable($this->cropDir) && $this->cropDir!=""){
			$this->errorMsg .= 'Error, el directorio ('.$this->cropDir.') no es escribible. Archivo no puede ser cortado';
			$err = true;
		}
		if($err == true){
			return false;
		}
		else {
			return true;
		}
	}
	/**
	 * Verifica si el archivo ya existe en el servidor.
	 * @return boolean
	 */
	private function fileExists(){
		$this->preDir();
		if(file_exists($this->destDir.$this->source)){
			$this->errorMsg .= 'Error, no se pudo subir el archivo porque ya existe.';
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Elimina un archivo desde la ruta indicada.
	 * @return String fileName o False en error
	 */
	public function delete($file=''){
		if($file!=""){ $this->source = $file;}
		unlink($this->source);
	}	
	/**
	 * Corta una imagen.
	 * @return String fileName o False en error
	 */
	public function crop($file='',$width='',$height='',$top='',$left=''){
		if($file!=""){ $this->source = $file;}
		if ($width != '') $this->newWidth = $width;
		if ($height != '') $this->newHeight = $height;
		if ($top != '') $this->top = $top;
		if ($left != '') $this->left = $left;
		return $this->_resize_crop(true,"");
	}
	/**
	 * Redimensiona una imagen.
	 * @return String fileName o False en error
	 */
	public function resize($file='',$width='',$height='',$fixed='width'){
		if($file!=""){ $this->source = $file; }
		if($width != '') $this->newWidth = $width;
		if($height != '') $this->newHeight = $height;
		return $this->_resize_crop(false,$fixed);
	}
	/**
	 * Corta una imagen.
	 * @return String fileName o False en error
	 */
	public function cropResize($file='',$width='',$height='',$top='',$left='',$newWidth='',$newHeight=''){
		if($file!=""){ $this->source = $file;}
		if ($width != '') $this->width = $width;
		if ($height != '') $this->height = $height;
		if ($newWidth != '') $this->newWidth = $newWidth;
		if ($newHeight != '') $this->newHeight = $newHeight;
		if ($top != '') $this->top = $top;
		if ($left != '') $this->left = $left;
		return $this->_resize_crop(true,"crop");
	}
	/**
	 * Retorna el archivo en su directorio temporal.
	 * Si la fuente es la ubicacion del directorio, retorna su ubicacion.
	 * @return String Temp File Location
	 */
	private function getTemp(){
		if(is_array($this->source)){
			return $this->source['tmp_name'];
		}
		else {
			return $this->source;
		}
	}
	/**
	 * Retorna la ubicacion del archivo.
	 * Si la fuente es la ubicacion del directorio, retorna su ubicacion.
	 * @return String File Location
	 */
	private function getFile(){
		if(is_array($this->source)){
			return $this->source['name'];
		}
		else {
			return $this->source;
		}
	}
	/**
	 * Redimencion y corta una imagen.
	 * @param boolean $crop
	 * @return String fileName False en error
	 */
	private function _resize_crop ($crop,$fixed) {
		$ext = explode(".",$this->getFile());
		$ext = strtolower(end($ext));
		list($width, $height) = getimagesize($this->getTemp());
		if(!$crop){
			$ratio = $width/$height;
			if($fixed=="width"){
				if ($this->newWidth/$this->newHeight > $ratio) {
					$this->newWidth = $this->newHeight*$ratio;
				}
				else {
					$this->newHeight = $this->newWidth/$ratio;
				}
			} else {
				if ($this->newWidth/$this->newHeight > $ratio) {
					$this->newHeight = $this->newWidth*$ratio;
				}
				else {
					$this->newWidth = $this->newHeight/$ratio;
				}
			}
		}
		$normal  = imagecreatetruecolor($this->newWidth, $this->newHeight);
		if($ext == "jpg") {
			$src = imagecreatefromjpeg($this->getTemp());
		}
		else if($ext == "gif") {
            $src = imagecreatefromgif ($this->getTemp());
		}
		else if($ext == "png") {
            $src = imagecreatefrompng ($this->getTemp());
		}
		if($crop){
			$pre = '.th.jpg'; //$this->newWidth.'x'.$this->newHeight.'_crop_';
			if($fixed=="crop"){
				if(imagecopyresampled($normal, $src, 0, 0, $this->top, $this->left, $this->newWidth, $this->newHeight, $this->width, $this->height)){
					$this->info .= 'Imagen fue redimensionada y grabada correctamente.';
				}			
			}
			else
			{
				if(imagecopy($normal, $src, 0, 0, $this->top, $this->left, $this->newWidth, $this->newHeight)){
					$this->info .= 'Imagen fue cortada y grabada correctamente.';
				}
			}
 			$dir = $this->cropDir;
		}
		else {
			$pre = '.th.'.$ext; //$this->newWidth.'x'.$this->newHeight.'_';
			if(imagecopyresampled($normal, $src, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $width, $height)){
				$this->info .= 'Imagen fue redimensionada y grabada correctamente.';
			}
			$dir = $this->resizeDir;
		}
		
		$this->fileName = $this->getFile() . $pre;
//		se comenta para siempre generar una imagen jpg		
//		if($ext == "jpg" || $ext == "jpeg") {
			imagejpeg($normal, $this->fileName, $this->quality);
//		}
/*
		else if($ext == "gif") {
			imagegif ($normal, $this->fileName);
		}
		else if($ext == "png") {
			imagepng ($normal, $this->fileName,0);
		}
*/		
		imagedestroy($src);
		return $src;
	}
}
?>