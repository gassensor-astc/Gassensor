<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\models\Seo;

$seo = Seo::findOne(['type' => Seo::TYPE_PAGE_NEWS]);
$seo->registerMetaTags($this);
// Если Description в SEO пустой — выводим meta description из заголовка страницы
if (trim((string) ($seo->description ?? '')) === '') {
    $fallback = trim($seo->h1 ?? $seo->title ?? $this->title ?? '');
    if ($fallback !== '') {
        $this->registerMetaTag(['name' => 'description', 'content' => $fallback]);
    }
}

$this->params['breadcrumbs'][] = $this->title;

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
    <h1><?= $seo->h1 ?></h1>

    <div id="contentSection">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <?= $this->render('_news-item', ['model' => $model]) ?>
        <?php endforeach; ?>

        <?= $listView->renderPager() ?>

    </div>

</div>


