<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */

$this->title = 'Хостинг Изображений';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">
        <div class="container-fluid">

            <!-- Отрисовываем в том случае, если присутствует хотя бы 1 изображение -->
            <?php if (count($images) > 0) { ?>

                <div class="row d-flex justify-content-between align-items-center bg-secondary p-4">
                    <div class="col-6 text-left">
                        <h3 class="m-0">Сортировка</h3>
                    </div>
                    <div class="col-6 text-right d-block-in d-flex justify-content-end align-items-center mx-2-in">
                        <?= $sort->link('name') . ' | ' . $sort->link('upload_datetime');?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 my-4 text-success">
                        <!-- Адаптивная подсказка -->
                        <h4 class="d-none d-sm-block text-center">Для просмотра дополнительной информации, кликните на изображение</h4>
                        <h4 class="d-none d-md-block text-center">Для просмотра дополнительной информации, наведите курсор на
                            изображение</h4>
                    </div>
                </div>

                <?php
                /* При данной инкрементации (по 3) удобнее всего отрисовывать (при кол-ве выводимых изображений, равном 9 на страницу) */
                for ($i = 0;
                     $i < count($images);
                     $i += 3) {
                if ($i < count($images)) { ?>
                    <div class="row d-flex flex-wrap justify-content-center bg-secondary <?= $i == 0 ? 'pt-4' : ($i == 6 ? 'pb-4' : '') ?>">
                        <?php
                        for ($k = $i;
                             $k < $i + 3;
                             $k++) {
                            if ($k < count($images)) { ?>
                                <div class="col-sm-12 col-md-3 card">
                                    <!-- Хедер и футер карточки изначально скрыты и показываются, только при наведении (на моб. устройстве - клике) -->
                                    <h3 class="card-header text-center"><?= $images[$k]->name ?></h3>
                                    <img
                                            src="<?php
                                            /* Задаем полный путь и путь до превью изображения */
                                            $img_thumb_path = '/uploads/' . $images[$k]->name . '_thumb.' . $images[$k]->extension;
                                            $origin_path = Url::base(true) . '/uploads/' . $images[$k]->name . '.' . $images[$k]->extension;
                                            /* Проверяем на наличие и выводим файл превью, иначе оригинал изображения */
                                            if (file_exists(Yii::getAlias('@webroot') . $img_thumb_path)) {
                                                echo Url::base(true) . $img_thumb_path;
                                            } else {
                                                echo $origin_path;
                                            }
                                            ?>"
                                            alt="Превью изображения: <?= $images[$k]->name ?>"
                                            width="200"
                                            height="200">
                                    <!-- В футере расположена дата и время загрузки изображения, а так же кнопка для перехода на оригинал -->
                                    <p class="card-footer small text-center">
                                        <a href="<?= $origin_path ?>" class="btn btn-success d-block">Посмотреть оригинал</a>
                                        <span class="d-block"><?= $images[$k]->upload_datetime ?></span>
                                    </p>
                                </div>
                            <?php }
                        }
                } ?>
                    </div>
                        <?php }
                        } else { ?>
                            <!-- Если на данный момент не существует ни 1 изображения, оповещаем об этом пользователя -->
                            <div class="row">
                                <div class="col-12 bg-danger p-4">
                                    <h2 class="text-center my-4 text-danger">
                                        На данный момент, изображения в системе отсутствуют!
                                    </h2>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Пагинация -->
                    <?= LinkPager::widget(['pagination' => $pagination]) ?>

                </div>
    </div>
