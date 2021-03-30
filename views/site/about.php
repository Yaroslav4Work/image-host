<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'О приложении';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2 class="text-center jumbotron">
        Данное приложение является тестовым заданием от <a href="https://banki.shop" class="text-success">banki.shop</a>
    </h2>
    <h3 class="text-center">
        Оно является прототипом
        <span class="text-uppercase">
            <strong class="text-success">хостинга изображений</strong>
        </span>.
    </h3>

</div>
