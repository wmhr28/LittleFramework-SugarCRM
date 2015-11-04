<?php
/**
 * Description of Utils
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 */
class Utils {

    static function setTimezone($time, $outFormat = "Y/m/d H:i:s", $defaultZone = "UTC", $newZone = "America/Guayaquil") {
        $date = new DateTime($time, new DateTimeZone($defaultZone));
        $date->setTimezone(new DateTimeZone($newZone));
        $result = $date->format($outFormat);
        return $result;
    }

    static function getArrayList($Data, $trim = TRUE) {
        $resp = array();
        foreach ($Data as $elemento) {
            $resp[trim($elemento->id)] = ($trim) ? trim($elemento->nombre) : $elemento->nombre;
        }
        return $resp;
    }

    static function validarEmail($email) {
        return (filter_var($email, FILTER_VALIDATE_EMAIL) != false) ? TRUE : FALSE;
    }

    static function getYearList() {
        $rango = ANIO_AUTO_USADO;
        $hasta = date('Y');
        $result = '';
        for ($i = 0; $i <= $rango; $i++) {
            $result[$hasta - $i] = $hasta - $i;
        }
        return $result;
    }

    static function esSetyVacio($var) {
        $resp = FALSE;
        if (!isset($var)) {
            $resp = TRUE;
        } else {
            if (empty($var))
                $resp = TRUE;
        }
        return $resp;
    }

    //put your code here
    static function esCedulaValida($numero) {
        if (strlen($numero) == 0) {
            return FALSE;
        }
        $resultado = "";
        $suma = 0;
        $residuo = 0;
        $pri = false;
        $pub = false;
        $nat = false;
        $numeroProvincias = 22;
        $modulo = 11;

        /* Aqui almacenamos los digitos de la cedula en variables. */
        $d1 = substr($numero, 0, 1);
        $d2 = substr($numero, 1, 1);
        $d3 = substr($numero, 2, 1);
        $d4 = substr($numero, 3, 1);
        $d5 = substr($numero, 4, 1);
        $d6 = substr($numero, 5, 1);
        $d7 = substr($numero, 6, 1);
        $d8 = substr($numero, 7, 1);
        $d9 = substr($numero, 8, 1);
        $d10 = substr($numero, 9, 1);

        $p1 = 0;
        $p2 = 0;
        $p3 = 0;
        $p4 = 0;
        $p5 = 0;
        $p6 = 0;
        $p7 = 0;
        $p8 = 0;
        $p9 = 0;

        /* El tercer digito es: */
        /* 9 para sociedades privadas y extranjeros */
        /* 6 para sociedades publicas */
        /* menor que 6 (0,1,2,3,4,5) para personas naturales */

        if ($d3 == 7 || $d3 == 8) {
            $resultado = '0';
        }

        /* Solo para personas naturales (modulo 10) */
        if ($d3 < 6) {
            $nat = true;
            $p1 = $d1 * 2;
            if ($p1 >= 10)
                $p1 -= 9;
            $p2 = $d2 * 1;
            if ($p2 >= 10)
                $p2 -= 9;
            $p3 = $d3 * 2;
            if ($p3 >= 10)
                $p3 -= 9;
            $p4 = $d4 * 1;
            if ($p4 >= 10)
                $p4 -= 9;
            $p5 = $d5 * 2;
            if ($p5 >= 10)
                $p5 -= 9;
            $p6 = $d6 * 1;
            if ($p6 >= 10)
                $p6 -= 9;
            $p7 = $d7 * 2;
            if ($p7 >= 10)
                $p7 -= 9;
            $p8 = $d8 * 1;
            if ($p8 >= 10)
                $p8 -= 9;
            $p9 = $d9 * 2;
            if ($p9 >= 10)
                $p9 -= 9;
            $modulo = 10;
        }

        /* Solo para sociedades publicas (modulo 11) */
        /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
        else {
            if ($d3 == 6) {
                $pub = true;
                $p1 = $d1 * 3;
                $p2 = $d2 * 2;
                $p3 = $d3 * 7;
                $p4 = $d4 * 6;
                $p5 = $d5 * 5;
                $p6 = $d6 * 4;
                $p7 = $d7 * 3;
                $p8 = $d8 * 2;
                $p9 = 0;
            } else {
                /* Solo para entidades privadas (modulo 11) */
                if ($d3 == 9) {
                    $pri = true;
                    $p1 = $d1 * 4;
                    $p2 = $d2 * 3;
                    $p3 = $d3 * 2;
                    $p4 = $d4 * 7;
                    $p5 = $d5 * 6;
                    $p6 = $d6 * 5;
                    $p7 = $d7 * 4;
                    $p8 = $d8 * 3;
                    $p9 = $d9 * 2;
                }
            }
        }
        $suma = $p1 + $p2 + $p3 + $p4 + $p5 + $p6 + $p7 + $p8 + $p9;
        $residuo = $suma % $modulo;

        /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo */
        if ($residuo == 0)
            $digitoVerificador = 0;
        else
            $digitoVerificador = $modulo - $residuo;

        /* ahora comparamos el elemento de la posicion 10 con el dig. ver. */
        if ($pub == true) {
            if ($digitoVerificador != $d9) {
                $resultado = '0';
            }
            /* El ruc de las empresas del sector publico terminan con 0001 */
            if (substr($numero, 9, 4) != '0001') {
                $resultado = '0';
            }
        } else {
            if ($pri == true) {
                if ($digitoVerificador != $d10) {
                    $resultado = '0';
                }
                if (substr($numero, 10, 3) != '001') {
                    $resultado = '0';
                }
            } else {
                if ($nat == true) {
                    if ($digitoVerificador != $d10) {
                        $resultado = '0';
                    }
                    if (strlen($numero) > 10 && substr($numero, 10, 3) != '001') {
                        $resultado = '0';
                    }
                }
            }
        }
        if ($resultado == "") {
            return true;
        } else {
            return false;
        }
    }

    static function encodeHtml($str) {
        return htmlentities($str, 0, 'UTF-8');
    }

    static function decodeHtml($str) {
        return html_entity_decode($str, 0, 'UTF-8');
    }

    static function trasformarFecha($fecha, $currentFormat = 'Y/m/d H:i:s', $newFormat = 'Y-m-d H:i:s') {
        $result = '';
        if (trim($fecha) != '') {
            $currentDate = DateTime::createFromFormat($currentFormat, trim($fecha));
            if ($currentDate) {
                $result = $currentDate->format($newFormat);
            }
        }
        return $result;
    }

    static function esfechaValida($fecha, $ddmmaaaa = true, $separador = '-') {
        $fecha = trim($fecha);
        if (strlen($fecha) == 0) {
            return FALSE;
        }
        $arrayFecha = explode($separador, $fecha);
        if ($ddmmaaaa) {
            list($dia, $mes, $anio) = $arrayFecha;
        } else {
            list($anio, $mes, $dia) = $arrayFecha;
        }
        return checkdate($mes, $dia, $anio);
    }

    static function esEmailValido($valor) {
        if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $valor)) {
            return TRUE;
        }
        return FALSE;
    }

    static function get_ip() {
        return $_SERVER["REMOTE_ADDR"];
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

    static function calcularEdad($fecha) {
        if (strlen(trim($fecha)) == 0) {
            return '';
        }
        list($dia, $mes, $anio) = explode('-', $fecha);
        $diaDif = date('d') - $dia;
        $mesDif = date('m') - $mes;
        $anioDif = date('Y') - $anio;
        if ($mesDif < 0)
            $anioDif--;
        if ($mesDif == 0 and $diaDif < 0)
            $anioDif--;
        return $anioDif;
    }

    static function fechaFormatoYmd($fecha) {
        if (Utils::esfechaValida($fecha, true, '-')) {
            $date = new DateTime($fecha);
            return $date->format('Y-m-d');
        }
        return '';
    }

}
