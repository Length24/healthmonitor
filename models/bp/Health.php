<?php

namespace app\models\bp;

use yii\db\ActiveRecord;
use yii;

class Health extends ActiveRecord
{
    public static function tableName()
    {
        return 'health_check';
    }

    public function updateCheck($post)
    {
        $this->sys = $post['sys'];
        $this->dia = $post['dia'];
        $this->pul = $post['pul'];
        $this->step = $post['step'];
        $this->other = $post['other'];
        $this->datetimecheck = $post['senddaydate'] . ' ' . $post['senddaytime'];
        $this->save();
    }

    public static function getWeekStats()
    {
        $sql = "SELECT AVG(hc.step) as avgStep, AVG(hc.sys) as avgSys, AVG(hc.dia) as avgDia, AVG(hc.pul) as avgPul, COUNT(hc.id) as count , COUNT(hc.id) / 7 as avgDay
                    FROM bpmain.health_check hc
                    WHERE hc.datetimecheck >= DATE(NOW() - INTERVAL 7 DAY)
                    ";

        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if (is_array($result)) {
            return reset($result);
        }
        return false;
    }
}