<?php

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $modelGaz common\models\Gaz */
/* @var $formAdd common\components\cart\AddToCartForm */

use common\models\Product;
use frontend\assets\AppAsset;
use frontend\widgets\CartAddWidget;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => '/catalog'];

$gaz = $model->mainGaz;

if (!$gaz && $model->gazs) {
    $gaz = $model->gazs[0];
}

if ($gaz) {
    $this->params['breadcrumbs'][] = ['label' => $gaz->title, 'url' => "/catalog/{$gaz->slug}"];
}

$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('lib/yii2AjaxRequest.js', ['depends' => AppAsset::class]);

if ($seo = $model->seo) {
    $seo->registerMetaTags($this);
}

$this->params['productJsonLd'] = $model->getJsonLd();

?>

<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> container'>
    <h1>
        <?= $seo->h1 ?? $this->title ?>
        <?php if (\Yii::$app->user->isAdmin()): ?>
                <a href="/backend/product/update?id=<?= $model->id ?>"
                   class="btn d-inline rounded-pill ml-2"
                   target="_blank"
                   style="font-size: 0.8rem; padding: 4px; background: red;">
                    <i class="fa fa-edit m-1"></i>
                </a>
        <?php endif; ?>
    </h1>

    <div class="row">
        <div class="col-md-4">
            <?php
            $url = $model->getPictUrl() ?: '/i/no-photo.gif';
            ?>
            <?= Html::img($url, [
                    'alt' => $this->title,
                    'loading' => "lazy",
                    'title' => $this->title,
                    'class' => 'border',
            ]) ?>
            <hr/>

            <?php foreach (Product::getPdfIndexes() as $v):
                if (!$url = $model->getPdfUrl($v)) {
                    continue;
                }
                $attr = 'pdf' . ($v ? (int)$v : '');
                ?>

                <div class="mt-3 border p-2 shadow position-relative pdf-link-wrap">
                    <?= Html::a('<i class="fa fa-2x fa-file-pdf"></i> ' . $model->$attr, $url, ['target' => '_blank', 'class' => 'stretched-link']) ?>
                </div>

                <hr/>
            <?php endforeach; ?>

            <div class="border my-3 p-2 bg-light">
                <?= CartAddWidget::widget(['formAdd' => $formAdd, 'model' => $model, 'hiddenCount' => false, 'tableGrid' => true]) ?>
            </div>

        </div>
        <div class="col-md-8">


            <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                            'name:text:Название',

                            [
                                    'attribute' => 'manufacture.title',
                                    'label' => 'Производитель',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        // return $model->manufacture->title;
                                        return Html::a($model->manufacture->title, Url::to(['/manufacture/' . $model->manufacture->slug]), []);

                                    }

                            ],
                        //'price',

                            [
                                    'attribute' => 'mainGaz',
                                    'label' => 'Основной газ',
                                    'format' => 'raw',
                                    'value' => function ($model) {

                                        $arrs = [];
                                        $rerurn = '';

                                        if ($gaz = $model->mainGaz) {
                                            $arrs[] = ['slug' => $gaz->slug, 'title' => $gaz->title];
                                        }

                                        if ($gaz2 = $model->mainGaz2) {
                                            $arrs[] = ['slug' => $gaz2->slug, 'title' => $gaz2->title];
                                        }

                                        if ($gaz3 = $model->mainGaz3) {
                                            $arrs[] = ['slug' => $gaz3->slug, 'title' => $gaz3->title];
                                        }

                                        foreach ($arrs as $arr) {
                                            $rerurn .= Html::a($arr['title'], "/catalog/{$arr['slug']}") . ' / ';
                                        }

                                        $rerurn = rtrim($rerurn, ' / ');

                                        return $rerurn;
                                    }
                            ],

                            Product::getGazesGridCol(false, $model->notMainGazes),

                            'measurementType.name:text:Тип измерения',

                            [
                                    'attribute' => 'formfactor',
                                    'label' => 'Диаметр, мм (типоразмер)',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-formfactor', ['model' => $model]);
                                    }
                            ],

                            [
                                    'attribute' => 'range',
                                    'label' => 'Диапазон',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-range', ['model' => $model]);
                                    }
                            ],

                            [
                                    'attribute' => 'sensitivity',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-sensitivity', ['model' => $model]);
                                    }
                            ],

                            [
                                    'attribute' => 'response_time',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-response-time', ['model' => $model]);
                                    }
                            ],

                            [
                                    'attribute' => 'energy_consumption',
                                    'label' => 'Энергопотребление',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-energy_consumption', ['model' => $model]);
                                    }
                            ],

                            [
                                    'attribute' => 'temperature_range',
                                    'label' => 'Температурный диапазон',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-temperature_range', ['model' => $model]);
                                    }
                            ],
                            [
                                    'attribute' => 'info',
                                    'label' => 'Описание',
                                    'value' => $model->info,
                                    'visible' => $model->info,
                            ],
                            [
                                    'attribute' => 'bias_voltage',
                                    'label' => 'Напряжение смещения (Bias Voltage)',
                                    'value' => $model->bias_voltage,
                                    'visible' => $model->bias_voltage,
                                    'format' => 'html',
                                    'captionOptions' => ['style' => 'color: red'],
                                    'contentOptions' => ['style' => 'color: red'],
                            ],
                    ],
            ]) ?>

        </div>
    </div>


</div>


