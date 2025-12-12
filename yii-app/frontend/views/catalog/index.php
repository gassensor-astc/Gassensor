<?php

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $seo common\models\Seo|null */

use common\models\Seo;
use frontend\assets\AppAsset;

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('lib/yii2AjaxRequest.js', ['depends' => AppAsset::class]);

if (!$seo) {
    $seo = Seo::findOne(['type' => Seo::TYPE_PAGE_CATALOG]);
    $this->title = $seo->title;
}
$seo->registerMetaTags($this);

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
</style>
<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> px-2'>
    <h1 class="text-center"><?= $seo->h1 ?? $this->title ?></h1>

    <div class="row">
        <div style="height: 330px;" class="col-lg-2 col-md-3 bg-light border py-1" id="form_div">
            <?= $this->render('_filter', [
                'model' => $searchModel,
            ]) ?>
        </div>
        <div class="col-md-10">

            <?= $this->render('_grid', [
                'dataProvider' => $dataProvider,
                //'searchModel' => $searchModel,
            ]) ?>

        </div>

    </div>

</div>



