<?php

namespace frontend\controllers;

use app\models\Author;
use Yii;
use yii\web\Controller;
use app\models\Book;
use app\models\Category;
use yii\db\Query;
use yii\helpers\Html;

class BookController extends Controller
{
   public function actionIndex()
   {
      // return "hello books";
      $error = false;
      $query = (new Query())
         ->select([
            'categories' => 'GROUP_CONCAT(DISTINCT category.category_name)',
            'authors' => 'GROUP_CONCAT(DISTINCT author.author_name)',
            'book.*'
         ])
         ->from('book')
         ->innerJoin('author_his_book', 'author_his_book.book_id = book.book_id')
         ->innerJoin('author', 'author.author_id = author_his_book.author_id')
         ->innerJoin('category_has_book', 'category_has_book.book_id = book.book_id')
         ->innerJoin('category', 'category.category_id = category_has_book.category_id')
         ->groupBy('book.book_id');

      $books = $query->all();
      if (count($books) == 0) {
         $error = '<a href="parse/" class="btn btn-primary my-5">Go parse</a>';
      }
      return $this->render('index', ['books' => $books, 'error' => $error]);
   }


   public function actionCard($id)
   {
      $query = (new Query())
         ->select([
            'categories' => 'GROUP_CONCAT(DISTINCT category.category_name)',
            'authors' => 'GROUP_CONCAT(DISTINCT author.author_name)',
            'book.*'
         ])
         ->from('book')
         ->innerJoin('author_his_book', 'author_his_book.book_id = book.book_id')
         ->innerJoin('author', 'author.author_id = author_his_book.author_id')
         ->innerJoin('category_has_book', 'category_has_book.book_id = book.book_id')
         ->innerJoin('category', 'category.category_id = category_has_book.category_id')
         ->where(['book.book_id' => [$id]])
         ->groupBy('book.book_id');
      $books = $query->one();
      if(!$books){
         return $this->redirect(['book/']);
        }
      return $this->render('card', ['books' => $books]);

   }


   public function actionParse()
   {

      $filePath = Yii::getAlias('@webroot/source/books.json');
      // $fileImg  = Yii::getAlias('@webroot/source/image/');

      $data = file_get_contents($filePath);
      $data = (array) json_decode($data);
      $db = Yii::$app->db;

      $all_authors = [];
      $all_categories = [];
      $authors = [];
      $categories = [];
      $ids = [];
      // добавляем все книги
      foreach ($data as $index => $row) {
         $row = (array) $row;
         $book_id = ++$index;
         $command = $db->createCommand();
         $command->insert('book', [
            'book_id' => null,
            'book_title' => @$row['title'],
            'book_image' => @$row['thumbnailUrl'],
            'book_page' => @$row['pageCount'],
            'book_short_desc' => @$row['shortDescription'],
            'book_long_desc' => @$row['longDescription'],
            'book_status' => @$row['status'],
            'book_isbn' => @$row['isbn'] ? $row['isbn'] : 0,
            'book_published_date' => time(),
         ])->execute();
         $book_id = $db->getLastInsertID();
         $ids[] =  $book_id;
         $authors[$book_id] = $row['authors'];
         $categories[$book_id] = $row['categories'];
         $all_authors = array_merge($all_authors, $row['authors']);
         $all_categories = array_merge($all_categories, $row['categories']);
      }

      $all_authors = array_unique($all_authors);
      $all_categories = array_unique($all_categories);



      // добавляем всех авторов в таблицу авторов
      $author_his_id = [];
      foreach ($all_authors as $row) {
         if ($row != '') {
            $command = $db->createCommand();
            $command->insert('author', [
               'author_id' => null,
               'author_name' => $row,
            ])->execute();
            $author_id = $db->getLastInsertID();
            $author_his_id[$row] = $author_id;
         }
      }

      // добавляем всех авторов в связующую таблицу авторов
      foreach ($authors as $book_id => $author_book) {
         foreach ($author_book as $row) {
            if ($row != '') {
               $command = $db->createCommand();
               $command->insert('author_his_book', [
                  'author_id' => $author_his_id[$row],
                  'book_id' => $book_id,
               ])->execute();
            }
         }
      }



      // добавляем всех авторов в таблицу категорий
      $category_his_id = [];
      foreach ($all_categories as $row) {
         if ($row != '') {
            $command = $db->createCommand();
            $command->insert('category', [
               'category_id' => null,
               'category_name' => $row,
            ])->execute();
            $category_id = $db->getLastInsertID();
            $category_his_id[$row] = $category_id;
         }
      }

      // добавляем всех авторов в связующую таблицу авторов
      foreach ($categories as $book_id => $category_book) {
         foreach ($category_book as $row) {
            if ($row != '') {
               $command = $db->createCommand();
               $command->insert('category_has_book', [
                  'category_id' => $category_his_id[$row],
                  'book_id' => $book_id,
               ])->execute();
            }
         }
      }
      return '<button class="\'btn btn-primary\'" onclick="window.history.back();">Назад</button>';
   }
}
