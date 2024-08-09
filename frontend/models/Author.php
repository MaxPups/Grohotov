<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Author extends ActiveRecord
{
    public static function tableName()
    {
        return 'author'; 
    }

    public function rules()
    {
        return [
            [['author_name'], 'required'],
            ['author_name', 'string', 'max' => 100],
            ['author_id', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'author_id' => 'Author ID',
            'author_name' => 'Author Name',
        ];
    }
}
