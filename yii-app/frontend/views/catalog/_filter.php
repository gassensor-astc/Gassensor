<?php

/* @var $this yii\web\View */

/* @var $model common\models\search\ProductSearch */

use common\models\Gaz;
use common\models\GazGroup;
use common\models\Manufacture;
use common\models\MeasurementType;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\base\DynamicModel;

/* @var $request \yii\web\Request */
$req = Yii::$app->request;

?>

<?php $form = ActiveForm::begin(['method' => 'get', 'action' => '/catalog', 'options' => ['class' => 'pt-2']]); ?>
<input type="hidden" name="scroll" value="">
<div style="height: 2px;"></div>
<?= $form->field($model, 'manufacture_id')->dropDownList(
    Manufacture::getDropDownData(true),
    ['class' => 'form-select', 'options' => Manufacture::manufactureOption2($model)])->label(false)
?>

<?= $form->field($model, 'gaz_id')->dropDownList(
    Gaz::getDropDownData(true),
    ['class' => 'form-select', 'options' => Gaz::gazOption2($model)])->label(false)
?>

<?php if (0): ?>
    <?= $form->field($model, 'gaz_group_id')->dropDownList(
        GazGroup::getDropDownData(true),
        ['class' => 'form-select', 'options' => ['' => ['label' => 'выберите']]])
    ?>
<?php endif; ?>

<?= $form->field($model, 'measurement_type_id')->dropDownList(
    MeasurementType::getDropDownData(true),
    ['class' => 'form-select', 'options' => MeasurementType::measurementTypeOption2($model)])->label(false)
?>

<?php if (0): ?>
    <div class="mb-3 border p-1">
        <label>Разрешение</label>

        <div class="row g-1">
            <div class="col">
                <?= $form->field($model, 'resolution_from')->input('number') ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'resolution_to')->input('number') ?>
            </div>
        </div>
    </div>
<?php endif; ?>


<div class="mb-2" style="margin-top: -8px;">
    <div class="row g-1">
        <div class="col" style="min-width: 190px; vertical-align: middle; line-height: 34px;">
            <label>Время отклика, до (сек.)</label>
        </div>
        <div class="col">
            <?= $form->field($model, 'response_time_to')->input('number', ['style' => 'max-width: 100px;'])->label(false) ?>
        </div>
    </div>
</div>

<?php
echo $form->field($model, 'selectedSignalTypes', [
        'options' => [
            'style' => 'margin-top: -10px;',
            'class' => 'mb-3'
        ]
    ])->widget(Select2::classname(), [
    'data' => $model->getAvailableSignalTypes(),
    'options' => [
        'placeholder' => 'Выходной сигнал',
        'multiple' => true,
        'style' => 'margin-top: -10px;'
    ],
    'pluginOptions' => [
        'allowClear' => true
    ],
    'toggleAllSettings' => [
        'selectLabel' => '',
        'unselectLabel' => '',
    ],
])->label(false);
?>

<?php if (0): ?>
    <div class="mb-3 border p-1">
        <label>Энергопотребление</label>

        <div class="row g-1">
            <div class="col">
                <?= $form->field($model, 'energy_consumption_from')->input('number') ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'energy_consumption_to')->input('number') ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="mb-3">
    <?php if ($req->get('ProductSearch')): ?>
        <div class="row g-1">
            <div class="col-8">
                <?= Html::submitButton('Поиск', ['class' => 'btn-form w-100']) ?>
            </div>
            <div class="col-4">
                <a title="Сброс фильтров" href="<?= Url::to(['/products']) ?>" class="btn-form w-100"><i
                            class="fa fa-fw fa-file-o" style="margin-left: 0;"></i></a>

            </div>
        </div>
    <?php else: ?>
        <?= Html::submitButton('Поиск', ['class' => 'btn-form w-100']) ?>
    <?php endif; ?>
</div>

<?php ActiveForm::end(); ?>
