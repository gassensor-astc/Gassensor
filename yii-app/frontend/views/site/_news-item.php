<?php
/* @var $this yii\web\View */
/* @var $model common\models\News */

use yii\helpers\Html;

$time = strtotime((string) $model->date);
$detailUrl = '/news/' . $model->slug;
$content = trim((string) $model->content);
$contentImageUrl = null;
if ($content !== '' && preg_match('~<img[^>]+src=["\']([^"\']+)["\'][^>]*>~i', $content, $imgMatch)) {
    $contentImageUrl = $imgMatch[1];
}

$contentWithoutImages = preg_replace('~<figure\b[^>]*>.*?<img\b[^>]*>.*?</figure>~is', '', $content);
$contentWithoutImages = preg_replace('~<img\b[^>]*>~i', '', (string) $contentWithoutImages);
$contentWithoutImages = trim((string) $contentWithoutImages);

$pictureUrl = $contentImageUrl ?: $model->getPictUrl();
?>
<article class="news-archive-item">
    <div class="news-archive-item__category">
        <a href="<?= Html::encode($detailUrl) ?>">Без рубрики</a>
    </div>

    <h2 class="news-archive-item__title">
        <a href="<?= Html::encode($detailUrl) ?>"><?= Html::encode($model->title) ?></a>
    </h2>

    <div class="news-archive-item__meta">
        <span class="news-archive-item__author">admin</span>
        <a href="<?= Html::encode($detailUrl) ?>">
            <?= $time ? date('d.m.Y H:i', $time) : Html::encode((string) $model->date) ?>
        </a>
    </div>

    <div class="news-archive-item__content">
        <?= $contentWithoutImages ?>
    </div>

    <?php if ($pictureUrl): ?>
        <div class="news-archive-item__image">
            <?= Html::img($pictureUrl, [
                'alt' => $model->title,
                'loading' => 'lazy',
                'title' => $model->title,
            ]) ?>
        </div>
    <?php endif; ?>
</article>
