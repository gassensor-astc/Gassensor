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

?>

<div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
    <div class="card h-100 text-center">
        <a href="<?= $url ?>" target="_blank">
            <?php if ($pictUrl): ?>
                <img src="<?= $pictUrl ?>" class="card-img-top p-3" alt="<?= Html::encode($title) ?>" style="width: 100%; height: 180px;">
            <?php else: ?>
                <div class="card-img-top p-3" style="height: 180px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                    <span class="text-muted">Нет фото</span>
                </div>
            <?php endif; ?>
        </a>
        <div class="card-body d-flex flex-column">
            <p class="card-text flex-grow-1" style="font-size: 14px; line-height: 1.3;">
                <?= Html::encode($title) ?>
            </p>
            <a href="<?= $url ?>" target="_blank" class="btn btn-search btn-sm mt-2">Перейти</a>
        </div>
    </div>
</div>
