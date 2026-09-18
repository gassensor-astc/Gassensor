<?php

/* @var $this yii\web\View */
/* @var $model common\models\Product */

use yii\helpers\Html;

$url = $model->url;
$pictUrl = $model->getPictUrl();

?>
<article class="sample-card">
    <a class="sample-card__image" href="<?= $url ?>" aria-label="Открыть товар: <?= Html::encode($model->name) ?>">
        <?php if ($pictUrl): ?>
            <?= Html::img($pictUrl, ['alt' => $model->name, 'title' => $model->name, 'loading' => 'lazy']) ?>
        <?php else: ?>
            <span class="sample-card__noimg">Нет фото</span>
        <?php endif; ?>
    </a>
    <a class="sample-card__title" href="<?= $url ?>"><?= Html::encode($model->name) ?></a>
    <a class="sample-card__more" href="<?= $url ?>">Подробнее
        <span class="sample-card__more-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 18L18 6M18 6H10M18 6V14" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        </span>
    </a>
</article>
