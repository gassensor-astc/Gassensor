<?php
/* @var $this yii\web\View */
/* @var $q string */
/* @var $model common\models\News|common\models\Applications */
/* @var $type string news|applications */

use yii\helpers\Html;

// URL и данные в зависимости от типа
if ($type === 'news') {
    $url = "/news/{$model->slug}";
    $date = $model->date;
    $pictUrl = method_exists($model, 'getPictUrl') ? $model->getPictUrl() : null;
} else {
    $url = "/applications/{$model->slug}";
    $date = $model->created_at ? date('Y-m-d', $model->created_at) : null;
    $pictUrl = $model->preview ?: null;
}

// H1 из SEO или используем title
$seo = $model->seo ?? null;
$h1 = ($seo && $seo->h1) ? $seo->h1 : $model->title;

?>

<div class="snippet-news d-flex align-items-start gap-3 p-2 mb-2">
    <?php if ($pictUrl): ?>
        <a href="<?= $url ?>" target="_blank">
            <img src="<?= $pictUrl ?>" alt="<?= Html::encode($h1) ?>" style="max-width: 60px; max-height: 60px; object-fit: cover; border-radius: 4px;">
        </a>
    <?php endif; ?>
    <div>
        <a href="<?= $url ?>" target="_blank">
            <?= preg_replace("($q)iu", "<b>$q</b>", Html::encode($h1)) ?>
        </a>
        <?php if ($date): ?>
            <br><small class="text-muted"><?= date('d.m.Y', strtotime($date)) ?></small>
        <?php endif; ?>
    </div>
</div>
