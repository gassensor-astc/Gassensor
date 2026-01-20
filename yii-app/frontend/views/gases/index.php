<?php

/* @var $this yii\web\View */
/* @var $dataProviderGases yii\data\ActiveDataProvider */
/* @var $dataProviderFreons yii\data\ActiveDataProvider */

use common\models\Gaz;
use common\models\Seo;
use yii\helpers\Html;

$this->title = 'Датчики и сенсоры по типу газа';
$this->params['breadcrumbs'][] = $this->title;

$h1 = $this->title;
if ($seo = Seo::findOne(['type' => Seo::TYPE_PAGE_GASES])) {
    $seo->registerMetaTags($this);
    if ($seo->h1) {
        $h1 = $seo->h1;
    }
}

?>
<style>
    .gases-list {
        column-count: 4;
        column-gap: 20px;
    }
    .gases-list a {
        display: block;
        padding: 4px 0;
        break-inside: avoid;
    }
    @media (max-width: 991px) {
        .gases-list {
            column-count: 3;
        }
    }
    @media (max-width: 767px) {
        .gases-list {
            column-count: 2;
        }
    }
    @media (max-width: 480px) {
        .gases-list {
            column-count: 1;
        }
    }
</style>
<div class="gases-index container">

    <h1 class="text-center">
        <?= Html::encode($h1) ?>
    </h1>

    <h5 class="mt-4 mb-3">Газы</h5>
    <div class="gases-list">
        <?php foreach ($dataProviderGases->getModels() as $model): ?>
            <?= Html::a($model->title, "/catalog/{$model->slug}", ['class' => 'gaz-link']) ?>
        <?php endforeach; ?>
    </div>

    <h5 class="mt-4 mb-3">Фреоны (хладагенты)</h5>
    <div class="gases-list">
        <?php foreach ($dataProviderFreons->getModels() as $model): ?>
            <?= Html::a($model->title, "/catalog/{$model->slug}", ['class' => 'gaz-link']) ?>
        <?php endforeach; ?>
    </div>

</div>