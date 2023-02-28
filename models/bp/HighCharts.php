<?php

namespace app\models\bp;

use app\modules\bp\models\Bp;
use yii\base\BaseObject;
use yii\db\ActiveRecord;

class HighCharts extends ActiveRecord
{
    protected array $rawData = [];

    protected array $graphtype = [];
    protected array $graphtitle = [];
    protected array $xAxis = [];
    protected array $yAxis = [];
    protected array $tooltip = [];
    protected array $plotOptions = [];
    protected array $legend = [];
    protected array $series = [];

    public static function tableName()
    {
        return 'graph_settings';
    }

    public function getGraph()
    {
        $this->getRawData();
        $graph = [];
        $graph['chart'] = $this->getGraphtype();
        $graph['title'] = $this->getGraphtitle();
        $graph['xAxis'] = $this->getXAxis();
        $graph['yAxis'] = $this->getYAxis();
        $graph['tooltip'] = $this->getTooltip();
        $graph['plotOptions'] = $this->getPlotOptions();
        $graph['legend'] = $this->getLegend();
        $graph['series'] = $this->getSeries();

        return json_encode($graph);
    }

    /**
     * @return array
     */
    protected function getRawData(): array
    {
        $model = new Bp();
        $fullData = $model->getBpData(true);
        return $this->rawData;
    }

    /**
     * @return array
     */
    protected function getGraphtype(): array
    {
        $this->graphtype = ['type' => $this->getAttribute('type')];
        return $this->graphtype;
    }

    /**
     * @return array
     */
    protected function getGraphtitle(): array
    {
        $this->graphtitle = ['text' => $this->getAttribute('title'), "align" => 'left'];
        return $this->graphtitle;
    }

    /**
     * @return array
     */
    protected function getXAxis(): array
    {
        $this->xAxis = ["categories" => ['africa', 'america', 'Asia'], 'title ' => ['text' => null]];
        return $this->xAxis;
    }

    /**
     * @return array
     */
    protected function getYAxis(): array
    {
        $this->yAxis = [
            "min" => 0, "title" => [
                "text" => 'Population (millions)',
                "align" => 'high'
            ],
            "labels" => [
                "overflow" => 'justify'
            ]];
        return $this->yAxis;
    }

    /**
     * @return array
     */
    protected function getTooltip(): array
    {
        $this->tooltip = ['valueSuffix' => 'Mil'];
        return $this->tooltip;
    }

    /**
     * @return array
     */
    protected function getPlotOptions(): array
    {
        $this->plotOptions =
            ["bar" =>
                ["dataLabels" => [
                    "enabled" => true]
                ]
            ];
        return $this->plotOptions;
    }

    /**
     * @return array
     */
    protected function getLegend(): array
    {
        $this->legend = [
            "layout" => 'vertical',
            "align" => 'right',
            "verticalAlign" => 'top',
            "x" => -40,
            "y" => 80,
            "floating" => true,
            "borderWidth" => 1,
            "shadow" => true
        ];
        return $this->legend;
    }

    /**
     * @return array
     */
    protected function getSeries(): array
    {
        $this->series = [

            ["name" => 'Year 1990',
                "data" => [631, 727, 3202, 721, 26]],
            ["name" => 'Year 2000',
                "data" => [814, 841, 3714, 726, 31]],
            ["name" => 'Year 2010',
                "data" => [1044, 944, 4170, 735, 40]],
            ["name" => 'Year 2018',
                "data" => [1276, 1007, 4561, 746, 42]]

        ];

        return $this->series;
    }


}