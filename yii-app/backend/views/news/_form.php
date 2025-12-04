<?php

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelSeo common\models\Seo */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$js =
        <<<JS
 $("#news-slug").on("change keyup input click", function () {
     if (this.value.length >= 2) {
         let q = this.value;
         let request = $.ajax({
             url: '/backend/ajax/slug?q=' + q,
                method: "GET",
                dataType: "json"
         });
         request.done(function (data) {
             if (data.slug != null && data.slug !== '') {
                 $("#news-slug").val(data.slug);
             }
         });
     }
  });
JS;

$this->registerJs($js, $this::POS_READY);
//echo '<pre>';var_dump($model);
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'date')->textInput()->label('Дата публикации') ?>
<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'content')->textarea(['rows' => '6', 'class' => 'form-control', 'maxlength' => true]) ?>

<h3>SEO</h3>

<?= $this->render('/seo/_sub-form', ['model' => $modelSeo, 'form' => $form]) ?>

<h2>Файлы</h2>

<?= $this->render('_files', ['model' => $model,]) ?>

<h3>Добавить</h3>

<?= $form->field($model, 'uploadFile')->fileInput() ?>

<div class="form-actions">
    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end();

$this->registerJsFile('/admin/js/plugin/ckeditor/ckeditor.js', ['position' => View::POS_END]);

$this->registerJs("$(document).ready(function () {
    CKEDITOR.replace( 'news-content', {
    extraAllowedContent: 'img[title]',
    height: 380,
    startupFocus: true,
    filebrowserUploadUrl: '/upload.php',
    on: {
       instanceReady: function() {
            this.dataProcessor.htmlFilter.addRules( {
                elements: {
                    img: function( el ) {
                       el.attributes.title = el.attributes.alt;
                    }
                }
            });            
        }
    }
    });

CKEDITOR.config.allowedContent = true;CKEDITOR.config.extraAllowedContent = 'img[title]';CKEDITOR.config.removePlugins = 'spellchecker, about, save, newpage, print, templates, scayt, flash, pagebreak, smiley,preview,find'});", View::POS_END);


?>





