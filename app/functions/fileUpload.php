<?php
namespace functions; 

class fileUpload
{
	public $target_dir; 
	public $file_max_size; // 50000
	public $message;

	public function index($file)
	{
		$target_file = $this->target_dir . basename($file["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
		$check = getimagesize($file["tmp_name"]);
	    if($check == false) {
	    	$this->message = "Файл изображения является поддельным!";
	        return false;
	    }

		// Check file size
		if ($file["size"] > $this->file_max_size) {
			$this->message = "Ошибка размера файла!";
		    return false;
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$this->message = "Ошибка типа файла!";
		    return false;
		}
		
		$newName = $this->target_dir . md5(time().$file["name"]).".".$imageFileType; 
	    if (move_uploaded_file($file["tmp_name"], $newName)) {
	    	$this->message = "Файл успешно загружен!";
	        return $newName;
	    } else {
	       return false;
	    }
	}
}