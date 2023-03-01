<?php

namespace app\models\bp;

use yii\db\ActiveRecord;

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
}