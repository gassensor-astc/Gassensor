<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\helpers\MyDataColumn;
use common\models\Product;
use common\helpers\Tools;
use yii\grid\GridView;
use yii\helpers\Html;

?>
<div class="table-responsive">
    <?= GridView::widget([

        'dataProvider' => $dataProvider,
        'columns' => [

            //'id',
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'white-space: nowrap;', 'class' => 'table-top'],
                'label' => 'Сенсор (датчик)',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model common\models\Product */
                    return Html::a($model->name, $model->url, ['target' => '_blank']);
                }
            ],

            [
                'attribute' => 'gaz_title',
                'headerOptions' => ['class' => 'table-top'],
                'label' => 'Газ',
                'format' => 'raw',
                'value' => function ($model) {
                    $result = '';
                    if ($mainGaz = $model->mainGaz) {
                        $label = $mainGaz->title;
                        //$result .= Html::a($label, "/catalog/{$mainGaz->slug}", ['class' => 'px-1 gaz main']);
                        $result .= Html::tag('div', "<b>$label</b>");
                        $result .= '<br/>';
                    }
                    $arr = Product::getGazesGridCol();
                    $result .= $arr['value']($model);

                    return $result ?: null;
                }
            ],

            [
                'label' => 'Диапазон',
                'headerOptions' => ['class' => 'table-top'],
                'class' => MyDataColumn::class,
                'tpl' => '/product/_cell-range',
            ],

            Product::getMeasurementTypeNameGridCol(),

            [
                'attribute' => 'signalType',
                'label' => 'Выходной сигнал',
                'headerOptions' => ['class' => 'table-top'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'format' => 'raw',
                'value' => function ($model) {
                    $html = '';
                    if ($model->first) {
                        $html .= '<div>Первичный</div>';
                    }
                    if ($model->analog) {
                        $html .= '<div>Аналоговый</div>';
                    }
                    if ($model->digital) {
                        $html .= '<div>Цифровой</div>';
                    }
                    return $html;
                }
            ],

            [
                'attribute' => 'formfactor',
                'label' => 'Диаметр, мм (типоразмер)',
                'headerOptions' => ['class' => 'table-top'],
                //   'contentOptions' => ['style' => 'text-align:center;'],
                'format' => 'raw',
                'value' => function ($model) {
                    $type = Tools::checkStringType($model->formfactor);

                    if ($type == 'int' || $type == 'float') {
                        $value = $model->formfactor;

                        if ($type == 'float') {
                            $value = (float)$value;
                            $value = round($value, 1);
                        } else {
                            $value = $value . '.0';
                        }

                        return '<div class="text-center">' . $value . '</div>';
                    } else {
                        return '<p class="text-center">' . $model->formfactor . '</p>';
                    }

                }
            ],

            Product::getManufactureTitleGridCol(),

            [
                'attribute' => 'cart',
                'label' => 'Заказать',
                'headerOptions' => ['class' => 'table-top', 'style' => 'max-width: 90px;'],
                'contentOptions' => ['style' => 'text-align:center; max-width: 90px;'],
                'format' => 'raw',
                'value' => function ($model) {
                    return $this->render('_cell-cart', ['model' => $model]);
                }
            ],
        ],
    ]); ?>

</div>