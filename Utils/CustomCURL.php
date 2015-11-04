<?php
 
/**
 * Description of CustomCURL
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 * @date 2/05/2014
 * ***************
 * @Version 2 
 * @dateMod 15/05/2015
 */
class CustomCURL {

    static function Post($url, $data = "", $httpAuth = FALSE, $user = "", $password = "", $return = 1, $language = "es-ES,es", $encoding = 'gzip,deflate,sdch') {
        ob_start();
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_USERAGENT, FRAME_NAME);
        curl_setopt($cURL, CURLOPT_HEADER, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, $return);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array("Accept-Language: $language"));
        curl_setopt($cURL, CURLOPT_ENCODING, $encoding);


        curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, 0);

        if ($httpAuth) {
            curl_setopt($cURL, CURLOPT_HEADER, false);
            curl_setopt($cURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($cURL, CURLOPT_USERPWD, "$user:$password");
        }

        curl_setopt($cURL, CURLOPT_POST, true);
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($cURL);
        curl_close($cURL);
        ob_end_flush();
        return $response;
    }

    static function SugarCall($url, $oauthtoken = '', $type = 'GET', $arguments = array(), $encodeData = true, $returnHeaders = false) {
        $type = strtoupper($type);
        if ($type == 'GET') {
            $url .= "?" . http_build_query($arguments);
        }
        $curl_request = curl_init($url);
        if ($type == 'POST') {
            curl_setopt($curl_request, CURLOPT_POST, 1);
        } elseif ($type == 'PUT') {
            curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "PUT");
        } elseif ($type == 'DELETE') {
            curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "DELETE");
        }
        curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl_request, CURLOPT_HEADER, $returnHeaders);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
        if (!empty($oauthtoken)) {
            $token = array("oauth-token: {$oauthtoken}");
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, $token);
        }
        if (!empty($arguments) && $type !== 'GET') {
            if ($encodeData) {
                //encode the arguments as JSON
                $arguments = json_encode($arguments);
            }
            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $arguments);
        }
        $result = curl_exec($curl_request);
        if ($returnHeaders) {
            //set headers from response
            list($headers, $content) = explode("\r\n\r\n", $result, 2);
            foreach (explode("\r\n", $headers) as $header) {
                header($header);
            }
            //return the nonheader data
            return trim($content);
        }
        curl_close($curl_request);
        //decode the response from JSON
        $response = json_decode($result);
        return $response;
    }

}

?>
