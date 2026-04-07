<?php


use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Seo */

$this->title = Yii::t('app', 'Загрузка и выгрузка файла карты сайта sitemap.xml', [
    'name' => 'Загрузка и выгрузка файла карты сайта sitemap.xml',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Seos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'sitemap.xml');

?>

<!-- row -->
<div class="row">

    <!-- NEW WIDGET START -->
    <article class="col-sm-12 col-md-12 col-lg-12">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
            <!-- widget options:
            usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
            data-widget-colorbutton="false"
            data-widget-editbutton="false"
            data-widget-togglebutton="false"
            data-widget-deletebutton="false"
            data-widget-fullscreenbutton="false"
            data-widget-custombutton="false"
            data-widget-collapsed="true"
            data-widget-sortable="false"
            -->

            <!-- widget div-->
            <div>

                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->

                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body">

                    <h1><?= Html::encode($this->title) ?></h1>

                    <?= Html::a('Загрузить карту sitemap.xml', '/sitemap.xml', ['class' => 'btn btn-success', 'download' => 'sitemap.xml' ]) ?>

                    <?= Html::a('Выгрузить карту sitemap.xml', Url::to(['seo/upload-sitemap']), ['class' => 'btn btn-success']) ?>

                    <?= Html::a('Сгенерировать sitemap.xml', Url::to(['seo/generate-sitemap']), [
                        'class' => 'btn btn-primary',
                        'data' => ['confirm' => 'Сформировать sitemap.xml и дочерние карты?']
                    ]) ?>

                    <div style="margin-top: 15px;">
                        <div>Файлы карт сайта:</div>
                        <div>sitemap.xml</div>
                        <div>sitemap-main.xml</div>
                        <div>sitemap-news.xml</div>
                        <div>sitemap-applications.xml</div>
                        <div>sitemap-manufacture.xml</div>
                        <div>sitemap-manufacture-products.xml</div>
                        <div>sitemap-gases.xml</div>
                        <div>sitemap-products.xml</div>
                    </div>

                </div>

                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>

    </article>

</div>

