<?php
/**
 * Description of SoapClient_Ext
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
 */
class SoapClient_Ext {

    private $url = "";    

    function __construct($url) {
        $this->url = $url; 
    }

    public function call($metodo,$parameter=null) {
        $client = new SoapClient($this->url);        
        try {
            $result = $client->$metodo($parameter);
            $status = TRUE;
        } catch (SoapFault $e) {
            $result['REQUEST']=$client->__getLastRequest();
            $result['REQUEST_HEADER']=$client->__getLastRequestHeaders();
            $result['RESPONSE']=$client->__getLastResponse();
            $result['RESPONSE_HEADER']=$client->__getLastResponseHeaders();
            $result['SOAP_FAULT'] = $e;
            $status = FALSE;
        }
        return array('status' => $status, 'result' => $result);
    }

}