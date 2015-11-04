<?php

/**
 * Description of SugarCrm
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
 */
class SugarCrm {

    private static $url;
    private static $username;
    private static $password;

    function __construct() {
        $signData = json_decode(SIGN_DATA_CRM, true);
        self::$url = $signData['base_url'];
        self::$username = $signData['username'];
        self::$password = $signData['password'];
    }

    private static function call($method, $parameters) {

        $jsonEncodedData = json_encode($parameters);
        $post = array(
            "method" => $method,
            "input_type" => "JSON",
            "response_type" => "JSON",
            "rest_data" => $jsonEncodedData
        );
        $resultCURL = CustomCURL::Post(self::$url, $post);
        $result = explode("\r\n\r\n", $resultCURL, 2);
        return json_decode($result[1]);
    }

    protected static function login() {
        $login_parameters = array(
            "user_auth" => array(
                "user_name" => self::$username,
                "password" => md5(self::$password),
                "version" => "1"),
            "application_name" => FRAME_NAME,
            "name_value_list" => array(),
        );
        $login_result = self::call("login", $login_parameters);
        return $login_result->id;
    }

    protected static function set_entries($module, $arrayData, $session_id) {
        $set_entries_parameters = array(
            "session" => $session_id,
            //The name of the module from which to retrieve records.
            "module_name" => $module,
            //Record attributes
            "name_value_list" => $arrayData,
        );
        return self::call("set_entries", $set_entries_parameters);
    }

    protected static function set_entry($module, $arrayData, $session_id) {
        $set_entries_parameters = array(
            "session" => $session_id,
            //The name of the module from which to retrieve records.
            "module_name" => $module,
            //Record attributes
            "name_value_list" => $arrayData,
        );
        return self::call("set_entry", $set_entries_parameters);
    }

    protected static function set_relationship($module, $module_id, $moduleRel, $related_id, $session_id) {
        $relationship_parameters = array(
            'session' => $session_id,
            'module_name' => $module,
            'module_id' => $module_id,
            'link_field_name' => $moduleRel,
            'related_ids' => array(
                $related_id
            ),
            'name_value_list' => array(),
            'delete' => 0
        );
        return self::call("set_relationship", $relationship_parameters);
    }

    protected static function create_guid() {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);

        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);

        SugarCrm::ensure_length($dec_hex, 5);
        SugarCrm::ensure_length($sec_hex, 6);

        $guid = "";
        $guid .= $dec_hex;
        $guid .= SugarCrm::create_guid_section(3);
        $guid .= '-';
        $guid .= SugarCrm::create_guid_section(4);
        $guid .= '-';
        $guid .= SugarCrm::create_guid_section(4);
        $guid .= '-';
        $guid .= SugarCrm::create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= SugarCrm::create_guid_section(6);

        return $guid;
    }

    private static function create_guid_section($characters) {
        $return = "";
        for ($i = 0; $i < $characters; $i++)
            $return .= dechex(mt_rand(0, 15));

        return $return;
    }

    private static function ensure_length(&$string, $length) {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, "0");
        } elseif ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }

}
