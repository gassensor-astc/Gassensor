<?php
/* @var $this yii\web\View */
/* @var $model common\models\Applications */
/* @var $modelSeo common\models\Seo */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Редактирование: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => 'Применение датчиков газа', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update') . ' #' . $model->id;

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

                <!-- widget content -->
                <div class="widget-body">

                    <h1><?= Html::encode($this->title) ?></h1>

                    <?= $this->render('_form', [
                        'model' => $model,
                        'modelSeo' => $modelSeo,
                    ]) ?>

                </div>

                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>

    </article>

</div>
<script>
    (function() {
        setInterval(() => {
            let eyes = document.querySelectorAll('link[rel="stylesheet"]');
            for (let i = 0; i < eyes.length; i++) {
                if (eyes[i]) {
                    if (eyes[i].href.includes('bootstrap.css?v=')) {
                        eyes[i].remove();
                    }
                }
            }
        }, 50);
    })()
</script>