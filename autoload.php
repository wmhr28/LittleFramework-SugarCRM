<?php
/**
 * Description of autoload
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
 */
require_once __DIR__.'/Libraries/Php/php-activerecord/ActiveRecord.php';
include_once __DIR__.'/Config.php';
include_once __DIR__.'/Libraries/Php/simplehtmldom/simple_html_dom.php';
include_once __DIR__.'/Libraries/Php/dompdf/dompdf_config.inc.php';
require_once CRM_PATH.'/vendor/nusoap/nusoap.php';

function autoloader_Utils($class) {
    $ruta = __DIR__."/Utils/$class.php";
    if (file_exists($ruta)) {
        require_once $ruta;
    }
}

function autoloader_Business($class) {
    $ruta = __DIR__."/Business/$class.php";
    if (file_exists($ruta)) {
        require_once $ruta;
    }
}

spl_autoload_register('autoloader_Utils');
spl_autoload_register('autoloader_Business');
