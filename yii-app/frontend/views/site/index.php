<?php

/* @var $this yii\web\View */
/* @var $sensorsList common\models\SensorsList */
/* @var $dataProviderCatalog yii\data\ActiveDataProvider */

use common\models\News;
use common\models\Seo;
use common\models\Page;
use common\models\Manufacture;
use frontend\widgets\GazLinks;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

$dataProvider = new ActiveDataProvider([
    'query' => News::find()->orderBy('date DESC'),
    'pagination' => [
        'pageSize' => 20,
        'pageSizeParam' => false
    ],
]);

$dataProviderNews = new ActiveDataProvider([
    'query' => News::find()->orderBy('date DESC'),
    'pagination' => [
        'pageSize' => 4,
        'pageSizeParam' => false
    ],
]);

$dataProviderManufacture = new ActiveDataProvider([
    'query' => Manufacture::find(),
    'pagination' => [
        'pageSize' => 16,
        'pageSizeParam' => false
    ],
]);

$seo = Seo::findOne(['type' => Seo::TYPE_PAGE_HOME])->registerMetaTags($this);
$seoHome = Seo::findOne(['type' => Seo::TYPE_PAGE_ABOUT]);

?>
<style>

    .post-box {
        margin-bottom: 12px;
    }

</style>
<div class="site-index">

    <h1 class="text-center"><?= $seo->h1 ?></h1>

    <div class="row">
        <div class="col-xxl-2 col-md-3">
            <div id="main_news">
            </div>
        </div>

        <div class="col-xxl-8 col-md-6 order-first order-md-0">

            <h2>Последние поступления продукции</h2>

            <div class="col-lg-12 col-md-6 bg-light border p-3 mb-3">
                <?= $this->render('_filter', [
                    'model' => $searchModel,
                ]) ?>
            </div>

            <?= $this->render('_grid', [
                'dataProvider' => $dataProviderCatalog,
                //'searchModel' => $searchModel,
            ]) ?>

            <p><a class="share" href="<?= Url::to(['/catalog']) ?>">Перейти в каталог продукции Газсенсор &rarr;</a></p>

            <h2>Сотрудничаем с мировыми брендами</h2>

            <p>Компания Газсенсор активно развивает направление поставок газовых датчиков и сенсоров от ведущих мировых
                производителей.</p>

            <div id="contentSection">
                <?php foreach ($dataProviderManufacture->getModels() ?? [] as $model): ?>
                    <?= $this->render('_manufacture-item', ['model' => $model]) ?>
                <?php endforeach; ?>
            </div>

            <p><a class="share" href="<?= Url::to(['/manufacture']) ?>">Перейти в каталог производителей &rarr;</a></p>

            <h2><?= $seoHome->h1 ?></h2>

            <?= Page::findOne(['type' => Page::TYPE_ABOUT])->content ?>

        </div>
        <div class="col-md-2">

            <?= GazLinks::widget() ?>

        </div>
    </div>

</div>