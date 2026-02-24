<?php

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $seo common\models\Seo|null */
/* @var $manufacture common\models\Manufacture|null */
/* @var string|null $canonicalUrl */

use common\models\Seo;
use frontend\assets\AppAsset;
use yii\helpers\Html;

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('lib/yii2AjaxRequest.js', ['depends' => AppAsset::class]);

if (!$seo) {
    $seo = Seo::findOne(['type' => Seo::TYPE_PAGE_CATALOG]);
    $this->title = $seo->title;
}
if (isset($canonicalUrl)) {
    $seo->setAttribute('url_canonical', null);
}
$seo->registerMetaTags($this);
if (!empty($canonicalUrl)) {
    $this->registerLinkTag(['rel' => 'canonical', 'href' => $canonicalUrl]);
}

/* @var $request \yii\web\Request */
$req = Yii::$app->request;

$js =
    <<<JS

$("select").change(function(){
    if ($(this).attr('id') !== 'productsearch-selectedsignaltypes') {
        $(this).closest("form").submit();
    } else {
        setTimeout(()=>{
            let h = $('.select2-selection--multiple').outerHeight();
            console.log('h = ' + h);
            if (h < 45) {
                $('#form_div').css('height', '330px');
            } else {
                $('#form_div').css('height', '360px');
            }
        }, 100);       
    }
})

$('input[type="number"]').on('change', function() {
    $(this).closest("form").submit();
});

$('input[type="text"]').on('change paste', function() {
    $(this).closest("form").submit();
});

$(window).on("scroll", function(){
	$('input[name="scroll"]').val($(window).scrollTop());
});

$(document).ready(function(){

	var p = window.location.search;
    
    setTimeout(()=>{
            let h = $('.select2-selection--multiple').outerHeight();
            console.log('h = ' + h);
            if (h < 45) {
                $('#form_div').css('height', '330px');
            } else {
                $('#form_div').css('height', '360px');
            }
        }, 500);
    
	p = p.match(new RegExp('scroll=([^&=]+)'));

	if (p) {
		window.scrollTo(0, p[1]);
	}	  

});

JS;

$this->registerJs($js, $this::POS_READY);

?>
<style>
    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #4c5d8d;
        border-color: #4c5d8d;
    }
    .page-link {
        position:relative;
        display:block;
        color:#4c5d8d;
        text-decoration:none;
        background-color:#fff;
        border:1px solid #dee2e6;
        transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out
    }
    .catalog-index .catalog-results {
        min-height: 600px;
        padding-bottom: 24px;
    }
    .catalog-index .field-productsearch-life_time_to {
        margin-bottom: 16px;
    }
    .catalog-index .field-productsearch-life_time_to .invalid-feedback {
        display: block;
        margin-top: 4px;
        line-height: 1.2;
    }
</style>
<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> px-2'>
    <h1 class="text-center">
        <?= $seo->h1 ?? $this->title ?>
        <?php if (isset($manufacture) && $manufacture): ?>
            <a href="/backend/seo/manufacture-update?id=<?= $manufacture->id ?>"
               class="btn d-inline rounded-pill ml-2"
               target="_blank"
               title="Редактировать SEO"
               aria-label="Редактировать SEO"
               style="font-size: 0.8rem; padding: 4px; background: red;">
                <i class="fa fa-edit m-1"></i>
            </a>
        <?php endif; ?>
    </h1>

    <div class="row">
        <div style="height: 330px;" class="col-lg-2 col-md-3 bg-light border py-1" id="form_div">
            <?= $this->render('_filter', [
                'model' => $searchModel,
            ]) ?>
        </div>
        <div class="col-md-10 catalog-results">

            <?= $this->render('_grid', [
                'dataProvider' => $dataProvider,
                //'searchModel' => $searchModel,
            ]) ?>

        </div>

    </div>

</div>



