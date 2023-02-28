<?php

namespace app\modules\bp\models;

use app\models\bp\Health;
use yii\base\Exception;
use yii;

class Bp extends \yii\base\Model
{

    public array $dataTableColumns = [];

    /**
     * @return array
     */
    public function getDataTableColumns(): array
    {
        return $this->dataTableColumns;
    }

    /**
     * @param array $dataTableColumns
     */
    public function setDataTableColumns(array $dataTableColumns): void
    {
        $this->dataTableColumns = $dataTableColumns;
    }

    public function getBpData($json = false)
    {

        $result = [];
        $grouping = $this->getGrouping();
        $fields = $this->getFields();
        $where = $this->getBpDataWhere();

        if ($where !== false) {
            $sql = 'SELECT ' . $fields . '
                    FROM (
                        SELECT
                        rt.id ,
                        rt.datetime, 
                        rt.sys,
                         rt.dia, 
                         rt.pul, 
                         rt.step, 
                         rt.other, 
                        CONCAT(ro.url,"?id=",rt.id,"&del") as del, 
                        CONCAT(ro.url,"?id=",rt.id,"&edit") as edit,
                        CONCAT(date(rt.datetime), " ",TIME_FORMAT(rt.datetime, "%p")) as dateampm,
						DATE(rt.datetime) as justdate,
						TIME_FORMAT(rt.datetime, "%p") as ampm,
						YEARWEEK(rt.datetime) as weekNum
                        FROM bpmain.health_check rt
                        JOIN bpmain.routing ro ON ro.id = 1
                        ' . $where['sql'] . '
                        
                    ) a
                    ' . $grouping
                . ' ORDER BY a.datetime DESC';

            $result = Yii::$app->db->createCommand($sql, $where['params'])->queryAll();
        }

        if ($json) {
            $result = array_map('array_values', $result); //datatable needs unkeyed array
            $result = json_encode($result);
        }
        return $result;
    }

    private function getGrouping()
    {
        $get = Yii::$app->request->get();
        $grouping = '';
        if (isset($get['filter'])) {
            $filter = $get['filter'];
            if ($filter == 2) {
                $grouping = 'GROUP BY a.dateampm';
            } else if ($filter == 3) {
                $grouping = 'GROUP BY a.justdate';
            } else if ($filter == 4) {
                $grouping = 'GROUP BY a.weekNum';
            } else if ($filter == 5) {
                $grouping = 'GROUP BY a.justdate';
            } else if ($filter == 6) {
                $grouping = 'GROUP BY a.justdate';
            }
        }

        return $grouping;
    }

    private function getFields()
    {
        $this->dataTableColumns = [];
        $get = Yii::$app->request->get();
        $filter = 1;
        if (isset($get['filter'])) {
            $filter = $get['filter'];
        }

        $fields['datetime'] = 'a.datetime';
        $this->dataTableColumns['datetime'] = true;

        if (in_array($filter, [2])) {
            $fields['datetime'] = 'a.dateampm';
        }
        if (in_array($filter, [4])) {
            $fields['datetime'] = 'a.weekNum';
        }
        if (in_array($filter, [3, 5, 6])) {
            $fields['datetime'] = 'a.datetime';
        }

        $array = ['SYSmmHg' => 'sys',
            'DIAmmHg' => 'dia',
            'Pulse' => 'pul',
            'Steps' => 'step',
            'AverageKm' => 'round((a.step / 1400),2)',
            'otherInfo' => 'other'];

        $anyChecked = false;
        foreach ($array as $id => $field) {
            if (isset($get[$id])) {
                $this->dataTableColumns[$id] = true;
                $anyChecked = true;
                $keyword = '';
                $endword = '), 1)';

                if (in_array($filter, [2, 3, 4])) { //apply max and min for filters
                    $keyword = 'ROUND(AVG(';

                }
                if (in_array($filter, [5])) { //apply max and min for filters
                    $keyword = 'ROUND(MAX(';
                }
                if (in_array($filter, [6])) { //apply max and min for filters
                    $keyword = 'ROUND(MIN(';
                }

                if ($filter == 1) {
                    $endword = '';
                }

                if ($id == 'otherInfo' && $filter != 1) { //filter technically string
                    $endword = ')';
                    $keyword = 'GROUP_CONCAT(';
                }

                $fields[] = $keyword . $field . $endword . ' as ' . $id;
            }
        }

        if (!$anyChecked) {
            foreach ($array as $id => $field) {
                $this->dataTableColumns[$id] = true;
                $fields[] = $field . ' as ' . $id;
            }
        }

        if ($filter == 1) { // no edit and delete filters if groupBy data
            $this->dataTableColumns['del'] = true;
            $this->dataTableColumns['edit'] = true;
            $fields['del'] = 'a.del';
            $fields['edit'] = 'a.edit';
        }

        return implode(',', $fields);

    }

    public function getBpDataWhere()
    {
        $where['params'] = [];
        $where['sql'] = 'WHERE rt.deleted = 0  ';
        $cookies = Yii::$app->request->cookies;
        $id = null;
        if (isset($cookies['id'])) {
            $id = $cookies['id']->value;
        }

        // if no set id, don't return data!
        if ($id !== null) {
            $get = Yii::$app->request->get();
            if (isset($get['todate']) && isset($get['fromdate'])) {
                $where['sql'] = $where['sql'] . "AND rt.datetime BETWEEN :from AND :to ";
                $where['params'][':to'] = $get['todate'] . ' 23:59:59';
                $where['params'][':from'] = $get['fromdate'] . ' 00:00:00';
            }
            //add userId check
            $where['sql'] = $where['sql'] . "AND userid = :userID ";
            $where['params'][':userID'] = $id;
            return $where;
        }


        return false;

    }

    public function saveHealthCheck($post)
    {
        try {
            $cookies = Yii::$app->request->cookies;
            $id = null;
            if (isset($cookies['id'])) {
                $id = $cookies['id']->value;
            }

            $health = new Health();
            $health->sys = $post['sys'];
            $health->dia = $post['dia'];
            $health->userid = $id;
            $health->pul = $post['pul'];
            $health->step = $post['step'];
            $health->other = $post['other'];

            if ($post['senddaydate'] == "") {
                $date = new \dateTime();
                $thisData = $date->format('Y-m-d H-i');
                $health->datetime = $thisData;
            } else {
                $health->datetime = $post['senddaydate'] . ' ' . $post['senddaytime'];
            }
            $health->save();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}