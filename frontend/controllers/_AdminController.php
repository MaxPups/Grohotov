<?php

namespace frontend\controllers;

use app\models\Author;
use app\models\AuthorHisBook;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use app\models\Book;
use app\models\Category;
use app\models\CategoryHasBook as ModelsCategoryHasBook;
use yii\models\CategoryHasBook;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;




class AdminController extends Controller
{
   public function actionIndex()
   {
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
      $authors = Author::find()->asArray()->all();
      $category = Category::find()->asArray()->all();
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

      // var_export($books);
      // return 1;
      if (!$books) {
         return $this->redirect(['admin/']);
      }
      return $this->render('card', ['books' => $books, 'authors' =>  $authors, 'category' => $category]);

      // $books = $query->all();
      // http://localhost/web/index.php?r=book/card&id=123123123
   }
   public function actionCreate()
   {
      return $this->render('create');
   }
   public function actionAdd()
   {
      $request = Yii::$app->request;
      $book_id = $request->get('book_id');
      $book_title = $request->get('book_title');
      // return $book_title ? $book_title : 'null';
      $book_page = $request->get('book_page');
      $book_isbn = $request->get('book_isbn');
      $book_image = $request->get('book_image');
      $book_published_date = $request->get('book_published_date');
      $book_short_desc = $request->get('book_short_desc');
      $book_long_desc = $request->get('book_long_desc');
      $book_status = $request->get('book_status');

      $author = json_decode($request->get('author'));
      $category = json_decode($request->get('category'));

      $model = new Book();
      $model->book_title = $book_title;
      $model->book_page = $book_page;
      $model->book_isbn = $book_isbn;
      $model->book_image = $book_image;

      // $model->book_published_date = $book_published_date;
      $model->book_published_date = strtotime($book_published_date);
      $model->book_short_desc = $book_short_desc;
      $model->book_long_desc = $book_long_desc;
      $model->book_status = $book_status;
      $model->save();
      $book_id = Yii::$app->db->getLastInsertID();

      //   addTo foreign key
      $authors_data = [];
      foreach ($author as $row) {
         $authors_data[] = ['book_id' => $book_id, 'author_id' => $row];
      }
      $columns = ['book_id', 'author_id'];
      $command = Yii::$app->db->createCommand();
      $command->batchInsert('author_his_book', $columns, $authors_data)->execute();



      //   addTo foreign key
      $category_data = [];
      foreach ($category as $row) {
         $category_data[] = ['book_id' => $book_id, 'category_id' => $row];
      }
      $columns = ['book_id', 'category_id'];
      $command_category = Yii::$app->db->createCommand();
      $command_category->batchInsert('category_has_book', $columns, $category_data)->execute();

      // return $this->redirect(['card/?id=' . $book_id]);
      return $book_id;
   }

   public function actionDelete($id)
   {
      // без транзакций
      // AuthorHisBook::deleteAll(['book_id' => $id]);
      // CategoryHisBook::deleteAll(['book_id' => $id]);
      // Book::deleteAll(['book_id' => $id]);

      $transaction = Yii::$app->db->beginTransaction();
      try {
         AuthorHisBook::deleteAll(['book_id' => $id]);
         ModelsCategoryHasBook::deleteAll(['book_id' => $id]);
         $book = Book::findOne($id);
         if ($book === null) {
            throw new NotFoundHttpException("Книга не найдена.");
         }
         $book->delete();
         $transaction->commit();
         return true;
      } catch (\Exception $e) {
         $transaction->rollBack();
         throw $e;
      }
   }
   public function actionEdit()
   {
      $request = Yii::$app->request;
      $book_id = $request->get('book_id');
      $book_title = $request->get('book_title');
      $book_page = $request->get('book_page');
      $book_isbn = $request->get('book_isbn');
      $book_image = $request->get('book_image');
      $book_published_date = $request->get('book_published_date');
      $book_short_desc = $request->get('book_short_desc');
      $book_long_desc = $request->get('book_long_desc');
      $book_status = $request->get('book_status');

      $author = json_decode($request->get('author'));
      $category = json_decode($request->get('category'));

      //   delete from foreign key
      AuthorHisBook::deleteAll(['book_id' => $book_id]);
      ModelsCategoryHasBook::deleteAll(['book_id' => $book_id]);



      //   addTo foreign key
      $authors_data = [];
      foreach ($author as $row) {
         $authors_data[] = ['book_id' => $book_id, 'author_id' => $row];
      }
      $columns = ['book_id', 'author_id'];
      $command = Yii::$app->db->createCommand();
      $command->batchInsert('author_his_book', $columns, $authors_data)->execute();



      //   addTo foreign key
      $category_data = [];
      foreach ($category as $row) {
         $category_data[] = ['book_id' => $book_id, 'category_id' => $row];
      }
      $columns = ['book_id', 'category_id'];
      $command_category = Yii::$app->db->createCommand();
      $command_category->batchInsert('category_has_book', $columns, $category_data)->execute();






      $model = Book::findOne($book_id);

      $model->book_title = $book_title;
      $model->book_page = $book_page;
      $model->book_isbn = $book_isbn;
      $model->book_image = $book_image;

      // $model->book_published_date = $book_published_date;
      $model->book_published_date = strtotime($book_published_date);
      $model->book_short_desc = $book_short_desc;
      $model->book_long_desc = $book_long_desc;
      $model->book_status = $book_status;
      $model->save();

      return strtotime($book_published_date);
   }
}
