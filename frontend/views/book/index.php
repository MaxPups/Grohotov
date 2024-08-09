<?php

use yii\helpers\Html;

$this->title = 'Книги';
$currentUrl = Yii::$app->request->url;
$imgUnknown = 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Unknown_person.jpg/434px-Unknown_person.jpg';
if ($error) {
    echo $error;
    return 1;
}

?>


<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row gap-5">
        <?php foreach ($books as $row) { ?>

            <div class="col-md-3">
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <img src="<?= Html::encode($row['book_image'] ? $row['book_image']  : $imgUnknown) ?>" class="card-img-top" alt="<?= Html::encode($row['book_title']) ?>">
                        <h5 class="card-title"><?= Html::encode($row['book_title']) ?></h5>
                        <p class="card-text"> Categories: <?= Html::encode($row['categories']) ?></p>
                        <p class="card-text"> Author: <?= Html::encode($row['authors']) ?></p>
                        <a href=<?= 'card/?id=' . $row['book_id'] ?> class="btn btn-primary">Перейти</a>

                    </div>
                </div>
            </div>

        <?php } ?>
    </div>
</div>