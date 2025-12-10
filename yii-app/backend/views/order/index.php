<?php
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrderSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Orders');
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

                            <?= Html::a('<i class="far fa-file-excel"></i> Экспорт в Excel', Url::current(['export-excel']), ['class' => 'btn btn-sm btn-primary ml-5']) ?>

                        </div>
                    </div>

                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::beginForm(['batch'], 'post', ['class' => 'batch d-none']) ?>
                                Выделенные
                                <?= Html::hiddenInput('data') ?>
                                <?= Html::submitButton('<i class="fa fa-trash-alt"></i> Удалить', [
                                    'name' => 'action',
                                    'value' => 'delete',
                                    'class' => 'btn btn-danger']) ?>
                                <?= Html::endForm() ?>
                            </div>
                        </div>
                    </div>

                    <!--
    <p>
        <?= Html::a(Yii::t('app', 'Create Order'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
 -->

                    <br>

                    <div class="table-responsive">
                        <?php Pjax::begin(); ?>
                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\CheckboxColumn'],
                                ['class' => 'yii\grid\SerialColumn'],
                                'id',
                                'created_at:dateTime',
                                [
                                    'attribute' => 'products',
                                    'label' => 'Товары',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-products', ['model' => $model,]);
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $this->render('_cell-status', ['model' => $model,]);
                                    }
                                ],
                                'name',
                                'email:email',
                                'phone',
                                'delivery_info:ntext',
                                'comment:ntext',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update} {delete}',
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

</div>
