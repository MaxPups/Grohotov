<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property int $book_id
 * @property string $book_title
 * @property string|null $book_image
 * @property int $book_page
 * @property string|null $book_short_desc
 * @property string|null $book_long_desc
 * @property string $book_status
 * @property int $book_isbn
 * @property int|null $book_published_date
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_page', 'book_published_date'], 'integer'],
            [['book_short_desc', 'book_long_desc', 'book_image'], 'string'],
            [['book_title', 'book_status', 'book_isbn'], 'string', 'max' => 55]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'book_id' => 'Book ID',
            'book_title' => 'Book Title',
            'book_image' => 'Book Image',
            'book_page' => 'Book Page',
            'book_short_desc' => 'Book Short Description',
            'book_long_desc' => 'Book Long Description',
            'book_status' => 'Book Status',
            'book_isbn' => 'Book ISBN',
            'book_published_date' => 'Book Published Date',
        ];
    }
}
