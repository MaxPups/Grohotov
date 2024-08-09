<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class CategoryHasBook extends ActiveRecord
{
    public static function tableName()
    {
        return 'category_has_book'; 
    }

    public function rules()
    {
        return [
            [['book_id', 'category_id'], 'required'], 
            [['book_id', 'category_id'], 'integer'], 
        ];
    }

    public function attributeLabels()
    {
        return [
            'book_id' => 'Book ID',
            'category_id' => 'category ID',
        ];
    }


    public function getBook()
    {
        return $this->hasOne(Book::class, ['book_id' => 'book_id']);
    }

    public function getcategory()
    {
        return $this->hasOne(category::class, ['category_id' => 'category_id']);
    }
}
