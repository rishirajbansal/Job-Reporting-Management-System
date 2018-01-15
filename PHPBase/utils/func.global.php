<?php

/* Licensed To: ThoughtExecution & 9sistemes
* Authored By: Rishi Raj Bansal
* Developed in: Jul-Aug-Sep 2016
 * ===========================================================================
 * This is FULLY owned and COPYRIGHTED by ThoughtExecution
 * This code may NOT be RESOLD or REDISTRUBUTED under any circumstances, and is only to be used with this application
 * Using the code from this application in another application is strictly PROHIBITED and not PERMISSIBLE
 * ===========================================================================
*/

/**
 * set document type
 * @param string $type type of document
 */
function set_content_type($type = 'application/json') {
    header('Content-Type: '.$type);
}

/**
 * Read CSV from URL or File
 * @param  string $filename  Filename
 * @param  string $delimiter Delimiter
 * @return array            [description]
 */
function read_csv($filename, $delimiter = ",") {
    $file_data = array();
    $handle = @fopen($filename, "r") or false;
    if ($handle !== FALSE) {
        while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
            $file_data[] = $data;
        }
        fclose($handle);
    }
    return $file_data;
}

/**
 * Print Log to the page
 * @param  mixed  $var    Mixed Input
 * @param  boolean $pre    Append <pre> tag
 * @param  boolean $return Return Output
 * @return string/void     Dependent on the $return input
 */
function plog($var, $pre=true, $return=false) {
    $info = print_r($var, true);
    $result = $pre ? "<pre>$info</pre>" : $info;
    if ($return) return $result;
    else echo $result;
}

/**
 * Log to file
 * @param  string $log Log
 * @return void
 */
function elog($log, $fn = "debug.log") {
    $fp = fopen($fn, "a");
    fputs($fp, "[".date("d-m-Y h:i:s")."][Log] $log\r\n");
    fclose($fp); 
}


function removeImageFiles($storeFolder, $storeFolder_thumb){
    $ds = DIRECTORY_SEPARATOR; 
    
    //Remove all the unused files from temp upload folder
    if ($handle = opendir($storeFolder)){
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if (!is_dir($storeFolder. $ds .$entry)){
                    unlink($storeFolder. $ds .$entry);
                }
            }
        }
        closedir($handle);
    }
    if ($handle = opendir($storeFolder_thumb)){
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if (!is_dir($storeFolder_thumb. $ds .$entry)){
                    unlink($storeFolder_thumb. $ds .$entry);
                }
            }
        }
        closedir($handle);
    }
}

function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth )  {

    $dir = opendir( $pathToImages );

    while (false !== ($fname = readdir( $dir ))) {
        
        if ($fname != "." && $fname != ".." && $fname != "thumbs" ) {
        }
        else{
            continue;
        }
        
        $info = pathinfo($pathToImages . $fname);

        $img = '';
        switch($info['extension']){
            case 'jpg':
                $img = imagecreatefromjpeg( "{$pathToImages}\\{$fname}" );
                break;
            case 'jpeg':
                $img = imagecreatefromjpeg( "{$pathToImages}\\{$fname}" );
                break;
            case 'png':
                $img = imagecreatefrompng( "{$pathToImages}\\{$fname}" );
                break;

            case 'gif':
                $img = imagecreatefromgif( "{$pathToImages}\\{$fname}" );
                break;
            default:
                $img = imagecreatefromjpeg( "{$pathToImages}\\{$fname}" );
        }
        
        $width = imagesx( $img );
        $height = imagesy( $img );

        // calculate thumbnail size
        $new_width = $thumbWidth;
        $new_height = floor( $height * ( $thumbWidth / $width ) );

        // create a new temporary image
        $tmp_img = imagecreatetruecolor( $new_width, $new_height );

        // copy and resize old image into new image 
        imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
        $tname = 'thumb.jpg';
        imagejpeg( $tmp_img, "{$pathToThumbs}\\{$tname}",100 );
        /*switch($info['extension']){
            case 'jpg' || 'jpeg':
                imagejpeg( $tmp_img, "{$pathToThumbs}\\{$tname}",100 );
                break;
            case 'png':
                imagepng( $tmp_img, "{$pathToThumbs}\\{$tname}",100 );
                break;

            case 'gif':
                imagegif( $tmp_img, "{$pathToThumbs}\\{$tname}",100 );
                break;
            default:
                imagejpeg( $tmp_img, "{$pathToThumbs}\\{$tname}",100 );
        }*/

    }

    closedir( $dir );
}

?>