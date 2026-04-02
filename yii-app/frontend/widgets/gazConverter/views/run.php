<?php
/* @var $this yii\web\View */

use common\models\Gaz;
use frontend\assets\AppAsset;
use frontend\widgets\gazConverter\GazConverterForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model = new GazConverterForm;
$model->corvertType = GazConverterForm::CONVERT_FROM_PPM;

$this->registerJsFile('lib/yii2AjaxRequest.js', ['depends' => AppAsset::class]);
$this->registerJs(<<<JS
$(document).on('input', '.widget-gaz-converter input[type="text"]', function () {
    var value = $(this).val();
    if (typeof value === 'string' && value.indexOf(',') !== -1) {
        $(this).val(value.replace(/,/g, '.'));
    }
});

$(document).on('beforeSubmit', '.widget-gaz-converter form', function () {
    $(this).find('input[type="text"]').each(function () {
        var value = $(this).val();
        if (typeof value === 'string' && value.indexOf(',') !== -1) {
            $(this).val(value.replace(/,/g, '.'));
        }
    });
});
JS);

?>

<div class='widget-gaz-converter card p-2 bg-light'>

    <?php if ($title = $this->context->title): ?>
      <h3 class="text-center"><?= $title ?></h3>
    <?php endif; ?>

        <?php $form = ActiveForm::begin(['action' => ['site/gaz-convert']]) ?>

          <?= $form->field($model, 'gazId')->dropDownList(Gaz::getDropDownData(), ['class' => 'form-select']) ?>

          <div class="row g-0 my-3">
          	<div class="col-4 pt-2">
              <?= $form->field($model, 'corvertType')->radioList(GazConverterForm::getTypes(), [
                  'separator' => '<div style="margin-bottom: -0.25rem;">&nbsp;</div>',
                  //'separator' => '<div class="my-1">&nbsp;</div>',
                  //'style' => 'margin-top: 2.2rem',
              ])->label(false) ?>
            </div>
          	<div class="col-8">
              <?= $form->field($model, 'ppm')->textInput([
                      'class' => 'mt-1',
                      'placeholder' => $model->attributeLabels()['ppm'],
                      'data-type' => 'covertFromPpm',
                  ])->label(false) ?>

              <?= $form->field($model, 'mg')->textInput([
                      'class' => 'mt-1',
                      'placeholder' => $model->attributeLabels()['mg'],
                      'data-type' => 'covertFromMg',
                  ])->label(false) ?>

              <?= $form->field($model, 'obd')->textInput([
                        'class' => 'mt-1',
                        'placeholder' => $model->attributeLabels()['obd'],
                        'data-type' => 'covertFromObd',
                  ])->label(false) ?>
            </div>
          </div>

          <?= Html::submitButton('Конвертировать', [
              'class' => 'btn btn-primary mt-1 w-100',
          ]) ?>

        <?php ActiveForm::end(); ?>

</div>
