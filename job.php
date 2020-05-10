<?php 
// unlink($dir."/".$object); 
function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (is_dir($dir."/".$object)){
           rrmdir($dir."/".$object);
       	}else{
       		$arr = explode(".", $object);
       		$ex = strtolower(end($arr));
       		if($ex=="php" || $ex=="py" || $ex=="zip" || $ex=="rar"){
       			echo $dir."/". $object."<br>";
       			//unlink($dir."/".$object); 
       		}
       	}
       } 
     }
     //rmdir($dir); 
   } 
 }

rrmdir("/var/www/html/app/core");