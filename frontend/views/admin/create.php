<?php

use app\models\Author;
use app\models\Category;
use PhpParser\Node\Stmt\Foreach_;

// var_export($books['authors']);

// $book_has_author = explode(',', $books['authors']);
// $book_has_category = explode(',', $books['categories']);
// $book_has_author = Author::find()->asArray()->all();
// $book_has_category = Category::find()->asArray()->all();

// тут может быть массив значений
$status_publish = ['PUBLISH', 'NON PUBLISH'];


?>


<form class="border m-5 p-5">
   <!-- title -->
   <div class="form-group m-5">
      <label for="book_title">book title:</label>
      <input type="text" class="form-control" id="book_title" value="some title" require />
   </div>

   <!-- count page -->
   <div class="form-group m-5">
      <label for="book_page">book page:</label>
      <input type="text" class="form-control" id="book_page" value="12" require />
   </div>
   <!-- count isbn -->
   <div class="form-group m-5">
      <label for="book_isbn">book isbn:</label>
      <input type="text" class="form-control" id="book_isbn" value="23453245" require />
   </div>
   <!-- image as url -->
   <div class="form-group m-5">
      <label for="book_image">book image:</label>
      <input type="text" class="form-control" id="book_image" value="https://s3.amazonaws.com/AKIAJC5RLADLUMVRPFDQ.book-thumb-images/allen.jpg" require />
   </div>

   <!--time published-->
   <div class="form-group m-5">
      <label for="book_published_date">book published date:</label>
      <input type="date" class="form-control" id="book_published_date" value="2024-08-01" require />
   </div>


   <div class="form-group m-5">
      <label for="book_short_desc">book short description:</label>
      <textarea class="form-control" id="book_short_desc" rows="3"  value="" require>sfgnvoeprxiguwfers</textarea>
   </div>


   <div class="form-group m-5">
      <label for="book_long_desc">book long description:</label>
      <textarea class="form-control" id="book_long_desc" rows="5"  value="" require>sfgnvoeprxiguwfers</textarea>
   </div>



   <!-- published -->
   <div class="form-group m-5">
      <label for="book_status">Book status: </label>
      <select class="form-control" id="book_status" require>
         <?php
         foreach ($status_publish as $val) {
               echo ' <option value="' . $val . '"  >' . $val . '</option>';
         }
         ?>
      </select>
   </div>







   <!-- authors -->
   <div class="form-group m-5">
      <label for="author">Authors: </label>
      <select multiple class="form-control" id="author">
         <?php

         foreach ($book_has_author as $val) {
               echo ' <option value="' . $val['author_id'] . '"  >' . $val['author_name'] . '</option>';
         }
         ?>
      </select>
   </div>


   <!-- categories -->
   <div class="form-group m-5">
      <label for="category">Category: </label>
      <select multiple class="form-control" id="category">
         <?php

         foreach ($book_has_category as $val) {
               echo ' <option value="' . $val['category_id'] . '"  >' . $val['category_name'] . '</option>';
         }
         ?>
      </select>
   </div>
   <button class="btn btn-danger" id="btn_add" type="button">Add</button>
</form>



<script>
   const btn_add = document.querySelector('#btn_add');
   

  // add
  btn_add.addEventListener('click', () => {
      // url
      // http://localhost:20080/admin/edit/
      const body = {};
      let str = '?';
      let selectCategory = document.getElementById('category');
      let selectedCategoryValues = Array.from(selectCategory.selectedOptions).map(option => option.value);
      console.log(selectedCategoryValues);
      body['category'] = selectedCategoryValues;
      str += 'category=' + JSON.stringify(selectedCategoryValues);

      let selectAuthor = document.getElementById('author');
      let selectedAuthorValues = Array.from(selectAuthor.selectedOptions).map(option => option.value);
      console.log(selectedAuthorValues);
      body['author'] = selectedAuthorValues;
      str += '&author=' + JSON.stringify(selectedAuthorValues);

      const all_field = document.querySelectorAll('[require]');
      all_field.forEach(e => {

         body[e.id] = e.value;
         str += `&${e.id}=${e.value}`;
      });

      console.log(str);
      fetch('http://localhost:20080/admin/add/' + str, {
         // method: 'POST',
         // body: JSON.stringify(body)
      }).then(e => e.json()).then(res => window.location.replace("http://localhost:20080/admin/card/?id=" + res));

   });
</script>