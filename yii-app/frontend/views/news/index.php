<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\models\Seo;
use yii\helpers\Html;
use Yii;

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

// Номер страницы для H1 новостей.
// /news и /news?page=1 считаются первой страницей (без суффикса),
// /news?page=2 → Страница 2 и т.д.
$req = Yii::$app->request;
$page = 1;
$pageParam = $req->get('page', null);
if ($pageParam !== null && $pageParam !== '') {
    $p = (int)$pageParam;
    $page = $p <= 1 ? 1 : $p;
} elseif (isset($dataProvider->pagination)) {
    $p = (int)$dataProvider->pagination->page; // 0-based
    $page = $p + 1;
    if ($page <= 1) {
        $page = 1;
    }
}

$newsH1 = $seo->h1 ?? $this->title;
if ($page > 1 && $newsH1 !== null && $newsH1 !== '') {
    if (!preg_match('/,?\s*Страница\s+\d+$/u', $newsH1)) {
        $newsH1 = rtrim($newsH1, " \t\n.") . ', Страница ' . $page;
    }
}

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

    /* Фиксированная высота карточек новостей на странице /news */
    .news-index #contentSection {
        display: flex;
        flex-wrap: wrap;
    }

    .news-index .news-box {
        display: flex;
        padding: 8px;
        box-sizing: border-box;
    }

    .news-index .post-box {
        display: flex;
        flex-direction: column;
        height: 250px;
        width: 100%;
        padding: 8px 10px;
        margin-bottom: 8px;
        overflow: hidden;
    }

    .news-index .entry-media {
        flex: 0 0 auto;
        margin-bottom: 6px;
        text-align: center;
    }

    .news-index .inner-post {
        flex: 1 1 auto;
        padding: 6px 8px 8px;
        display: flex;
        align-items: flex-start;
    }

    .news-index .entry-summary {
        width: 100%;
    }

    .news-index .entry-summary p {
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
        line-height: 1.2;
    }
</style>
<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> container'>
    <h1><?= Html::encode($newsH1) ?></h1>

    <div id="contentSection">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <?= $this->render('_news-item', ['model' => $model]) ?>
        <?php endforeach; ?>

        <?= $listView->renderPager() ?>

    </div>

</div>


