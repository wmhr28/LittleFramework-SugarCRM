<?php
/**
 * Description of LibChart_Ext
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
 */
//include_once '../Libraries/Php/libchart/classes/libchart.php';

class LibChart_Ext {

    private $title;
    private $filename;
    private $width;
    private $height;
    private $serie1;
    private $serie2;

    function __construct($title, $filename, $width, $height,$serie1=array(),$serie2=array()) {
        $this->title = $title;
        $this->filename = $filename;
        $this->width = $width;
        $this->height = $height;
        $this->serie1 = $serie1;
        $this->serie2 = $serie2;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function setSerie1($serie1) {
        $this->serie1 = $serie1;
    }

    public function setSerie2($serie2) {
        $this->serie2 = $serie2;
    }

    function barrasVerticales() {
        $chart = new VerticalBarChart($this->width, $this->height);
        $DataSet = new XYDataSet();
        if (count($this->serie1) == 0) {
            $DataSet->addPoint(new Point("Sin Datos", 0));
        } else {
            array_multisort($this->serie2, SORT_DESC, $this->serie1);
            $j = 0;
            foreach ($this->serie1 as $valor) {
                $DataSet->addPoint(new Point($this->serie2[$j], $valor));
                $j++;
            }
        }
        $chart->setDataSet($DataSet);
        $chart->setTitle($this->title);
        $chart->render($this->filename);
    }

    function pie() {        
        $DataSet = new pData; 
        if (count($this->serie1) == 0) {
            $DataSet->AddPoint(1, "Serie1");
            $DataSet->AddPoint(array("Sin Datos"), "Serie2");
        } else {
            array_multisort($this->serie2, SORT_DESC, $this->serie1);
            foreach ($this->serie2 as $row1) {
                $DataSet->AddPoint($row1, "Serie1");
            }
            foreach ($this->serie1 as $row2) {
                $DataSet->AddPoint($row2, "Serie2");
            }
        }        
        $DataSet->AddAllSeries();
        $DataSet->SetAbsciseLabelSerie("Serie2");

        // Initialise the graph
        $Test = new pChart($this->width, $this->height);
        $Test->drawFilledRoundedRectangle(7, 7, 700, 253, 5, 240, 240, 240);
        $Test->drawRoundedRectangle(5, 5, 700, 255, 5, 230, 230, 230);
        $Test->createColorGradientPalette(195, 204, 56, 223, 110, 41, 15);

        // Draw the pie chart
        $Test->setFontProperties("fonts/tahoma.ttf", 8);
        $Test->AntialiasQuality = 0;
        $Test->drawPieGraph($DataSet->GetData(), $DataSet->GetDataDescription(), 250, 128, 110, PIE_PERCENTAGE_LABEL, FALSE, 65, 20, 5);
        $Test->drawPieLegend(450, 15, $DataSet->GetData(), $DataSet->GetDataDescription(), 250, 250, 250);

        // Write the title
        $Test->setFontProperties("fonts/tahoma.ttf", 10);
        $Test->drawTitle(10, 20, $this->title, 100, 100, 100);

        $Test->Render($this->filename);
    }

}
