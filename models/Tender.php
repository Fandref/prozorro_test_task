<?php

namespace app\models;

use yii\db\ActiveRecord;

class Tender extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%tenders}}';
    }

    public function rules()
    {
        return [
            [['tender_id', 'description', 'amount', 'date_modified'], 'required'],
            [['tender_id'], 'string', 'length' => 32],
            [['description'], 'string'],
            [['amount'], 'double'],
            [['date_modified'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }
}