<?php
/* @var $this yii\web\View */
/* @var $model common\models\Seo */
/* @var $form yii\widgets\ActiveForm  */
?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Title') ?>

<?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'breadcrumb_text')->textInput(['maxlength' => true])->label('Текст хлебной крошки') ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'opisanie')->textarea(['rows' => 6])->label('Описание товара') ?>

<?= $form->field($model, 'url_canonical')->textInput(['maxlength' => true])->label('Canonical URL') ?>
