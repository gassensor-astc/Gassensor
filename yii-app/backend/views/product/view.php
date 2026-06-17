<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Product;

$this->title = "{$model->name} (#{$model->id})";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Товары'), 'url' => ['index', 'sort' => '-id',]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<div class="row-fluid">

    <div class="col">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false">

            <!-- widget div-->
            <div>

                <h1><?= Html::encode($this->title) ?></h1>

                <p>
                    <?= Html::a(Yii::t('app', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger mx-2',
                        'data' => [
                            'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить этот элемент?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                    <?= Html::a(Yii::t('app', '<i class="fa fa-eye"></i> Slug'), "/product/{$model->slug}", ['class' => 'btn btn-info', 'target' => '_blank']) ?>
                </p>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'created_at:dateTime',
                        'updated_at:dateTime',
                        'manufacture_id:raw:manufacture id',
                        'manufacture.title:raw:manufacture title',
                        'mainGaz.title:raw:Главный газ',
                        Product::getGazesGridCol(true),
                        'name',
                        'img',
                        'price',
                        'slug',
                        'measurement_type_id',
                        'measurementType.name:raw:Тип измерений',
                        'formfactor',
                        'formfactor_unit',
                        'resolution',

                        [
                            'attribute' => 'sensitivity_first',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->sensitivity_first;
                            }
                        ],

                        [
                            'attribute' => 'sensitivity_analog',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->sensitivity_analog;
                            }
                        ],

                        [
                            'attribute' => 'sensitivity_digital',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->sensitivity_digital;
                            }
                        ],

                        'sensitivity_from:raw:Чувсвительность от',
                        'sensitivity_to:raw:Чувсвительность до',
                        'sensitivity_unit:raw:Чувсвительность unit',
                        'response_time:raw',
                        'response_time_unit',
                        'energy_consumption_analog_from:raw:Энергопотребление аналоговый от',
                        'energy_consumption_analog_to:raw:Энергопотребление аналоговый до',
                        'energy_consumption_analog_unit:raw:Энергопотребление аналоговый ед. изм.',
                        'energy_consumption_digital_from:raw:Энергопотребление цифровой от',
                        'energy_consumption_digital_to:raw:Энергопотребление цифровой до',
                        'energy_consumption_digital_unit:raw:Энергопотребление цифровой ед. изм.',
                        'temperature_range_from:raw:Температурный диапазон от',
                        'temperature_range_to:raw:Температурный диапазон до',
                        'info:raw:Описание',
                        'bias_voltage:raw:Напряжение смещения (Bias Voltage)'
                    ],
                ]) ?>

            </div>
        </div>
    </div>

    <div class="col">
        <h3>Диапазоны</h3>
        <?= $this->render('_ranges', ['model' => $model]) ?>
    </div>

    <div class="col">
        <h3>SEO</h3>
        <?= $this->render('/seo/_sub-view', ['model' => $model->seo]) ?>
    </div>

    <div class="col">
        <?= $this->render('_files', ['model' => $model]) ?>
    </div>

</div>

