<?php
/* @var $this yii\web\View */
/* @var $model common\models\Applications */

use common\models\Seo;
use yii\helpers\Html;

$seo = Seo::findOne(['type' => Seo::TYPE_APPLICATIONS])->registerMetaTags($this);
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => '/applications'];
$this->params['breadcrumbs'][] = $model->title;

if ($seo = $model->seo) {
    $seo->registerMetaTags($this);
}

$cleanText = strip_tags($model->content);
$len = mb_strlen($cleanText, 'UTF-8');

$readTime = round($len * 50/1000/60);

function declension($number, $titles) {
    $cases = [2, 0, 1, 1, 1, 2];
    return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
}

$minutes = ['минута', 'минуты', 'минут'];

$model->views++;
$model->save();
?>
<style>
    .new-info {
        margin-left: 65%;
    }
    .new-info i {
        margin-left: 10px;
    }
    #news-photo-block a {
        text-decoration: underline !important;
        color: #4c5d8d;
        transition: all 0.3s linear;
        -webkit-transition: all 0.3s linear;
    }
    #news-photo-block a:hover {
        color: #b53e00;
        text-decoration:none !important;
    }
</style>

<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> container'>
    <div class="new-info">
        <?=date('d.m.Y', $model->created_at)?> | Время чтения: <?=$readTime?> <?=declension($readTime, $minutes)?> <i class="fa fa-eye" aria-hidden="true"></i> <?=$model->views?>
    </div>
    <h1>
        <?= $model->seo->h1 ?? $this->title ?>
        <?php if (Yii::$app->user->isAdmin()): ?>
            <?= Html::a('<i class="fa fa-edit m-1"></i>',
                ['backend/applications/update',
                    'id' => $model->id,],
                ['class' => "btn d-inline rounded-pill",
                    'target' => "_blank",
                    'style' => "font-size: 0.8rem; padding: 4px; background: red;"
                ]) ?>
        <?php endif; ?>
    </h1>

    <div class="single-img w-100">
        <div class="" id="news-photo-block">
            <p id="news-content">
                <?= $model->content ?>
            </p>
        </div>

        <div class="mt-4 mb-2">
            <a href="/applications">
                <i class="fa fa-long-arrow-left" aria-hidden="true"></i> Назад в статьи
            </a>
        </div>
    </div>
</div>

