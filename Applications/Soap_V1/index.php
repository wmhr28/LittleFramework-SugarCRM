<?php
/**
 * Description of sugarRepair
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
 */
require_once '../../autoload.php';

$server = new soap_server();
$server->configureWSDL('ws_Casabaca');
$server->register('SendInformationCrm', array('metodo' => 'xsd:string', 'datos' => 'xsd:string'), array('return' => 'xsd:string'));
$server->register('SetInformationCrm', array('metodo' => 'xsd:string', 'datos' => 'xsd:string'), array('return' => 'xsd:string'));
$server->register('Test', array('metodo' => 'xsd:string', 'datos' => 'xsd:string'), array('return' => 'xsd:string'));

function SendInformationCrm($metodo, $datos) {
    $response = serverSOAP::response($metodo, $datos);
    return new soapval('return', 'xsd:string', $response);
}

function SetInformationCrm($metodo, $datos) {
    $response = serverSOAP::response($metodo, $datos);
    return new soapval('return', 'xsd:string', $response);
}

function Test($metodo,$datos){
    $response['metodo'] = $metodo;
    $response['datos'] = $datos;
    return new soapval('return', 'xsd:string', json_encode($response));
}

if (!isset($HTTP_RAW_POST_DATA))
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');

$server->service($HTTP_RAW_POST_DATA);
