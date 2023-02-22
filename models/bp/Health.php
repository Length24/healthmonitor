<?php

namespace app\models\bp;

use yii\db\ActiveRecord;

class Health extends  ActiveRecord
{
    public static function tableName()
    {
        return 'health_check';
    }
}