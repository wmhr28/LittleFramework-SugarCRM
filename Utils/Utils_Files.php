<?php
/**
 * Description of Utils_Files
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 * @date 21/03/2014
 */
class Utils_Files {

    static function getExtForMimeType($mimeType) {
        $resp = '';
        switch ($mimeType) {
            case 'image/png':
                $resp = 'png';
                break;
            case 'image/gif':
                $resp = 'gif';
                break;
            case 'image/jpeg':
                $resp = 'jpg';
                break;
        }
        return $resp;
    }

    static function getExtension($path) {
        $mimeType = "";
        if (function_exists("mime_content_type")) {
            $mimeType = mime_content_type($path);
        }
        if (function_exists("finfo_file")) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $path);
        }
        return Utils_Files::getExtForMimeType($mimeType);
    }

    static function getURLImageAndSave($url, $nameImage) {
        $image = file_get_contents($url);
        $pathImage = '';
        $saveImageName = $pathImage . $nameImage;
        $file = fopen($saveImageName, 'w');
        fwrite($file, $image);
        fclose($file);
        $finfo = new finfo();
        $fileInfo = $finfo->file($saveImageName, FILEINFO_MIME);
        $fileInfo = explode(';', $fileInfo);
        $mimeType = $fileInfo[0];
        $charset = $fileInfo[1];
        $mimeType = explode('/', $mimeType);
        $extFile = $mimeType[1];
        rename($saveImageName, $saveImageName . '.' . $extFile);
    }

}

?>
