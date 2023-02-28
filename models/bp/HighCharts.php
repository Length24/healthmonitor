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

    protected array $categories = [];

    public static function tableName()
    {
        return 'graph_settings';
    }

    public function getGraph()
    {
        $this->getRawData();
        $graph = [];
        $graph['series'] = $this->getSeries();
        $graph['chart'] = $this->getGraphtype();
        $graph['title'] = $this->getGraphtitle();
        $graph['xAxis'] = $this->getXAxis();
        $graph['yAxis'] = $this->getYAxis();
        $graph['tooltip'] = $this->getTooltip();
        $graph['plotOptions'] = $this->getPlotOptions();
        $graph['legend'] = $this->getLegend();


        return json_encode($graph);
    }

    /**
     * @return array
     */
    protected function getRawData(): array
    {
        $model = new Bp();
        $data = $model->getBpData();

        foreach ($data as $item) {
            if (isset($item['datetime'])) {
                $this->rawData[$item['datetime']] = $item;
            }
        }
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

        $this->categories = array_values($this->categories);
        $this->xAxis = ["categories" => $this->categories, 'title ' => ['text' => null]];
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
        $this->tooltip = ['valueSuffix' => ''];
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
        $legend = $this->getAttribute('include_legend');
        if ($legend == 1) {
            $this->legend = [
                "layout" => 'horizontal',
                "align" => 'bottom',
                "verticalAlign" => 'bottom',
                "x" => 0,
                "y" => 80,
                "floating" => true,
                "borderWidth" => 1,
                "shadow" => true
            ];
        } else {
            $this->legend = [
                "enabled" => false
            ];
        }
        return $this->legend;
    }

    private function addElements($name, &$row, $record)
    {
        if (isset($record[$name])) {
            $row[] = $record[$name];
            $this->categories[$name] = $name;
        }
    }

    /**
     * @return array
     */
    protected function getSeries(): array
    {
        $seriesType = $this->getAttribute('seriesType');
        $this->series = [];
        foreach ($this->rawData as $id => $record) {
            if ($seriesType == 2) {

            } else { // default pull everything by filter set
                $row = [];
                $this->addElements('SYSmmHg', $row, $record);
                $this->addElements('DIAmmHg', $row, $record);
                $this->addElements('Pulse', $row, $record);
                $this->addElements('Steps', $row, $record);
                $this->addElements('AverageKm', $row, $record);

                $this->series[] = ['name' => $id, "data" => $row];

            }
        }

        return $this->series;
    }


}