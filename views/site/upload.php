<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Загрузка изображений';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-upload">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    /* Если данная страница отрисовывается после отправки формы, то выводим пользователю названия (не)загруженных файлов */
    if (count($uploaded) > 0 or count($not_uploaded) > 0) {
        ?>
        <div class="container-fluid my-4">
            <div class="row d-flex">

                <?php if (count($not_uploaded) > 0) {
                    ?>
                    <div class="col-12 col-md-6 bg-danger p-4 flex-auto">
                        <h3 class="text-danger">Данные файлы не были загружены: </h3>
                        <ul class="text-danger small list-unstyled">
                            <?php foreach ($not_uploaded as $item) {
                                ?>
                                <li class="py-1 px-2"><?= $item ?></li> <?php
                            } ?>
                        </ul>
                    </div>
                    <?php
                } ?>

                <?php if (count($uploaded) > 0) {
                    ?>
                    <div class="col-12 col-md-6 bg-success p-4 flex-auto">
                        <h3 class="text-success">Данные файлы были успешно загружены: </h3>
                        <ul class="text-success small list-unstyled">
                            <?php foreach ($uploaded as $item) {
                                ?>
                                <li class="py-1 px-2"><?= $item ?></li> <?php
                            } ?>
                        </ul>
                    </div>
                    <?php
                } ?>

            </div>
        </div> <?php
    }
    ?>

    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-12 bg-secondary p-4">
                <?php $form = ActiveForm::begin(['id' => 'upload-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])
                    ->hint('Выберите до 5 изображений')
                    ->label('Загрузить изображения')
                    ->error(['message' => 'Данный тип файлов не поддерживается, пожалуйста выберите до 5 изображений!'])
                ?>

                <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success d-block ml-auto']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

