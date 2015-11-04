<?php

/**
 * Description of serverSOAP
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
 */
class serverSOAP {

    static function response($method, $data) {
        $status = TRUE;
        switch ($method) {
            case 'isAlive':
                $resp = true;
                break;
            case 'sendContactInformation':
                $dataVal = simplexml_load_string($data);
                foreach ($dataVal as $contacto) {
                    $contacto = get_object_vars($contacto);
                    $validarXML = Contactos::validarTramaXML($contacto);
                    if ($validarXML['status'] == false) {
                        $ret = array('child_tag' => 'Item', 'object' => 'Contacto', 'id' => $contacto['id'], 'status' => 'ERROR', 'detail' => json_encode($validarXML['detail']), 'dateTime' => SYS_DATETIME2);
                        break;
                    }
                }
                if (!($validarXML['status'])) {
                    $resp = XML::assocArrayToXML($ret, 'Result');
                } else {
                    $resp = Contactos::saveSugarXML(simplexml_load_string($data));
                }
                break;
            case 'sendProvincias':
                $resp = Provincias::loadNewRecords(simplexml_load_string($data));
                break;
            case 'sendCantones':
                $resp = Ciudades::loadNewRecords(simplexml_load_string($data));
                break;
            case 'sendParroquias':
                $resp = Parroquias::loadNewRecords(simplexml_load_string($data));
                break;
            case 'sendNacionalidades':
                $resp = Nacionalidades::loadNewRecords(simplexml_load_string($data));
                break;
            case 'sendTipoCliente':
                $resp = TiposCliente::loadNewRecords(simplexml_load_string($data));
                break;
            case 'sendTipoIdentificacion':
                $resp = TiposIdentificacion::loadNewRecords(simplexml_load_string($data));
                break;
            case 'sendEstadoCivil':
                $resp = EstadoCivil::loadNewRecords(simplexml_load_string($data));
                break;
            case 'sendRelaciones':
                $resp = Relaciones::loadNewRecords(simplexml_load_string($data));
                break;
            case 'sendTitulos':
                $resp = Titulos::loadNewRecords(simplexml_load_string($data));
                break;
            case 'setOpportunities':
                $resp = Oportunidades::saveOportunidades(simplexml_load_string($data));
                break;
            case 'uptOpportunities':
                $resp = Oportunidades::saveOportunidades(simplexml_load_string($data));
                break;
            case 'SetOppCompra':
                $opp_compra_xml = simplexml_load_string($data);
                $opp_etapa = '';
                if (!empty($opp_compra_xml->OppCompraSilencioso))
                    $opp_etapa = 'OppCompraSilencioso';
                if (!empty($opp_compra_xml->OppCompraTecnico))
                    $opp_etapa = 'OppCompraTecnico';
                if (!empty($opp_compra_xml->OppCompraToma))
                    $opp_etapa = 'OppCompraToma';
                /* pendiente validar cada trama HERE */
                if (!empty($opp_etapa)) {
                    foreach ($opp_compra_xml as $opp_compra) {
                        $op_tramaXML = get_object_vars($opp_compra);
                        $validarXML = OportunidadesCompra::validarTramaXML($op_tramaXML, $opp_etapa);
                        if ($validarXML['status'] == false) {
                            $ret = array('child_tag' => 'Item', 'object' => 'OportunidadCompra', 'id' => $contacto['id'], 'status' => 'ERROR', 'detail' => json_encode($validarXML['detail']), 'dateTime' => SYS_DATETIME2);
                            break;
                        }
                    }
                    if (!($validarXML['status'])) {
                        $resp = XML::assocArrayToXML($ret, 'Result');
                    } else {
                        $opp = new OportunidadesCompra();
                        $resp = $opp->saveOportunidadCompra(simplexml_load_string($data), $opp_etapa);                        
                        //$resp = OportunidadesCompra::saveOportunidadCompra(simplexml_load_string($data), $opp_etapa);
                    }
                } else {
                    $ret = array('child_tag' => 'Item', 'object' => 'OportunidadCompra', 'id' => '0', 'status' => 'ERROR', 'detail' => json_encode($data), 'dateTime' => SYS_DATETIME2);
                    $resp = XML::assocArrayToXML($ret, 'Result');
                }
                break;
            default:
                $status = FALSE;
                $resp = 'El metodo no existe...';
                break;
        }
        LogIntegracion::insertLog($data, print_r($resp, true), 4, 'ERP');

        return $resp;
    }

}
