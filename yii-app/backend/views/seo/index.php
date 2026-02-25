<?php

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SeoSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\models\Seo;

$this->title = 'SEO';
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

                            <?= Html::a(Yii::t('app', 'Добавить'), ['create'], ['class' => 'btn btn-info mb-1']) ?>

                            <?php
                                $typeNames = Seo::getTypeDropDownData();
                                foreach (array(Seo::TYPE_PAGE_HOME,
                                              Seo::TYPE_PAGE_CATALOG,
                                              Seo::TYPE_PAGE_CONTACT,
                                              Seo::TYPE_MANUFACTURES,
                                              Seo::TYPE_PAGE_VACANCY,
                                              Seo::TYPE_PAGE_PRIVACY,
                                              Seo::TYPE_PAGE_ACCESSORIES,
                                              Seo::TYPE_PAGE_CONVERTER,
                                              Seo::TYPE_PAGE_REMAINS,
                                              Seo::TYPE_PAGE_NEWS,
                                              Seo::TYPE_PAGE_ABOUT,
                                              Seo::TYPE_PAGE_GASES,
                                          ) as $v):
                                if ($model = Seo::findOne(['type' => $v])) {
                                    $url = ['update', 'id' => $model->id];
                                } else {
                                    $url = ['create', 'type' => $v];
                                }
                                $typeName = $typeNames[$v] ?? "type:$v";
                                ?>
                                <?= Html::a('<i class="fa fa-fw fa-edit"></i> ' . $typeName,
                                $url, ['class' => 'btn btn-success mb-1 mr-1']) ?>

                            <?php endforeach; ?>

                            <?= Html::a('<i class="fa fa-fw fa-edit"></i> Производители (каталог)', Url::to(['seo/manufacture']), ['class' => 'btn btn-success mr-1']) ?>

                            <?= Html::a('<i class="fa fa-fw fa-edit"></i> SEO-описания товаров', Url::to(['seo/product-descriptions']), ['class' => 'btn btn-success mr-1']) ?>

                            <?= Html::a('<i class="fa fa-fw fa-edit"></i> Robots.txt', Url::to(['seo/robots']), ['class' => 'btn btn-success mr-1']) ?>

                            <?= Html::a('<i class="fa fa-fw fa-edit"></i> Sitemap.xml', Url::to(['seo/sitemap']), ['class' => 'btn btn-success mr-1']) ?>

                            <?= Html::a('<i class="fa fa-fw fa-edit"></i> Google Indexing URLs', Url::to(['seo/google']), ['class' => 'btn btn-success mr-1']) ?>

                        </div>

                    </div>

                </div>

                <br>

                <div class="table-responsive">

                    <?php Pjax::begin(); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'class' => 'yii\grid\ActionColumn',
                            ],
                            'id',
                            'ref_id',
                            'type',
                            'typeName',
                            'h1',
                            'title',
                            'keyword:ntext',
                            'description:ntext',
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
