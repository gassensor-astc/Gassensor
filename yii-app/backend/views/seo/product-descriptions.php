<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var string $id */
/* @var string $name */
/* @var string $filter */
/* @var int $withoutTitle */
/* @var int $withoutH1 */
/* @var int $withoutDescription */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap5\ActiveForm;

$this->title = 'SEO-описания товаров (' . $dataProvider->totalCount . ')';
$this->params['breadcrumbs'][] = ['label' => 'SEO', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row-fluid">
    <div class="col">
        <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false">
            <div>
                <h1><?= Html::encode($this->title) ?></h1>

                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['product-descriptions'],
                    'options' => ['class' => 'seo-search-form mb-3'],
                ]); ?>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div>
                        <label class="form-label mb-0 me-1">ID товара</label>
                        <input type="text" name="id" class="form-control form-control-sm d-inline-block" style="width: 100px;" value="<?= Html::encode($id ?? '') ?>" placeholder="ID">
                    </div>
                    <div>
                        <label class="form-label mb-0 me-1">Название</label>
                        <input type="text" name="name" class="form-control form-control-sm d-inline-block" style="width: 220px;" value="<?= Html::encode($name ?? '') ?>" placeholder="Часть названия">
                    </div>
                    <div>
                        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary btn-sm']) ?>
                        <?php if ($id !== '' || $name !== '' || !empty($filter)): ?>
                            <?= Html::a('Сбросить', ['product-descriptions'], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

                <div class="seo-stats mb-3">
                    <span class="seo-stat-item me-3">Товары без Title: <?php
                        $n = $withoutTitle ?? 0;
                        if ($n > 0) {
                            echo Html::a($n, ['product-descriptions', 'id' => $id ?: null, 'name' => $name ?: null, 'filter' => 'no_title'], ['class' => 'seo-stat-link']);
                        } else {
                            echo $n;
                        }
                    ?></span>
                    <span class="seo-stat-item me-3">Товары без H1: <?php
                        $n = $withoutH1 ?? 0;
                        if ($n > 0) {
                            echo Html::a($n, ['product-descriptions', 'id' => $id ?: null, 'name' => $name ?: null, 'filter' => 'no_h1'], ['class' => 'seo-stat-link']);
                        } else {
                            echo $n;
                        }
                    ?></span>
                    <span class="seo-stat-item">Товары без Description: <?php
                        $n = $withoutDescription ?? 0;
                        if ($n > 0) {
                            echo Html::a($n, ['product-descriptions', 'id' => $id ?: null, 'name' => $name ?: null, 'filter' => 'no_description'], ['class' => 'seo-stat-link']);
                        } else {
                            echo $n;
                        }
                    ?></span>
                    <?php if ($filter): ?>
                        <?= Html::a('Сбросить фильтр', ['product-descriptions', 'id' => $id ?: null, 'name' => $name ?: null], ['class' => 'btn btn-outline-secondary btn-sm ms-2']) ?>
                    <?php endif; ?>
                </div>

                <div class="table-responsive" style="overflow-x: auto; overflow-y: visible;">
                    <table class="table table-bordered table-striped seo-products-table">
                        <thead>
                        <tr>
                            <th style="min-width: 135px;">Товар</th>
                            <th style="min-width: 340px;">Title / H1 / Description</th>
                            <th style="min-width: 300px;">Описание</th>
                            <th style="min-width: 300px;">Описание ИИ</th>
                            <th style="width: 120px;">Сохранить</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dataProvider->getModels() as $product): ?>
                            <?php $seo = $product->seo; ?>
                            <tr data-product-id="<?= $product->id ?>">
                                <td class="seo-product-cell">
                                    <div class="seo-cell-top">
                                        Товар (#<?= $product->id ?>)<br>
                                        <a href="<?= $product->url ?>" target="_blank"><?= Html::encode($product->name) ?></a><br>
                                        <span class="text-muted" style="font-size: 12px;">Создан: <?= $product->created_at ? Yii::$app->formatter->asDate($product->created_at) : '—' ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-2 seo-title-row">
                                        <label class="form-label mb-1">Title (<span class="js-count" data-for="title-<?= $product->id ?>">0</span>)</label>
                                        <input type="text" class="form-control js-seo-title js-count-input" data-count-id="title-<?= $product->id ?>" value="<?= Html::encode($seo->title ?? '') ?>">
                                    </div>
                                    <div class="mb-2 seo-title-row">
                                        <label class="form-label mb-1">H1 (<span class="js-count" data-for="h1-<?= $product->id ?>">0</span>)</label>
                                        <input type="text" class="form-control js-seo-h1 js-count-input" data-count-id="h1-<?= $product->id ?>" value="<?= Html::encode($seo->h1 ?? '') ?>">
                                    </div>
                                    <div>
                                        <label class="form-label mb-1">Description (<span class="js-count" data-for="desc-<?= $product->id ?>">0</span>)</label>
                                        <textarea class="form-control js-seo-description js-count-input" data-count-id="desc-<?= $product->id ?>" rows="3"><?= Html::encode($seo->description ?? '') ?></textarea>
                                    </div>
                                </td>
                                <td class="seo-opisanie-td">
                                    <div class="seo-opisanie-wrap">
                                        <label class="form-label mb-1">Описание (<span class="js-count" data-for="opis-<?= $product->id ?>">0</span>)</label>
                                        <textarea class="form-control js-seo-opisanie js-count-input" data-count-id="opis-<?= $product->id ?>" rows="6"><?= Html::encode($seo->opisanie ?? '') ?></textarea>
                                    </div>
                                </td>
                                <td class="seo-opisanie-td">
                                    <div class="seo-opisanie-wrap seo-opisanie-ai-wrap">
                                        <label class="form-label mb-1">Описание ИИ (<span class="js-count" data-for="opis-ai-<?= $product->id ?>">0</span>)</label>
                                        <textarea readonly class="form-control js-seo-opisanie-ai js-count-input" data-count-id="opis-ai-<?= $product->id ?>" rows="4"><?= Html::encode($seo->opisanie_ai ?? '') ?></textarea>
                                        <div class="seo-ai-buttons mt-1">
                                            <button type="button" class="btn btn-primary btn-sm js-generate-ai" data-product-id="<?= (int)$product->id ?>">Сгенерировать</button>
                                            <span class="js-generate-done text-success small align-middle" style="display: none;">Сгенерировано</span>
                                            <button type="button" class="btn btn-outline-secondary btn-sm js-copy-ai">Скопировать в буфер</button>
                                        </div>
                                    </div>
                                </td>
                                <td class="seo-save-td">
                                    <div class="seo-cell-center">
                                        <button class="btn btn-success btn-sm js-save-seo">Сохранить</button>
                                        <div class="mt-1 js-save-status text-muted" style="font-size: 12px;"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="seo-pagination-wrapper">
                    <?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$saveUrl = Url::to(['save-product-description']);
$generateAiUrl = Url::to(['generate-ai-description']);
$js = <<<JS
$('.js-save-seo').on('click', function () {
    const row = $(this).closest('tr');
    const productId = row.data('product-id');
    const status = row.find('.js-save-status');

    status.text('Сохранение...');

    const csrfParam = $('meta[name=csrf-param]').attr('content') || '_csrf-backend';
    const csrfToken = $('meta[name=csrf-token]').attr('content');

    const payload = {
        product_id: productId,
        title: row.find('.js-seo-title').val(),
        h1: row.find('.js-seo-h1').val(),
        description: row.find('.js-seo-description').val(),
        opisanie: row.find('.js-seo-opisanie').val(),
        opisanie_ai: row.find('.js-seo-opisanie-ai').val()
    };
    if (csrfParam && csrfToken) {
        payload[csrfParam] = csrfToken;
    }

    $.ajax({
        url: '{$saveUrl}',
        method: 'POST',
        dataType: 'json',
        data: payload,
        headers: csrfToken ? {'X-CSRF-Token': csrfToken} : {}
    }).done(function (data) {
        if (data.success) {
            status.text('Сохранено');
        } else {
            status.text('Ошибка');
        }
    }).fail(function () {
        status.text('Ошибка запроса');
    });
});

$('.js-generate-ai').on('click', function () {
    var btn = $(this);
    var row = btn.closest('tr');
    var productId = row.data('product-id');
    var textarea = row.find('.js-seo-opisanie-ai');
    var doneLabel = row.find('.js-generate-done');
    var origText = btn.text();
    doneLabel.hide();
    btn.prop('disabled', true).text('Генерация...');
    $.getJSON('{$generateAiUrl}', { product_id: productId })
        .done(function (data) {
            if (data.success) {
                textarea.val(data.opisanie_ai || '');
                updateCounts();
                doneLabel.show();
            } else {
                alert(data.error || 'Ошибка');
            }
        })
        .fail(function () {
            alert('Ошибка запроса');
        })
        .always(function () {
            btn.prop('disabled', false).text(origText);
        });
});

$('.js-copy-ai').on('click', function () {
    var btn = $(this);
    var text = btn.closest('tr').find('.js-seo-opisanie-ai').val() || '';
    if (!text) {
        alert('Нечего копировать');
        return;
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function () {
            var t = btn.text();
            btn.text('Скопировано');
            setTimeout(function () { btn.text(t); }, 1500);
        });
    } else {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'absolute';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand('copy');
            btn.text('Скопировано');
            setTimeout(function () { btn.text('Скопировать в буфер'); }, 1500);
        } catch (e) {}
        document.body.removeChild(ta);
    }
});

function updateCounts() {
    $('.js-count-input').each(function () {
        const id = $(this).data('count-id');
        const val = $(this).val() || '';
        $('.js-count[data-for="' + id + '"]').text(val.length);
    });
}

$(document).on('input', '.js-count-input', updateCounts);
updateCounts();
JS;

$this->registerJs($js);
?>
<style>
    .seo-products-table td,
    .seo-products-table th {
        vertical-align: top;
    }
    .seo-product-cell,
    .seo-save-td {
        position: relative;
        vertical-align: top;
        overflow: visible;
    }
    .seo-cell-center {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        transform: translateY(-50%);
        text-align: center;
    }
    .seo-cell-top {
        position: absolute;
        top: 8px;
        left: 12px;
        right: 0;
        text-align: left;
    }
    .seo-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .seo-title-row .form-label {
        min-width: 70px;
        margin: 0;
    }
    .seo-opisanie-td {
        vertical-align: top;
    }
    .seo-ai-buttons {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 6px;
    }
    .seo-ai-buttons .btn {
        white-space: nowrap;
    }
    .seo-stats {
        color: #333;
    }
    .seo-stat-link {
        font-weight: 600;
        text-decoration: none;
    }
    .seo-stat-link:hover {
        text-decoration: underline;
    }
    .table-responsive {
        overflow-x: auto;
        overflow-y: visible;
        position: relative;
    }
    .seo-products-table {
        position: relative;
    }
    .seo-pagination-wrapper {
        margin-top: 20px;
        position: relative;
        z-index: 100;
        clear: both;
    }
    .seo-pagination-wrapper .pagination {
        position: relative;
        z-index: 100;
        pointer-events: auto;
    }
    .seo-pagination-wrapper .pagination li {
        position: relative;
        z-index: 100;
    }
    .seo-pagination-wrapper .pagination a {
        position: relative;
        z-index: 100;
        pointer-events: auto;
        display: block;
    }
</style>
