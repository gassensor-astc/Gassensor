<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\helpers\StringHelpers;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Статьи');
$this->params['breadcrumbs'][] = $this->title;

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

                            <?= Html::a(Yii::t('app', 'Добавить'), ['create'], ['class' => 'btn btn-info btn-sm pull-left']) ?>

                        </div>
                    </div>
                </div>

                <br>

                <div class="table-responsive">
                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
                            'id',
                            [
                                'attribute' => 'Изображение',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if (empty($model->preview)) {
                                        return '';
                                    }
                                    return Html::img($model->preview, [
                                        'style' => 'width: 120px; border: 1px solid #ddd; padding: 4px; background: #fff;',
                                        'alt' => $model->title,
                                        'loading' => 'lazy',
                                    ]);
                                }
                            ],
                            'title',
                            [
                                'attribute' => 'slug',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a($model->slug, "/applications/{$model->slug}", [
                                        'target' => '_blank',
                                        'data-pjax' => 0,
                                    ]);
                                }
                            ],
                            //'content:ntext',

                            [
                                'attribute' => 'content',
                                'label' => 'Контент',
                                'value' => function ($data) {
                                    return StringHelpers::shortText(StringHelpers::removeHtmlTags($data->content));
                                }
                            ],

                            [
                                'attribute' => 'type',
                                'label' => 'Тип',
                                'value' => function ($data) {
                                    return $data->type === 1 ? 'газовые сенсоры' : 'газодетекторные трубоки';
                                }
                            ],

                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>
                </div>

            </div>
            <!-- end widget content -->

        </div>
        <!-- end widget div -->

    </div>
    <!-- end widget -->

</div>
