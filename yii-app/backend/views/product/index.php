<?php

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Product;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Товары');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index', 'sort' => '-id',]];

?>
<div class="row-fluid">

    <div class="col">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false">

            <!-- widget div-->
            <div>

                <h1><?= Html::encode($this->title) ?></h1>

                <div class="box-header">
                    <div class="row">
                        <div class="col-md-12">

                            <?= Html::a(Yii::t('app', 'Добавить товар'), ['create'], ['class' => 'btn btn-info btn-sm']) ?>

                            <?= Html::a(Yii::t('app', 'Добавить сенсоры'), ['upload-sensors'], ['class' => 'btn btn-info btn-sm']) ?>

                            <?= Html::a('<i class="far fa-file-excel"></i> Выгрузить каталог', Url::current(['export-excel']), ['class' => 'btn btn-sm btn-primary mr-1']) ?>

                        </div>
                    </div>
                </div>

                <br>

                <div class="table-responsive">

                    <?php Pjax::begin(); ?>

                    <?= Html::beginForm(['product/checkbox-delete'], 'post'); ?>

                    <?= GridView::widget(array(
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,

                        'columns' => array(
                            array('class' => 'yii\grid\SerialColumn'),
                            //array('class' => 'yii\grid\ActionColumn'),
                            'id',
                            'createdUpdatedAt:raw:Создан/Изменен',
                            //'name',
                            Product::getColumnNameField(),
                            'manufacture_id:raw:id производителя',

                            Product::getManufactureTitleGridCol(),

                            Product::getGazesGridCol(true),

                            array(
                                'attribute' => 'picture',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->pictUrl
                                        ? Html::img($model->pictUrl, array('style' => 'max-width: 100px;'))
                                        : null;
                                }
                            ),
                            //'price',
                            array(
                                'attribute' => 'slug',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a($model->slug, $model->url, array('target' => '_blank', 'data-pjax' => 0));
                                }
                            ),
                            'measurement_type_id',
                            'measurementType.name:text:Measurement Type',
                            'formfactor',
                            'formfactor_unit',
                            array(
                                'attribute' => 'range',
                                'label' => 'Диапазоны',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $this->render('_ranges', array('model' => $model));
                                }
                            ),
                            //'resolution',
                            //'sensitivity',
                            //'sensitivity_unit',
                            'response_time:datetime',
                            //'energy_consumption_from',
                            //'energy_consumption_to',
                            'energy_consumption_unit',
                            'temperature_range_from',
                            'temperature_range_to',
                            array(
                                'class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => function ($model) {
                                    return array('value' => $model->id);
                                },
                            ),
                        ),
                    )); ?>

                    <?= Html::submitButton('Удалить выбранные', ['class' => 'btn btn-danger mt-3 mb-3', 'data-confirm' => Yii::t('yii', 'Вы уверены, что хотите удалить данные записи? Восстановить их будет нельзя.'),]); ?>

                    <?= Html::endForm() ?>

                    <?php Pjax::end(); ?>

                </div>

            </div>
            <!-- end widget content -->

        </div>
        <!-- end widget div -->

    </div>
    <!-- end widget -->

</div>
