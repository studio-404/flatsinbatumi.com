<?php 
use Intervention\Image\ImageManager;

class Image2 extends Controller
{
	public function __construct()
	{
		if(!isset($_GET["f"])){ die(); }
		if(!isset($_GET["w"])){ die(); }
		if(!isset($_GET["h"])){ die(); }
	} 

	public function loadimage()
	{
		header('Content-Type: image/jpeg');

		require_once 'app/functions/request.php';
		
		if(!functions\request::index("GET","fromAdmin")){

			$f = base64_decode(urldecode(functions\request::index("GET","f")));
			$w = base64_decode(urldecode(functions\request::index("GET","w")));
			$h = base64_decode(urldecode(functions\request::index("GET","h")));
		}else{
			$f = functions\request::index("GET","f");
			$w = functions\request::index("GET","w");
			$h = functions\request::index("GET","h");
		}
		$grey = (functions\request::index("GET","grey")) ? functions\request::index("GET","grey") : false;

		$filename = explode(Config::WEBSITE, $f);
		if(!$filename){ exit(); }

		$ext = explode(".", $filename[1]);
		$end = strtolower(end($ext));


		if(isset($filename[1]) && file_exists($filename[1]) && ($end=="jpg" || $end=="png" || $end=="gif" || $end=="jpeg")){
			$fileSize = filesize($filename[1]);
			
			$resizeDir = "public/_temporaty/";
			$resizeFileName = $fileSize. "-" . $w . "-" . $h . "-". $grey . "-" . str_replace(array("/", " "), "-", $filename[1]);
			$resizePath = $resizeDir . $resizeFileName;
			
			if(!file_exists($resizePath)){		
				$manager = new ImageManager(array('driver' => 'gd'));		
				if($grey){
					$manager->make($filename[1])->fit($w, $h)->greyscale()->encode('jpg', 60)->save($resizePath);
				}else{
					$manager->make($filename[1])->fit($w, $h)->encode('jpg', 60)->save($resizePath);
				}
			}			
			//echo file_get_contents($resizePath);
			// readfile($resizePath);

			header("Location: http://flatsinbatumi.com/" . $resizePath);

		}else{
			die();
		}
	}

}