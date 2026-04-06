<?php
/* @var $this yii\web\View */

use common\models\Seo;
use common\models\Setting;
use common\helpers\StringHelpers;
use yii\widgets\LinkPager;

$seo = Seo::findOne(['type' => Seo::TYPE_PAGE_REMAINS]);
$seo->registerMetaTags($this);
if (trim((string) ($seo->description ?? '')) === '') {
    $this->registerMetaTag(['name' => 'description', 'content' => 'Наличие датчиков и сенсоров на складе компании Газсенсор.']);
}

$this->params['breadcrumbs'][] = $this->title;

// Номер страницы для H1 остатков.
// /remains и /remains?page=1 считаются первой страницей (без суффикса),
// /remains?page=2 → Страница 2 и т.д.
$req = Yii::$app->request;
$page = 1;
$pageParam = $req->get('page', null);
if ($pageParam !== null && $pageParam !== '') {
    $p = (int)$pageParam;
    $page = $p <= 1 ? 1 : $p;
} else {
    $p = (int)$pages->page; // 0-based
    $page = $p + 1;
    if ($page <= 1) {
        $page = 1;
    }
}

$remainsH1 = $seo->h1 ?? $this->title;
if ($page > 1 && $remainsH1 !== null && $remainsH1 !== '') {
    if (!preg_match('/,?\s*Страница\s+\d+$/u', $remainsH1)) {
        $remainsH1 = rtrim($remainsH1, " \t\n.") . ', Страница ' . $page;
    }
}

$hostInfo = $req->hostInfo;
$canonicalUrl = $hostInfo . '/remains';
if ($page > 1) {
    $canonicalUrl .= '?page=' . $page;
}
$this->params['customCanonical'] = true;
$this->params['canonicalUrl'] = $canonicalUrl;

$filename = './upload/' . Setting::getSensorsList();
$filename_dwnld = '/upload/sensors_list.xlsx';
?>
<style>
    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #4c5d8d;
        border-color: #4c5d8d;
    }
    .page-link {
        position:relative;
        display:block;
        color:#4c5d8d;
        text-decoration:none;
        background-color:#fff;
        border:1px solid #dee2e6;
        transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out
    }
</style>
<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> container'>
    <h1><?= $remainsH1 ?></h1>

    <div class="row">
        <div class="col-12 col-sm-12">

            <?php if (file_exists($filename)): ?>

            <p><a class="share" target="_blank" href="<?= $filename_dwnld ?>">Скачать список доступной продукции в формате Excel</a> (<?= StringHelpers::humanFilesize(filesize($filename),0)?>)</p>

            <?php endif ?>

            <table class="table  table-striped table-bordered">
                <thead>
                <tr>
                    <th style="text-align:center;">Позиция</th>
                    <th style="text-align:center;">Газ</th>
                    <th style="text-align:center;" class="text-nowrap">Количество</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($sensors ?? [] as $sensor): ?>
                    <tr>
                        <td>
                            <?php if ($sensor->link): ?>
                                <a href="<?= $sensor->link ?>"><?= $sensor->name ?></a>
                            <?php else: ?>
                                <?= $sensor->name ?>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center;"><?= $sensor->name2 ?></td>
                        <td style="text-align:right;"><?= $sensor->count ?></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        </div>

        <div class="col-12 mt-3">
            <?= LinkPager::widget([
                    'pagination' => $pages,
            ]); ?>
        </div>
    </div>
</div>
