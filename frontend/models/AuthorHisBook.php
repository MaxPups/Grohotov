<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class AuthorHisBook extends ActiveRecord
{
    public static function tableName()
    {
        return 'author_his_book'; 
    }

    public function rules()
    {
        return [
            [['book_id', 'author_id'], 'required'], 
            [['book_id', 'author_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'book_id' => 'Book ID',
            'author_id' => 'Author ID',
        ];
    }


    public function getBook()
    {
        return $this->hasOne(Book::class, ['book_id' => 'book_id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['author_id' => 'author_id']);
    }
}
