<?php

namespace app\modules\bp\models;

use app\models\bp\Health;
use yii\base\Exception;
use yii;

class Bp extends \yii\base\Model
{


    public function getBpData($json = false)
    {
        $result = [];

        $where = $this->getBpDataWhere();
        if ($where !== false) {
            $sql = 'SELECT rt.datetime, rt.sys, rt.dia, rt.pul, rt.step, rt.other, concat(ro.url,"?id=",rt.id,"&del") as del, concat(ro.url,"?id=",rt.id,"&edit") as edit
                        FROM bpmain.health_check rt
                        JOIN bpmain.routing ro ON ro.id = 1
                        ' . $where['sql'] . '
                        ORDER BY rt.datetime';

            $result = Yii::$app->db->createCommand($sql, $where['params'])->queryAll();
        }

        if ($json) {
            $result = array_map('array_values', $result); //datatable needs unkeyed array
            $result = json_encode($result);
        }
        return $result;
    }

    public function getBpDataWhere()
    {
        $where['params'] = [];
        $where['sql'] = 'WHERE rt.deleted = 0 AND ';
        $cookies = Yii::$app->request->cookies;
        $id = null;
        if (isset($cookies['id'])) {
            $id = $cookies['id']->value;
        }
        // if no set id, don't return data!
        if ($id !== null) {
            //add userId check
            $where['sql'] = $where['sql'] . "userid = :userID ";
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
            Var_Dump($e);
            die();
            return false;
        }
        return true;
    }
}