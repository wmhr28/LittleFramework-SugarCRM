<?php 
/**
 * Description of HTML
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 * @date 30/04/2014
 */
class HTML {

    var $html;
    var $doc;

    function __construct($html, $metatag = '<?xml encoding="utf-8" ?>') {
        $this->html = $metatag . $html;
        $this->doc = new DOMDocument();        
        @$this->doc->loadHTML($this->html);
    }

    function removeTag($tags) {
        $as = $this->doc->getElementsByTagName($tags);
        $nodelist=array();
        foreach ($as as $a)
            $nodelist[] = $a;
        foreach ($nodelist as $a)
            $a->parentNode->removeChild($a);
        $innerHTML = "";
        $nodeBody = $this->doc->getElementsByTagName('body')->item(0);
        foreach ($nodeBody->childNodes as $child) {
            $innerHTML .= $nodeBody->ownerDocument->saveXML($child);
        }
        $this->html = $innerHTML;
        return $this->html;
    }

    function getElementsByTag($tag, $index = -1) {        
        $as = $this->doc->getElementsByTagName($tag);        
        foreach ($as as $a)
            $nodelist[] = $a;
        return ($index == -1) ? $nodelist : $nodelist[$index];
    }

    public function getHtml() {
        return $this->html;
    }

    static function encodeEntity($str) {
        return htmlentities($str, 0, 'UTF-8');
    }

    static function decodeEntity($str) {
        return html_entity_decode($str, 0, 'UTF-8');
    }

}

?>
