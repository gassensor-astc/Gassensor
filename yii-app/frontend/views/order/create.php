<?php
/* @var $this yii\web\View */
/* @var $model common\models\Order */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => '/catalog'];
$this->params['breadcrumbs'][] = ['label' => 'Корзина', 'url' => '/cart'];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> container'>
     <h1><?= $this->title ?></h1>

        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
        ]) ?>

        <div class="row">
            <div class="col-sm-4">
              <?= $form->field($model, 'name')->textInput()->label('ФИО получателя <span style="color: red">*</span>') ?>
            </div>
            <div class="col-sm-4">
              <?= $form->field($model, 'email')->textInput()->label('Email <span style="color: red">*</span>') ?>
            </div>
            <div class="col-sm-4">

              <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
                  'mask' => '+7 (999) 999-99-99',
              ])->label('Контактный телефон <span style="color: red">*</span>') ?>

            </div>
        </div>

        <div class="row mt-2">
            <div class="col-sm-6">
              <?= $form->field($model, 'delivery_info')->textarea(['required' => true])->label('Адрес доставки <span style="color: red">*</span>') ?>
            </div>
            <div class="col-sm-6">
              <?= $form->field($model, 'comment')->textarea()->label('Комментарий к заказу') ?>
            </div>
        </div>
        <div class="mt-2">
            <span style="color: red">*</span> - обязательные для заполнения поля
        </div>
          <?= Html::submitButton('Оформить заказ', ['class' => 'btn btn-primary mt-3', 'name' => 'login-button']) ?>

        <?php ActiveForm::end(); ?>

</div>


