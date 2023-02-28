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
        $data = $model->getBpData(false, 'ORDER BY a.datetime ASC');

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
        if ($legend) {
            $this->legend = [
                "enabled" => true
            ];
        } else {
            $this->legend = [
                "enabled" => false
            ];
        }
        return $this->legend;
    }

    private function addElements($name, &$row, $record, $setCatIdToName = true)
    {
        if (isset($record[$name])) {
            $row[] = (int)$record[$name];
            if ($setCatIdToName) {
                $this->categories[$name] = $name;
            }
        }
    }

    /**
     * @return array
     */
    protected function getSeries(): array
    {
        $seriesType = $this->getAttribute('seriestype');
        $this->series = [];
        $id = '';
        $row = [];
        $row1 = [];
        $row2 = [];
        $row3 = [];
        $row4 = [];
        $row5 = [];
        foreach ($this->rawData as $id => $record) {
            if ($seriesType == 2) { // nly include steps
                $this->addElements('Steps', $row, $record, false);
                if (isset($record['Steps'])) {
                    $this->categories[$id] = $id;
                }
            } else if ($seriesType == 3) {
                $row = [];
                $this->addElements('SYSmmHg', $row, $record);
                $this->addElements('DIAmmHg', $row, $record);
                $this->addElements('Pulse', $row, $record);
                $this->series[] = ['name' => $id, "data" => $row];
            } else { // default pull everything by filter set
                $this->addElements('SYSmmHg', $row, $record, false);
                $this->addElements('DIAmmHg', $row1, $record, false);
                $this->addElements('Pulse', $row2, $record, false);
                $this->addElements('Steps', $row3, $record, false);
                $this->addElements('AverageKm', $row4, $record, false);
                $this->categories[$id] = $id;
            }
        }
        if ($seriesType == 2) { // nly include steps
            $this->series[] = ['name' => $id, "data" => $row];
        } else {
            $this->series[] = ['name' => 'SYSmmHg', "data" => $row];
            $this->series[] = ['name' => 'DIAmmHg', "data" => $row1];
            $this->series[] = ['name' => 'Pulse', "data" => $row2];
            $this->series[] = ['name' => 'Steps', "data" => $row3];
            $this->series[] = ['name' => 'AverageKm', "data" => $row4];
        }

        return $this->series;
    }


}