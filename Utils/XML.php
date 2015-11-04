<?php

/**
 * Description of XML
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
 */
class XML {

    static function assocArrayToXML($ar, $root_element_name) {
        $xml = new SimpleXMLElement("<{$root_element_name}></{$root_element_name}>");
        $f = create_function('$f,$c,$a', ' 
            foreach($a as $k=>$v) { 
                if(is_array($v)) {
                    $chName= array_key_exists("child_tag",$v)? $v["child_tag"] : $k;
                    $ch=$c->addChild($chName);
                    $f($f,$ch,$v); 
                } else {
                    if($k!="child_tag")
                        $c->addChild($k,$v); 
                } 
            }');
        $f($f, $xml, $ar);
        return $xml->asXML();
    }

    static function assocArrayToXML_2($ar, $root_element_name) {
        $xml = new SimpleXMLElement("<{$root_element_name}></{$root_element_name}>");
        self::ToXML($xml, $ar);
        return $xml->asXML();
    }

    static function ToXML($c, $a) {
        foreach ($a as $k => $v) {
            if (is_array($v)) {
                $chName = array_key_exists("child_tag", $v) ? $v["child_tag"] : $k;
                $ch = $c->addChild($chName);
                self::ToXML($ch, $v);
            } else {
                if ($k != "child_tag")
                    $c->addChild($k, $v);
            }
        }
    }

}
