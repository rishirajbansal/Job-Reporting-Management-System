<?php

require_once(dirname(__FILE__) . "/../../utils/func.global.php");

$ds = DIRECTORY_SEPARATOR; 
$storeFolder = '..' . $ds . 'tempuploads';
$storeFolder_thumb = '..' . $ds . 'tempuploads' . $ds . 'thumbs';

$imagePath = substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'admin')) . 'tempuploads';
$imagePathThumb = $imagePath . $ds . 'thumbs';

removeImageFiles($storeFolder, $storeFolder_thumb);

 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['logo']['tmp_name'];
      
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
     
    $targetFile =  $targetPath. $_FILES['logo']['name'];
 
    move_uploaded_file($tempFile,$targetFile);
    
    createThumbs($imagePath, $imagePathThumb, 200);
     
}
?>
