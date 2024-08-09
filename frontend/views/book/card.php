<?php

use yii\helpers\Html;

$imgUnknown = 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Unknown_person.jpg/434px-Unknown_person.jpg';

?>
<div class="jumbotron">
    <h1 class="display-4"><?= $books['book_title'] ?></h1>
    <img style="width: 18rem;" src="<?= Html::encode($books['book_image'] ? $books['book_image'] : $imgUnknown) ?>" class="card-img-top" alt="<?= Html::encode($books['book_title']) ?>">
    <p class="lead"><?= Html::encode($books['book_short_desc']) ?></p>
    <hr class="my-4">
    <p><?= Html::encode($books['book_long_desc']) ?></p>
    <p class="card-text"> Author: <?= Html::encode($books['authors']) ?></p>
    <p class="card-text"> Categories: <?= Html::encode($books['categories']) ?></p>
    <p class="card-text"> Кол-во страниц: <?= Html::encode($books['book_page'] ? $books['book_page'] : ' неизвестно ') ?></p>

    <div class="alert alert-success" role="alert">
        <?= $books['book_status'] ?>
    </div>
</div>