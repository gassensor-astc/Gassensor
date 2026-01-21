<?php
/* @var $this yii\web\View */
/* @var $q string */
/* @var $model common\models\Product */

use yii\helpers\Html;

$url = $model->url;
$pictUrl = $model->getPictUrl(false);

// Берём H1 из SEO, если есть, иначе name
$seo = $model->seo ?? null;
$title = ($seo && $seo->h1) ? $seo->h1 : $model->name;
$pattern = preg_quote(Html::encode($q), '/');

?>

<div class="snippet-product d-flex align-items-center gap-3 p-2 mb" style="line-height: 1.2;">
    <div style="width: 60px; height: 50px; flex-shrink: 0;">
        <?php if ($pictUrl): ?>
            <a href="<?= $url ?>" target="_blank">
                <img src="<?= $pictUrl ?>" alt="<?= Html::encode($title) ?>" style="width: 60px; height: 50px;">
            </a>
        <?php endif; ?>
    </div>
    <div>
        <a href="<?= $url ?>" target="_blank">
            <?= preg_replace("/($pattern)/iu", "<b>$1</b>", Html::encode($title)) ?>
        </a>
    </div>
</div>
