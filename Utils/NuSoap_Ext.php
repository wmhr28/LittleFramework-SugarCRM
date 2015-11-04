<?php 
/**
 * Description of Soap_WebServices
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 * @date 10/03/2014
 */
class NuSoap_Ext {

    private $url = "";
    private $wdsl = "";

    function __construct($url, $wdsl) {
        $this->url = $url;
        $this->wdsl = $wdsl;
    }

    function call($metodo, $datos) { 
        $cliente = new nusoap_client('http://crm:Crm2015@'.$this->url, $this->wdsl);
        //$cliente->setCredentials('crm', 'Crm2015', 'basic');
        $result = $cliente->call($metodo, $datos);

        if ($cliente->fault) {
            $respuesta = array('status' => FALSE, 'data' => "Error: $result");
        } else {
            $error = $cliente->getError();
            if ($error) {
                $respuesta = array('status' => FALSE, 'data' => "Error: $error");
            } else {
                $respuesta = array('status' => TRUE, 'data' => $result);
            }
        }
        var_dump($respuesta);
        return $respuesta;
    }

}

?>
