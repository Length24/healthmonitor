<?php

namespace app\models\bp;

use app\models\CreateFile;
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
        $data = $model->getBpData(false, 'ORDER BY max(a.orderby) ASC');

        foreach ($data as $item) {
            if (isset($item['datetimecheck'])) {
                $this->rawData[$item['datetimecheck']] = $item;
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
                "text" => \Yii::$app->params['webAddress'],
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

    private function addElements($name, $row, $record, $setCatIdToName = true)
    {
        if (isset($record[$name])) {
            $row[] = (int)$record[$name];
            if ($setCatIdToName) {
                $this->categories[$name] = $name;
            }
        }
        return $row;
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
        $rows = [];
        $include = $this->getAttribute('data_include');
        foreach ($this->rawData as $id => $record) {
            if ($seriesType == 2) { // nly include steps
                foreach ($include as $name) {
                    $row = $this->addElements($name, $row, $record, false);
                    if (isset($record[$name])) {
                        $this->categories[$id] = $id;
                    }
                }
            } else if ($seriesType == 3) {
                $row = [];
                foreach ($include as $name) {
                    $row = $this->addElements($name, $row, $record);
                }
                $this->series[] = ['name' => $id, "data" => $row];
            } else { // default pull everything by filter set
                foreach ($include as $jsonId => $name) {
                    if (!isset($rows[$jsonId])) {
                        $rows[$jsonId] = [];
                    }
                    $rows[$jsonId] = $this->addElements($name, $rows[$jsonId], $record, false);
                }
                $this->categories[$id] = $id;
            }
        }
        if ($seriesType == 2) { // nly include steps
            $this->series[] = ['name' => $id, "data" => $row];
        } else if ($seriesType == 1) {
            foreach ($include as $jsonId => $name) {
                $this->series[] = ['name' => $name, "data" => $rows[$jsonId]];

            }
        }

        return $this->series;
    }

    public function exportModule($graph)
    {
        $dataString = ('type=image/jpeg&width=800&options=' . $graph);
        $url = 'https://export.highcharts.com/?';
        $ch = curl_init();

        $file = CreateFile::createFile(rand(1, 100000) . time() . ".jpg");
        $img = $file['path'];
        $fp = $file['fp'];

        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_POST, 1);

        $result = curl_exec($ch);

        fwrite($fp, $result);
        fclose($fp);
        return $img;
    }


}