<?php

/**
 * Description of Contactos
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
         
 *         0 ok
 *         1 error en xml
 *         2 error parcial en xml
 *         3 conexion
 *         4 procesado
 */
class Contactos extends SugarCrm {

    static $estados = array(
        'ok',
        'error en xml',
        'error parcial en xml',
        'conexion',
        'procesado'
    );
    static $isTelefono = "/^\d{9}$/";
    static $isCelular = "/^\d{10}$/";
    static $isFecha1 = "/^\d{4}-\d{2}-\d{2}$/";
    /* Listas Desplegables */
    static $provincia_list;
    static $ciudad_list;
    static $parroquia_list;
    static $estado_civil_list;
    static $nacionaliad_list;
    static $tipo_cliente_list;
    static $titulo_list;
    static $tipo_identificacion_list;
    static $relacion_veh_list;
    static $relacion_rep_list;
    static $relacion_serv_list;
    /* Campos requeridos */
    static $campos_requeridos = array(
        'clienteId',
        'tipoClienteId',
        'genero',
        'tipoIdentificacionId',
        'estadoCivilId',
        'tituloId',
        'cedula',
        'nombres',
        'apellidos',
        'nacionalidadId',
        'domProvinciaId',
        'domCiudadId',
        'domParroquiaId',
        'telefono',
        'celular',
        'domCallePrinc',
        'domCalleSec',
        'domNumero',
        'relacionVehId',
        'relacionRepId',
        'relacionServId'
    );

    public static function saveSugarXML($Data) {
        self::$provincia_list = Provincias::getProvincias();
        self::$ciudad_list = Ciudades::getCiudades();
        self::$parroquia_list = Parroquias::getParroquias();
        self::$estado_civil_list = EstadoCivil::getEstadoCivil();
        self::$nacionaliad_list = Nacionalidades::getNacionalidades();
        self::$tipo_cliente_list = TiposCliente::getClienteTipos();
        self::$titulo_list = Titulos::getTitulos();
        self::$tipo_identificacion_list = TiposIdentificacion::getTipos();
        self::$relacion_veh_list = Relaciones::getRelNegocio('VEH');
        self::$relacion_rep_list = Relaciones::getRelNegocio('REP');
        self::$relacion_serv_list = Relaciones::getRelNegocio('SER');

        $ret = array();
        foreach ($Data as $contacto) {
            $detail = '';
            $contacto = get_object_vars($contacto);
            $validacion = self::validarCampos($contacto);
            if ($validacion['status']) {
                $idContacto = DatContact::getIdForCedula(trim($contacto['cedula']));
                if ($idContacto !== '') {
                    $accion = 'PUT';
                    $accionShow = 'ACTUALIZADO';
                } else {
                    $accion = 'POST';
                    $accionShow = 'NUEVO';
                }
                $status = 'ENVIADO';
                $record_arguments = array(
                    'tipo_cliente_c' => $contacto['tipoClienteId'],
                    'id_cliente_c' => substr($contacto['clienteId'], 0, 10),
                    'assigned_user_id' => '1',
                    'nacionalidad_c' => $contacto['nacionalidadId'],
                    'tipo_identificacion_c' => $contacto['tipoIdentificacionId'],
                    'numero_identificacion_c' => $contacto['cedula'],
                    'salutation' => $contacto['tituloId'],
                    'first_name' => substr($contacto['nombres'], 0, 100),
                    'last_name' => substr($contacto['apellidos'], 0, 100),
                    'genero_c' => $contacto['genero'],
                    'permite_correo_c' => $contacto['permiteCorreo'],
                    'permite_sms_c' => $contacto['permiteSms'],
                    'phone_mobile' => $contacto['celular'],
                    'phone_home' => $contacto['telefono'],
                    'phone_work' => $contacto['oficina'],
                    'phone_other' => $contacto['alternativo'],
                    'email1' => $contacto['email'],
                    'provincia_c' => $contacto['domProvinciaId'],
                    'ciudad_c' => $contacto['domCiudadId'] . '_' . $contacto['domProvinciaId'],
                    'parroquia_c' => $contacto['domParroquiaId'] . '_' . $contacto['domCiudadId'] . '_' . $contacto['domProvinciaId'],
                    'sector_c' => substr($contacto['domSector'], 0, 25),
                    'calle_principal_c' => substr($contacto['domCallePrinc'], 0, 80),
                    'calle_secundaria_c' => substr($contacto['domCalleSec'], 0, 40),
                    'no_casa_c' => substr($contacto['domNumero'], 0, 10),
                    'referencia_c' => substr($contacto['domReferencia'], 0, 80),
                    'codigo_postal_c' => substr($contacto['domCodigoPostal'], 0, 6),
                    'lugar_trabajo_c' => substr($contacto['trabLugar'], 0, 60),
                    'cargo_c' => substr($contacto['trabCargo'], 0, 60),
                    'department' => substr($contacto['trabDepart'], 0, 255),
                    'trabajo_contacto_c' => substr($contacto['trabContacto'], 0, 50),
                    'ofi_provincia_c' => $contacto['trabProvinciaId'],
                    'ofi_ciudad_c' => $contacto['trabCiudadId'] . '_' . $contacto['trabProvinciaId'],
                    'ofi_parroquia_c' => $contacto['trabParroquiaId'] . '_' . $contacto['trabCiudadId'] . '_' . $contacto['trabProvinciaId'],
                    'ofi_sector_c' => substr($contacto['trabSector'], 0, 25),
                    'ofi_calle_principal_c' => substr($contacto['trabCallePrinc'], 0, 80),
                    'ofi_calle_secundaria_c' => substr($contacto['trabCalleSec'], 0, 40),
                    'nro_ofi_c' => substr($contacto['trabNumero'], 0, 10),
                    'referencia_ofi_c' => substr($contacto['trabReferencia'], 0, 80),
                    'codigo_postal_ofi_c' => substr($contacto['trabCodigoPostal'], 0, 6),
                    'birthdate' => Utils::trasformarFecha($contacto['fechaNac'], $currentFormat = 'Y/m/d', $newFormat = 'Y-m-d'),
                    'profesion_c' => substr($contacto['profesion'], 0, 40),
                    'estado_civil_c' => $contacto['estadoCivilId'],
                    'creado_por_c' => substr($contacto['creadoPor'], 0, 60),
                    'relacion_veh_c' => $contacto['relacionVehId'],
                    'relacion_rep_c' => $contacto['relacionRepId'],
                    'relacion_serv_c' => $contacto['relacionServId'],
                    'valida_origen_c' => 'ERP'
                );
                /* Dato pendiente: Origen de Creaci�n */
                $this->procesarRegistros($record_arguments, $accion, 'Contacts', $idContacto);
                $detail = array(
                    'accion' => $accionShow,
                    'Cedula' => trim($contacto['cedula'])
                );
            } else {
                $status = 'ERROR';
                $detail = $validacion['detail'];
            }
            $ret[] = array(
                'child_tag' => 'Item',
                'object' => 'Contacto',
                'id' => $contacto['id'],
                'status' => $status,
                'detail' => json_encode($detail),
                'dateTime' => SYS_DATETIME2
            );
        }
        return XML::assocArrayToXML($ret, 'Result');
    }

    public static function validarTramaXML($xmlArray) {
        $camposXML = array(
            'id',
            'tipoClienteId',
            'clienteId',
            'nacionalidadId',
            'tipoIdentificacionId',
            'cedula',
            'tituloId',
            'nombres',
            'apellidos',
            'razonSocial',
            'genero',
            'permiteCorreo',
            'permiteSms',
            'celular',
            'telefono',
            'oficina',
            'alternativo',
            'email',
            'domProvinciaId',
            'domCiudadId',
            'domParroquiaId',
            'domSector',
            'domCallePrinc',
            'domCalleSec',
            'domNumero',
            'domReferencia',
            'domCodigoPostal',
            'trabLugar',
            'trabCargo',
            'trabDepart',
            'trabContacto',
            'trabProvinciaId',
            'trabCiudadId',
            'trabParroquiaId',
            'trabSector',
            'trabCallePrinc',
            'trabCalleSec',
            'trabNumero',
            'trabReferencia',
            'trabCodigoPostal',
            'fechaNac',
            'profesion',
            'estadoCivilId',
            'creadoPor',
            'relacionVehId',
            'relacionRepId',
            'relacionServId'
        );

        foreach ($camposXML as $key) {
            if (!(array_key_exists($key, $xmlArray))) {
                return array(
                    'status' => FALSE,
                    'detail' => "No existe el tag: <$key> en la trama XML"
                );
            }
        }
        return array(
            'status' => TRUE,
            'detail' => "OK trama"
        );
    }

    public static function validarCampos($datos) {

        /* Validacion de campos */
        foreach ($datos as $key => $value) {
            $value = trim($value);
            $len = strlen($value);
            switch ($key) {
                case 'clienteId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    break;
                case 'cedula' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if ($datos['tipoIdentificacionId'] != 'P') {
                        $validaCI = Utils::esCedulaValida($value);
                        if (!$validaCI) return array(
                                'status' => FALSE,
                                'detail' => 'Cedula incorrecta;' . $value
                            );
                    }

                    break;
                case 'fechaNac' :
                    if ($len > 10) return array(
                            'status' => FALSE,
                            'detail' => 'Fecha de nacimiento incorrecto: ' . $value . '. El formato tiene que ser: (YYYY/MM/DD)'
                        );

                    $valFechaNacimiento = Utils::esfechaValida($value, FALSE, '/');
                    if (!$valFechaNacimiento) return array(
                            'status' => FALSE,
                            'detail' => 'Fecha de nacimiento incorrecto: ' . $value . '. El formato tiene que ser: (YYYY/MM/DD)'
                        );

                    break;
                case 'genero' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    break;
                case 'nombres' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    break;
                case 'apellidos' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    break;
                case 'domCallePrinc' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    break;
                case 'domCalleSec' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    break;
                case 'domNumero' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    break;
                case 'email' :
                    if ($len != 0) {
                        if (!(Utils::validarEmail($value))) return array(
                                'status' => FALSE,
                                'detail' => "El campo $key no es valido"
                            );
                    }
                    break;
                case 'permiteCorreo' :
                    if (!($value == 0 or $value == 1)) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key no es valido"
                        );
                    break;
                case 'permiteSms' :
                    if (!($value == 0 or $value == 1)) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key no es valido"
                        );
                    break;
                case 'celular' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(preg_match(self::$isCelular, $value))) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key no es valido"
                        );
                    break;
                case 'telefono' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(preg_match(self::$isTelefono, $value))) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key no es valido"
                        );
                    break;
                case 'oficina' :
                    if ($len != 0) if (!(preg_match(self::$isTelefono, $value))) return array(
                                'status' => FALSE,
                                'detail' => "El campo $key no es valido"
                            );
                    break;
                case 'alternativo' :
                    if ($len != 0) if (!(preg_match(self::$isTelefono, $value) or preg_match(self::$isCelular, $value))) return array(
                                'status' => FALSE,
                                'detail' => "El campo $key no es valido"
                            );
                    break;
                case 'domProvinciaId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value, self::$provincia_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'domCiudadId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value . '_' . $datos['domProvinciaId'], self::$ciudad_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'domParroquiaId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value . '_' . $datos['domCiudadId'] . '_' . $datos['domProvinciaId'], self::$parroquia_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'trabProvinciaId' :
                    if ($len != 0) if (!(array_key_exists($value, self::$provincia_list))) return array(
                                'status' => FALSE,
                                'detail' => "El $key no existe"
                            );
                    break;
                case 'trabCiudadId' :
                    if ($len != 0) if (!(array_key_exists($value . '_' . $datos['trabProvinciaId'], self::$ciudad_list))) return array(
                                'status' => FALSE,
                                'detail' => "El $key no existe"
                            );
                    break;
                case 'trabParroquiaId' :
                    if ($len != 0) if (!(array_key_exists($value . '_' . $datos['trabCiudadId'] . '_' . $datos['trabProvinciaId'], self::$parroquia_list))) return array(
                                'status' => FALSE,
                                'detail' => "El $key no existe"
                            );
                    break;
                case 'estadoCivilId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value, self::$estado_civil_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'nacionalidadId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value, self::$nacionaliad_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'tituloId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value, self::$titulo_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'tipoClienteId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value, self::$tipo_cliente_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'tipoIdentificacionId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value, self::$tipo_identificacion_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'relacionVehId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value, self::$relacion_veh_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'relacionRepId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value, self::$relacion_rep_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                case 'relacionServId' :
                    if ($len == 0) return array(
                            'status' => FALSE,
                            'detail' => "El campo $key es requerido"
                        );
                    if (!(array_key_exists($value, self::$relacion_serv_list))) return array(
                            'status' => FALSE,
                            'detail' => "El $key no existe"
                        );
                    break;
                default :

                    break;
            }
        }
        return array(
            'status' => TRUE,
            'detail' => ''
        );
    }

    public static function getContactsXML($datos) {
        $xmlBody = '';
        foreach ($datos as $data) {
            $xmlBody .= "<contactosCrm>
                        <id>" . @$data[0] . "</id>
                        <noCia>01</noCia>
                        <tipoClienteId>" . @$data[1] . "</tipoClienteId>
                        <clienteId>" . @$data[2] . "</clienteId>
                        <nacionalidadId>" . @$data[3] . "</nacionalidadId>
                        <tipoIdentificacionId>" . @$data[4] . "</tipoIdentificacionId>
                        <cedula>" . @$data[5] . "</cedula>
                        <tituloId>" . @$data[6] . "</tituloId>
                        <nombres>" . @$data[7] . "</nombres>
                        <apellidos>" . @$data[8] . "</apellidos>
                        <razonSocial>" . @$data[9] . "</razonSocial>
                        <genero>" . @$data[10] . "</genero>
                        <permiteCorreo>" . @$data[11] . "</permiteCorreo>
                        <permiteSms>" . @$data[12] . "</permiteSms>
                        <celular>" . @$data[13] . "</celular>
                        <telefono>" . @$data[14] . "</telefono>
                        <oficina>" . @$data[15] . "</oficina>
                        <alternativo>" . @$data[16] . "</alternativo>
                        <email>" . @$data[17] . "</email>
                        <domProvinciaId>" . @$data[18] . "</domProvinciaId>
                        <domCiudadId>" . @$data[19] . "</domCiudadId>
                        <domParroquiaId>" . @$data[20] . "</domParroquiaId>
                        <domSector>" . @$data[21] . "</domSector>
                        <domCallePrinc>" . @$data[22] . "</domCallePrinc>
                        <domCalleSec>" . @$data[23] . "</domCalleSec>
                        <domNumero>" . @$data[24] . "</domNumero>
                        <domReferencia>" . @$data[25] . "</domReferencia>
                        <domCodigoPostal>" . @$data[26] . "</domCodigoPostal>
                        <trabLugar>" . @$data[27] . "</trabLugar>
                        <trabCargo>" . @$data[28] . "</trabCargo>
                        <trabDepart>" . @$data[29] . "</trabDepart>
                        <trabContacto>" . @$data[30] . "</trabContacto>
                        <trabProvinciaId>" . @$data[31] . "</trabProvinciaId>
                        <trabCiudadId>" . @$data[32] . "</trabCiudadId>
                        <trabParroquiaId>" . @$data[33] . "</trabParroquiaId>
                        <trabSector>" . @$data[34] . "</trabSector>
                        <trabCallePrinc>" . @$data[35] . "</trabCallePrinc>
                        <trabCalleSec>" . @$data[36] . "</trabCalleSec>
                        <trabNumero>" . @$data[37] . "</trabNumero>
                        <trabReferencia>" . @$data[38] . "</trabReferencia>
                        <trabCodigoPostal>" . @$data[39] . "</trabCodigoPostal>";
            if (!empty($data[40])) $xmlBody .= "<fechaNac>" . @$data[40] . "</fechaNac>";
            $xmlBody .= "<profesion>" . @$data[41] . "</profesion>
                        <estadoCivilId>" . @$data[42] . "</estadoCivilId>
                        <creadoPor>" . @$data[43] . "</creadoPor>
                        <fechaHoraMod>" . @$data[44] . "</fechaHoraMod>
                        <relacionVehId>" . @$data[45] . "</relacionVehId>
                        <relacionRepId>" . @$data[46] . "</relacionRepId>
                        <relacionServId>" . @$data[47] . "</relacionServId>
                    </contactosCrm>";
        }
        $xml = "<contactosCrmList>$xmlBody</contactosCrmList>";

        return $xml;
    }

    public static function procesarEnvios($xml, $fuente) {
        $conn = clienteCasabaca::testConection();
        $estado = 0;
        $int_status = false;
        if ($conn) {
            $result = clienteCasabaca::sendDataXML($xml);
            if (!(is_array($result))) {
                $x = $result;
                $result = simplexml_load_string($result);
                foreach ($result as $item) {
                    $estado = 0;
                    $itemArray = get_object_vars($item);
                    if ($itemArray['status'] == 'ERROR') {
                        /* SE PRODUCE UN ERROR AL PROCESAR EL XML ERROR DE PARSEO */
                        $estado = 2;
                        if ($fuente == 'SugarCRM') Clientes::saveClientesBaseInter(simplexml_load_string($xml));
                    } else {
                        /* SE INTEGRO CORRECTAMENTE EN CASABACA */
                        $int_status = true;
                        if ($fuente == 'API') Clientes::updateIntFiels($itemArray['id'], 1, 'Integrado', SYS_DATETIME);
                    }
                    LogIntegracion::insertLog($xml, print_r($itemArray, true), $estado, $fuente . '-a');
                }
            } else {
                /* NO INTEGRO POR ERRORES EN WS CON XML (RETORNA JSON) */
                $estado = 1;
                if ($fuente == 'SugarCRM') Clientes::saveClientesBaseInter(simplexml_load_string($xml));
                LogIntegracion::insertLog($xml, print_r($result, true), $estado, $fuente . '-b');
            }
        } else {
            /* NO SE TIENE CONEXION AL WS */
            $estado = 3;
            Clientes::saveClientesBaseInter(simplexml_load_string($xml));
            LogIntegracion::insertLog($xml, "Conexion:FALSE", $estado, $fuente . '-c');
        }

        return array(
            'status' => $int_status,
            'idRegistro' => ''
        );
    }

    public static function getFieldsForCedula($cedula, $campos) {
        $getEmail = false;
        $isNew = false;
        if (in_array('email', $campos)) {
            $ind = array_search('email', $campos);
            unset($campos[$ind]);
            $getEmail = TRUE;
        }
        if (!in_array('id', $campos)) {
            $campos[] = 'id';
        }
        if (!in_array('tipo_contacto_c', $campos)) {
            $campos[] = 'tipo_contacto_c';
        }
        $resp = '';
        $Prospecto = DatProspects::getForCedula($cedula, $campos);
        if (!empty($Prospecto)) {
            foreach ($campos as $attr) {
                $resp[$attr] = empty($Prospecto->$attr) ? '' : Utils::encodeHtml($Prospecto->$attr);
            }
            if ($getEmail) {
                $resp['email'] = DatEmail::getEmailForId($Prospecto->id);
            }
            $resp['tipo_contacto_c'] = '1';
            $resp['result'] = true;
            $isNew = true;
        } else {
            $Contacto = DatContact::getForCedula($cedula, $campos);
            if (!empty($Contacto)) {
                foreach ($campos as $attr) {
                    $resp[$attr] = empty($Contacto->$attr) ? '' : Utils::encodeHtml($Contacto->$attr);
                }
                if ($getEmail) {
                    $resp['email'] = DatEmail::getEmailForId($Contacto->id);
                }
                $resp['tipo_contacto_c'] = '2';
                $resp['result'] = true;
                $isNew = true;
            }
        }

        if ($isNew) {
            $PCT = new PCT();
            $pctData = $PCT->getDatosLastPCTByCedula($cedula);
            return array_merge($resp, $pctData);
        }
        return $resp;
    }

}
