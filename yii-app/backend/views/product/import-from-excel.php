<?php

/* @var $model \yii\base\DynamicModel */
/* @var $form yii\widgets\ActiveForm|null */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Импорт товаров из Excel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Товары'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

?>

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">
                    <h1><?= Html::encode($this->title) ?></h1>

                    <p>Формат файла: .xlsx, .xls, .csv, .ods. Первая строка — заголовок.</p>

                    <?= $form->field($model, 'file')->fileInput() ?>

                    <?= Html::submitButton(Yii::t('app', 'Импортировать'), ['class' => 'btn btn-success']) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </article>
</div>
