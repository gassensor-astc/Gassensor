<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\models\Seo;
use common\models\News;
use yii\helpers\Html;
use yii\helpers\Url;

$seo = Seo::findOne(['type' => Seo::TYPE_PAGE_NEWS]);
$seo->registerMetaTags($this);
if (trim((string) ($seo->description ?? '')) === '') {
    $fallback = trim($seo->h1 ?? $seo->title ?? $this->title ?? '');
    if ($fallback !== '') {
        $this->registerMetaTag(['name' => 'description', 'content' => $fallback]);
    }
}

$this->params['breadcrumbs'][] = $this->title;
$this->params['hideBreadcrumbs'] = true;

$req = Yii::$app->request;
$pagination = $dataProvider->pagination;
$page = 1;
$pageParam = $req->get('page', null);
if ($pageParam !== null && $pageParam !== '') {
    $p = (int) $pageParam;
    $page = $p <= 1 ? 1 : $p;
} elseif ($pagination !== false) {
    $p = (int) $pagination->page;
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

$hostInfo = $req->hostInfo;
$canonicalUrl = $hostInfo . '/news';
if ($page > 1) {
    $canonicalUrl .= '?page=' . $page;
}
$this->params['customCanonical'] = true;
$this->params['canonicalUrl'] = $canonicalUrl;

$pageCount = $pagination === false ? 1 : (int) $pagination->getPageCount();
$currentPage = $pagination === false ? 1 : ((int) $pagination->page + 1);
$recentNews = News::find()->orderBy('date DESC')->limit(5)->all();
$pageUrl = static function (int $targetPage): string {
    return $targetPage <= 1 ? Url::to(['/news']) : Url::to(['/news', 'page' => $targetPage]);
};
?>
<style>
    .news-index-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 8px 30px 50px;
    }

    .news-index-page__layout {
        display: flex;
        align-items: flex-start;
        gap: 60px;
    }

    .news-index-page__main {
        flex: 1 1 auto;
        min-width: 0;
    }

    .news-index-page__sidebar {
        width: 300px;
        flex: 0 0 300px;
        padding-top: 118px;
    }

    .news-index-page__header {
        margin: 0 0 44px;
    }

    .news-index-page__header h1 {
        display: inline-block;
        margin: 0;
        color: #0b3b5b;
        font-size: 40px;
        font-weight: 400;
        line-height: 1.2;
    }

    .news-index-page__header h1::after {
        content: "";
        display: block;
        width: 70px;
        height: 2px;
        margin-top: 25px;
        background: #65ad00;
    }

    .news-archive-item {
        margin-bottom: 60px;
    }

    .news-archive-item:last-of-type {
        margin-bottom: 0;
    }

    .news-index-page .news-archive-item__category {
        margin: 0 0 16px;
        font-size: 16px;
        line-height: 1.35;
        color: #f39c12;
    }

    .news-index-page .news-archive-item__category a {
        color: inherit;
        text-decoration: none;
    }

    .news-index-page .news-archive-item__title {
        margin: 0 0 18px;
        font-size: 30px;
        line-height: 1.2;
        font-weight: 500;
    }

    .news-index-page .news-archive-item__title a {
        color: #0b3b5b;
        text-decoration: none;
        transition: color .15s ease;
    }

    .news-index-page .news-archive-item__title a:hover {
        color: #65ad00;
    }

    .news-index-page .news-archive-item__meta {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 28px;
        font-size: 16px;
        color: #3f3f3f;
        line-height: 1.35;
    }

    .news-index-page .news-archive-item__author {
        color: #3f3f3f;
    }

    .news-index-page .news-archive-item__meta a {
        color: #b5b5b5;
        text-decoration: none;
    }

    .news-index-page .news-archive-item__content {
        margin-bottom: 30px;
        font-size: 16px;
        font-weight: 300;
        line-height: 1.8;
        letter-spacing: .02em;
        color: #3f3f3f;
    }

    .news-index-page .news-archive-item__content p {
        margin-bottom: 20px;
    }

    .news-index-page .news-archive-item__content p:last-child {
        margin-bottom: 0;
    }

    .news-index-page .news-archive-item__content img,
    .news-index-page .news-archive-item__content iframe,
    .news-index-page .news-archive-item__image img {
        display: block;
        max-width: 100%;
        height: auto;
    }

    .news-index-page .news-archive-item__image {
        margin: 0;
    }

    .news-index-page .news-archive-item__image img {
        width: 100%;
    }

    .news-archive-pagination {
        margin-top: 60px;
    }

    .news-archive-pagination__links {
        display: flex;
        flex-wrap: wrap;
        gap: 5px 7px;
        align-items: center;
    }

    .news-archive-pagination__links a,
    .news-archive-pagination__links span {
        display: inline-block;
        min-width: 12px;
        padding: 9px;
        border-radius: 2px;
        font-size: 12px;
        font-weight: 500;
        line-height: 12px;
        text-align: center;
        text-decoration: none;
        color: #3f3f3f;
        transition: background .15s ease, color .15s ease;
    }

    .news-archive-pagination__links a:hover {
        color: #65ad00;
    }

    .news-archive-pagination__links .current {
        color: #fff;
        background: #c4c9cd;
    }

    .news-archive-pagination__links .prev,
    .news-archive-pagination__links .next {
        padding: 7px 15px;
        font-size: 11px;
        letter-spacing: .02em;
        text-transform: uppercase;
    }

    .news-sidebar__section {
        margin-bottom: 56px;
    }

    .news-sidebar__title {
        display: inline-block;
        margin: 0 0 34px;
        color: #0b3b5b;
        font-size: 16px;
        font-weight: 400;
        line-height: 1.35;
    }

    .news-sidebar__title::after {
        content: "";
        display: block;
        width: 40px;
        height: 2px;
        margin-top: 16px;
        background: #e4e4e4;
    }

    .news-sidebar__search {
        width: 100%;
        border: 0;
        border-bottom: 1px solid #d8d8d8;
        padding: 0 0 12px;
        font-size: 16px;
        color: #3f3f3f;
        background: transparent;
        outline: none;
    }

    .news-sidebar__search::placeholder {
        color: #6f7088;
        opacity: 1;
    }

    .news-sidebar__list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .news-sidebar__item {
        padding-bottom: 14px;
        border-bottom: 1px solid #ececec;
    }

    .news-sidebar__item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .news-sidebar__item a {
        color: #222;
        text-decoration: none;
        font-size: 18px;
        line-height: 1.45;
        transition: color .15s ease;
    }

    .news-sidebar__item a:hover {
        color: #65ad00;
    }

    @media (max-width: 767.98px) {
        .news-index-page {
            padding: 4px 12px 36px;
        }

        .news-index-page__layout {
            display: block;
        }

        .news-index-page__header {
            margin-bottom: 32px;
        }

        .news-index-page__header h1 {
            font-size: 32px;
        }

        .news-index-page__header h1::after {
            margin-top: 18px;
        }

        .news-archive-item {
            margin-bottom: 42px;
        }

        .news-archive-item__title {
            font-size: 24px;
        }

        .news-archive-item__meta {
            gap: 10px;
            margin-bottom: 22px;
            font-size: 15px;
        }

        .news-archive-item__content {
            margin-bottom: 24px;
            line-height: 1.7;
        }

        .news-index-page__sidebar {
            width: auto;
            padding-top: 24px;
        }

        .news-sidebar__section {
            margin-bottom: 36px;
        }
    }
</style>

<div class="news-index-page">
    <div class="news-index-page__layout">
        <div class="news-index-page__main">
            <header class="news-index-page__header">
                <h1><?= Html::encode($newsH1) ?></h1>
            </header>

            <div class="news-index-page__list">
                <?php foreach ($dataProvider->getModels() as $model): ?>
                    <?= $this->render('_news-item', ['model' => $model]) ?>
                <?php endforeach; ?>
            </div>

            <?php if ($pageCount > 1): ?>
                <div class="news-archive-pagination">
                    <nav aria-label="Новости">
                        <div class="news-archive-pagination__links">
                            <?php if ($currentPage > 1): ?>
                                <a class="prev" href="<?= Html::encode($pageUrl($currentPage - 1)) ?>">Назад</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                                <?php if ($i === $currentPage): ?>
                                    <span class="current"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="<?= Html::encode($pageUrl($i)) ?>"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($currentPage < $pageCount): ?>
                                <a class="next" href="<?= Html::encode($pageUrl($currentPage + 1)) ?>">Далее</a>
                            <?php endif; ?>
                        </div>
                    </nav>
                </div>
            <?php endif; ?>
        </div>

        <aside class="news-index-page__sidebar">
            <div class="news-sidebar__section">
                <form action="<?= Html::encode(Url::to(['/search'])) ?>" method="get">
                    <input class="news-sidebar__search" type="text" name="q" placeholder="Search..." aria-label="Поиск по сайту">
                </form>
            </div>

            <?php if (!empty($recentNews)): ?>
                <div class="news-sidebar__section">
                    <h2 class="news-sidebar__title">Свежие записи</h2>
                    <div class="news-sidebar__list">
                        <?php foreach ($recentNews as $recentItem): ?>
                            <div class="news-sidebar__item">
                                <a href="<?= Html::encode('/news/' . $recentItem->slug) ?>">
                                    <?= Html::encode($recentItem->title) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</div>
