<?php

use PhpParser\Node\Stmt\Foreach_;

// var_export($books['authors']);

$book_has_author = explode(',', $books['authors']);
$book_has_category = explode(',', $books['categories']);


// тут может быть массив значений
$status_publish = ['PUBLISH', 'NON PUBLISH'];


?>


<form class="border m-5 p-5">
   <!-- title -->
   <div class="form-group m-5">
      <label for="book_title">book title:</label>
      <input type="text" class="form-control" id="book_title" value='<?= $books['book_title'] ?>' require />
   </div>

   <div class="form-group m-5" hidden>
      <label for="book_id">book_id:</label>
      <input type="text" class="form-control" id="book_id" value='<?= $books['book_id'] ?>' require />
   </div>
   <!-- count page -->
   <div class="form-group m-5">
      <label for="book_page">book page:</label>
      <input type="text" class="form-control" id="book_page" value='<?= $books['book_page'] ?>' require />
   </div>
   <!-- count isbn -->
   <div class="form-group m-5">
      <label for="book_isbn">book isbn:</label>
      <input type="text" class="form-control" id="book_isbn" value='<?= $books['book_isbn'] ?>' require />
   </div>
   <!-- image as url -->
   <div class="form-group m-5">
      <label for="book_image">book image:</label>
      <input type="text" class="form-control" id="book_image" value='<?= $books['book_image'] ?>' require />
   </div>

   <!--time published-->
   <div class="form-group m-5">
      <label for="book_published_date">book published date:</label>
      <input type="date" class="form-control" id="book_published_date" value='<?= date('Y-m-d', $books['book_published_date']) ?>' require />
   </div>


   <div class="form-group m-5">
      <label for="book_short_desc">book short description:</label>
      <textarea class="form-control" id="book_short_desc" rows="3" require><?= $books['book_short_desc'] ?></textarea>
   </div>


   <div class="form-group m-5">
      <label for="book_long_desc">book long description:</label>
      <textarea class="form-control" id="book_long_desc" rows="5" require><?= $books['book_long_desc'] ?></textarea>
   </div>



   <!-- published -->
   <div class="form-group m-5">
      <label for="book_status">Book status: </label>
      <select class="form-control" id="book_status" require>
         <?php
         foreach ($status_publish as $val) {
            if ($val == $books['book_status']) {
               echo ' <option value="' . $val . '" selected >' . $val . '</option>';
            } else {
               echo ' <option value="' . $val . '"  >' . $val . '</option>';
            }
         }
         ?>
      </select>
   </div>







   <!-- authors -->
   <div class="form-group m-5">
      <label for="author">Authors: </label>
      <select multiple class="form-control" id="author">
         <?php

         foreach ($authors as $val) {
            if (in_array($val['author_name'], $book_has_author)) {
               echo ' <option value="' . $val['author_id'] . '" selected >' . $val['author_name'] . '</option>';
            } else {
               echo ' <option value="' . $val['author_id'] . '"  >' . $val['author_name'] . '</option>';
            }
         }
         ?>
      </select>
   </div>


   <!-- categories -->
   <div class="form-group m-5">
      <label for="category">Category: </label>
      <select multiple class="form-control" id="category">
         <?php

         foreach ($category as $val) {
            if (in_array($val['category_name'], $book_has_category)) {
               echo ' <option value="' . $val['category_id'] . '" selected >' . $val['category_name'] . '</option>';
            } else {
               echo ' <option value="' . $val['category_id'] . '"  >' . $val['category_name'] . '</option>';
            }
         }
         ?>
      </select>
   </div>
   <button class="btn btn-danger" id="btn_delete" type="button">Delete</button>
   <button class="btn btn-info" id="btn_edit" type="button">Save</button>
</form>



<script>
   const book_id = document.getElementById('book_id').value;
   const btn_delete = document.querySelector('#btn_delete');
   const btn_edit = document.querySelector('#btn_edit');
   
   // delete
   btn_delete.addEventListener('click', () => {
      fetch('http://localhost:20080/admin/delete/?id=' + book_id).then(e => e.json()).then(i =>    window.location.replace("http://localhost:20080/admin"));
      
   });



  // edit
   btn_edit.addEventListener('click', () => {
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

      // console.log(str);
      fetch('http://localhost:20080/admin/edit/' + str, {
         // method: 'POST',
         // body: JSON.stringify(body)
      }).then(e => e.json()).then(res => console.log(res));

   });
</script>