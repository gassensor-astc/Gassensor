<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\helpers\StringHelpers;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Новости');
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

                            <?= Html::a(Yii::t('app', 'Добавить новость'), ['create'], ['class' => 'btn btn-info btn-sm pull-left']) ?>

                        </div>
                    </div>
                </div>

                <br>

                <div class="table-responsive">
                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= Html::beginForm(['news/checkbox-delete'], 'post'); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            [
                                'attribute' => 'created_at',
                                'label' => 'Создано',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return date('Y-m-d ',$model->created_at );
                                }
                            ],
                            [
                                'attribute' => 'date',
                                'label' => 'Публикация',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->date;
                                }
                            ],

                            [
                                'attribute' => 'Изображение',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $this->render('_files', ['model' => $model,]);
                                }
                            ],
                            [
                                'attribute' => 'slug',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a($model->slug, "/news/{$model->slug}", ['target' => '_blank', 'data-pjax' => 0]).'<a style="padding-left: 20px;" href="/backend/news/update?id='.$model->id.'" title="Редактировать" aria-label="Редактировать" data-pjax="0"><svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"></path></svg></a>';
                                }
                            ],

                            'title',
                            //'content:ntext',

                            [
                                'attribute' => 'content',
                                'label' => 'Контент',
                                'value' => function ($data) {
                                    return StringHelpers::shortText(StringHelpers::removeHtmlTags($data->content), 300);
                                }
                            ],
                            [
                                'attribute' => 'lenght',
                                'label' => 'Длина контента',
                                'format' => 'raw',
                                'value' => function ($data) {
                                    return '<p style="text-align: right;width: 100%">'.number_format(mb_strlen(StringHelpers::shortText(StringHelpers::removeHtmlTags($data->content), 10000000)), 0, '', ',').'</p>';
                                },
                                'filter'=>false,
                            ],

                            [
                                'class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => function ($model) {
                                return ['value' => $model->id];
                            },
                            ],
                            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}',],

                        ],
                    ]); ?>

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
