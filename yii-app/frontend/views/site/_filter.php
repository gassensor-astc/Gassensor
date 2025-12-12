<?php

/* @var $this yii\web\View */
/* @var $model common\models\search\ProductSearch */

use common\models\Gaz;
use common\models\Manufacture;
use common\models\MeasurementType;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\select2\Select2;

?>

<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => '/catalog',
    'fieldConfig' => [
        'template' => "<div class=\"col\">{input}\n{hint}\n{error}</div>",
        'options' => ['tag' => false,]
    ],
]); ?>

    <div class="row justify-form-middle">
        <div class="col-xl-5 col-lg-12">
            <div class="row align-items-center">
                <?= $form->field($model, 'manufacture_id')->dropDownList(
                    Manufacture::getDropDownData(true),
                    ['class' => 'form-select', 'style' => 'min-width: 40px', 'options' => Manufacture::manufactureOption2($model)])->label(false)
                ?>

                <?= $form->field($model, 'gaz_id')->dropDownList(
                    Gaz::getDropDownData(true),
                    ['class' => 'form-select', 'style' => 'min-width: 40px', 'options' => Gaz::gazOption2($model)])
                ?>

                <?= $form->field($model, 'measurement_type_id')->dropDownList(
                    MeasurementType::getDropDownData(true),
                    ['class' => 'form-select', 'style' => 'min-width: 40px', 'options' => MeasurementType::measurementTypeOption2($model)])
                ?>
            </div>
        </div>

        <div class="col-xl-7 col-lg-12">
            <div class="row align-items-center">
                <div class="col-4">
                    <label class="float-end" style="font-size: 15px">Время отклика, до (сек.)</label>
                </div>

                <?php
                /*$form->field($model, 'response_time_from')->input('number', ['style' => 'min-width: 40px; max-width: 225px;']) */
                ?>

                <?= $form->field($model, 'response_time_to')->input('number', ['style' => 'min-width: 40px; max-width: 105px;']) ?>

                <?php
                echo $form->field($model, 'selectedSignalTypes', [
                    'options' => [
                        'style' => 'margin-left: -16px;',
                        'class' => 'col',
                    ]
                ])->widget(Select2::classname(), [
                    'data' => $model->getAvailableSignalTypes(),
                    'options' => [
                        'placeholder' => 'Выходной сигнал',
                        'multiple' => true,
                        'style' => 'margin-left: -16px;',
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

                <div class="col-1">
                    <?= Html::submitButton('Поиск', ['class' => 'btn-form float-end']) ?>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>
<script>
    (function() {
        let maInt = setInterval(()=>{
            let elem1 = document.querySelector('.select2-container');
            if (elem1) {
                elem1.style.marginLeft = '-80px';
                elem1.style.maxWidth = '210px';
                elem1.style.minWidth = '210px';
                clearInterval(maInt);
                maInt = null;
            }
        }, 100);
    })()
</script>
