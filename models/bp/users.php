<?php

namespace app\models\bp;

use yii\db\ActiveRecord;

class users extends ActiveRecord
{
    public static function tableName()
    {
        return 'users';
    }
}