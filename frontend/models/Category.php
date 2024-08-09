<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return 'category'; 
    }

    public function rules()
    {
        return [
            [['category_name'], 'required'],
            ['category_name', 'string', 'max' => 100],
            ['category_id', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'category_id' => 'category ID',
            'category_name' => 'category Name',
        ];
    }
}
