<?php

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelSeo common\models\Seo */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$js = <<<JS
 // Автоматическая генерация slug из title (только если slug пустой)
 $("#news-title").on("change keyup input", function () {
     if ($("#news-slug").val()) {
         return;
     }
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
 
 // Также транслитерация при ручном вводе slug
 $("#news-slug").on("change", function () {
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

<?php
    $content = (string)($model->content ?? '');
    $pdfLinks = [];
    if ($content !== '') {
        if (preg_match_all('~(?:href|src)\s*=\s*(?:["\']?)([^"\'>\s]+\.pdf(?:\?[^"\'>\s]*)?)~i', $content, $m)) {
            $pdfLinks = array_values(array_unique($m[1]));
        } elseif (preg_match_all('~(https?://[^\s"\']+\.pdf(?:\?[^\s"\']*)?|/[^"\'>\s]+\.pdf(?:\?[^"\'>\s]*)?)~i', $content, $m)) {
            $pdfLinks = array_values(array_unique($m[1]));
        }
    }
?>

<h3>PDF в контенте</h3>
<?php if (!empty($pdfLinks)): ?>
    <ul style="margin-bottom: 16px;">
        <?php foreach ($pdfLinks as $link): ?>
            <?php
                $path = $model->resolveContentFilePath($link);
                $size = $path && is_file($path) ? filesize($path) : null;
                $sizeText = $size !== null ? number_format($size / 1024, 1, '.', ' ') . ' KB' : 'файл не найден';
            ?>
            <li>
                <?= Html::a(Html::encode($link), $link, ['target' => '_blank', 'rel' => 'noopener']) ?>
                <span class="text-muted" style="margin-left: 8px;"><?= Html::encode($sizeText) ?></span>
                <?= Html::a(
                    'Удалить',
                    ['delete-content-file', 'id' => $model->id, 'url' => rawurlencode($link)],
                    [
                        'class' => 'btn btn-danger btn-xs',
                        'style' => 'margin-left: 8px;',
                        'data-method' => 'post',
                        'data-confirm' => 'Удалить PDF и убрать ссылку из контента?',
                    ]
                ) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="text-muted" style="margin-bottom: 16px;">В контенте ссылки на PDF не найдены.</div>
<?php endif; ?>

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

$this->registerJs("$(document).on('click', '.js-insert-news-image', function (e) {
    e.preventDefault();
    var url = $(this).data('url');
    if (!url) return;
    var html = '<p><img src=\"' + url + '\" alt=\"\" /></p>';
    if (window.CKEDITOR && CKEDITOR.instances && CKEDITOR.instances['news-content']) {
        CKEDITOR.instances['news-content'].insertHtml(html);
    } else {
        var ta = $('#news-content');
        if (ta.length) {
            ta.val((ta.val() || '') + \"\\n\" + html + \"\\n\");
        }
    }
});", View::POS_END);

?>


