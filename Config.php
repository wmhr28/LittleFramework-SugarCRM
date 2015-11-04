<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
 */
/*
 * IDs de Anfitrion y Asesor
 */

/* Datos Para reuniones automaticas */
define('SOLICITUD_AVALUO_NAME', 'Solicitud de Avaluo');
define('CARGO_COORDINADOR_ID', 35);

//echo $_SERVER['HTTP_HOST'];
define('ANFITRION', 3);
define('ASESOR', 1);
/*
 * Tiempos maximos para Reuniones y Tareas
 * @MAX_TIME = 15' - Default
 * @MAX_MEET_TIME = 30' - Default
 */
define('MAX_TIME', 15);
define('MAX_MEET_TIME', 30);
define('MAX_CALL_TIME', 30);
/*
 * Carpeta Actual del CRM
 */
define('CRM_FOLDER', 'sugarcrm');
/*
 * Constante para lista de a�os del presente + ANIO_AUTO_USADO
 */
define('ANIO_AUTO_USADO', 20);
/*
 * Instancia actual de trabajo
 */
//define('INSTANCIA', 'DEV');
define('INSTANCIA', 'PRE_PRD');
//define('INSTANCIA', 'PRD');
/*
 * Contantes y Varibles 
 */
$db_charset = 'utf8';
define('IP_SUGAR', $_SERVER['HTTP_HOST']);
if ($_SERVER['HTTP_HOST'] == '' || $_SERVER['HTTP_HOST'] == '') {
    define('IP_WS', ':8080');
}
if ($_SERVER['HTTP_HOST'] == '') {
    define('IP_WS', ':8080');
}
switch (INSTANCIA) {
    case 'DEV' :
        $db_ip = 'localhost';
        $db_user = '';
        $db_passwd = '';
        $db_name_sugar = '';
        $db_name_baseintermedia = '';
        $db_port = '';
        break;
    case 'PRE_PRD' :
        $db_ip = '';
        $db_user = '';
        $db_passwd = '';
        $db_name_sugar = '';
        $db_name_baseintermedia = '';
        $db_port = '';
        break;
    case 'PRD' :
        $db_ip = '';
        $db_user = '';
        $db_passwd = '';
        $db_name_sugar = '';
        $db_name_baseintermedia = '';
        $db_port = '';    
        break;
}
$signData = array(
            'base_url' => "http://" . IP_SUGAR . "/" . CRM_FOLDER . "/rest/v10/",
            'username' => "",
            'password' => "",
            'client_id' => "",
            'client_secret' => ""
        );
$connections = array(
    'SugarCRM' => "mysql://$db_user:$db_passwd@$db_ip/$db_name_sugar?charset=$db_charset",
    'BaseIntermedia' => "mysql://$db_user:$db_passwd@$db_ip/$db_name_baseintermedia?charset=$db_charset"
);
define('SERVER_URL_WS', '');
$url_drilldownfactura = '';
$url_drilldownfacturacobrar = '';
ActiveRecord\Config::initialize(function ($cfg) use($connections) {
    $cfg->set_model_directory(dirname(__FILE__) . '/Data');
    $cfg->set_connections($connections);
    $cfg->set_default_connection('SugarCRM');
});

ActiveRecord\Connection::$datetime_format = 'Y-m-d H:i:s';
// ActiveRecord\Connection::instance()->set_encoding('utf8');
date_default_timezone_set('UTC');
define("SYS_DATETIME", date("Y-m-d H:i:s"));
define("SYS_DATETIME2", date("d/m/Y H:i:s"));
define("VERSION", 'v2.0');
define("FRAME_NAME", 'LiteFramework - Plus Projects');
define('CRM_PATH', '/var/www/html/' . CRM_FOLDER);
setlocale(LC_MONETARY, 'en_US');
if (!defined('sugarEntry'))
    define('sugarEntry', true);
define('SIGN_DATA_CRM', json_encode($signData));
define('LAST_PCT_DAYS_VALIDS', 1);
define('DB_IP', $db_ip);
define('DB_USER', $db_user);
define('DB_PASSWD', $db_passwd);
define('DB_NAME_SUGAR', $db_name_sugar);
define('DB_NAME_BASEINTERMEDIA', $db_name_baseintermedia);
define('DB_PORT', $db_port);
define('DB_CHARSET', $db_charset);
define('URL_DRILLDOWNFACTURA', $url_drilldownfactura);
define('URL_DRILLDOWNCTASCOBRAR', $url_drilldownfacturacobrar);
define('NAME_CALL_7', 'Llamada Postventa 7 dias');
define('NAME_CALL_21', 'Llamada Postventa 21 dias');
define('PREFIJO_OPP_COMPRA', 'Oportunidad_Compra_');//PREFIJO PARA EL CAMPO NAME DE LAS OPORTUNIDADES DE COMPRA
if (!defined('REL_PATH_FRAME_PP'))
    define('REL_PATH_FRAME_PP', CRM_FOLDER);