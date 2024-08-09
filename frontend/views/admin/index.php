<?php

?>

<div class=" container row mx-auto p-5">
   <div class="col-12 row my-5">
      <a href="admin/create/" class="btn btn-info col-1">Create</a>
   </div>
   <?php foreach ($books as $row) { ?>
      <div class="p-1 col-4">
         <div class="card " >
            <div class="card-body">
               <h5 class="card-title">Title: <?= $row['book_title'] ?></h5>
               <h6 class="card-subtitle mb-2 text-muted">book_id: <?= $row['book_id'] ?></h6>
               <a href=<?= 'admin/card/?id=' . $row['book_id'] ?> class="btn btn-primary">Edit</a>
            </div>
         </div>
      </div>
   <?php } ?>



</div>