<?php

use yii\db\Migration;

/**
 * Class m240807_115533_create_book_authors_category
 */
class m240807_115533_create_book_authors_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('author', [
            'author_id' => $this->primaryKey(),
            'author_name' => $this->string(100)->notNull(),
        ]);

        $this->createTable('book', [
            'book_id' => $this->primaryKey()->notNull(),
            'book_title' => $this->string(65)->defaultValue(null),
            'book_image' => $this->text(),
            'book_page' => $this->integer()->defaultValue(null),
            'book_short_desc' => $this->text(),
            'book_long_desc' => $this->text(),
            'book_status' => $this->string(55)->defaultValue(null),
            'book_isbn' => $this->string(55)->defaultValue(null),
            'book_published_date' => $this->integer()->defaultValue(null),
        ]);

        $this->createTable('category', [
            'category_id' => $this->primaryKey()->notNull(),
            'category_name' => $this->string(65)->notNull(),
        ]);

        $this->createTable('author_his_book', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull()
        ]);

        $this->createTable('category_has_book', [
            'book_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'fk-author_his_book-book_id',
            'author_his_book',
            'book_id',
            'book',
            'book_id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-author_his_book-author_id',
            'author_his_book',
            'author_id',
            'author',
            'author_id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-category_has_book-book_id',
            'category_has_book',
            'book_id',
            'book',
            'book_id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-category_has_book-category_id',
            'category_has_book',
            'category_id',
            'category',
            'category_id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m240807_115533_create_book_authors_category cannot be reverted.\n";

        $this->dropTable('category_has_book');
        $this->dropTable('author_his_book');
        $this->dropTable('category');
        $this->dropTable('book');
        $this->dropTable('author');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240807_115533_create_book_authors_category cannot be reverted.\n";

        return false;
    }
    */
}
