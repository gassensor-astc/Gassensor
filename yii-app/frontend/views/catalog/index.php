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
}
if ($seo && (string)$seo->title !== '') {
    $this->title = $seo->title;
}
if (isset($canonicalUrl)) {
    $seo->setAttribute('url_canonical', null);
}
$seo->registerMetaTags($this);
if (!empty($canonicalUrl)) {
    $this->params['customCanonical'] = true;
    $this->params['canonicalUrl'] = $canonicalUrl;
}

/* @var $request \yii\web\Request */
$req = Yii::$app->request;

// Номер страницы для H1 каталога.
// Логика синхронизирована с Seo::registerMetaTags():
// - /catalog/slug и /catalog/slug?page=1 считаем первой страницей (без суффикса);
// - /catalog/slug?page=2 → Страница 2 и т.д.
$page = 1;
$pageParam = $req->get('page', null);
if ($pageParam !== null && $pageParam !== '') {
    $p = (int)$pageParam;
    $page = $p <= 1 ? 1 : $p;
} elseif (isset($this->context, $this->context->actionParams['page'])) {
    $p = (int)$this->context->actionParams['page'];
    if ($p >= 1) {
        $page = $p;
    }
}
if ($page < 1) {
    $page = 1;
}

// Базовый текст H1 (из SEO или из title)
$catalogH1 = $seo->h1 ?? $this->title;
if ($page > 1 && $catalogH1 !== null && $catalogH1 !== '') {
    // Если в H1 ещё нет ", Страница N" в конце — добавляем.
    if (!preg_match('/,?\s*Страница\s+\d+$/u', $catalogH1)) {
        $catalogH1 = rtrim($catalogH1, " \t\n.") . ', Страница ' . $page;
    }
}

$js =
    <<<JS

$('#catalog-filter-form').on('submit', function(e) {
    var \$sel = $(this).find('select[name="ProductSearch[gaz_id]"]');
    var slug = \$sel.find('option:selected').data('slug');
    if (slug) {
        e.preventDefault();
        var params = $(this).serializeArray()
            .filter(function(o) { return o.name !== 'ProductSearch[gaz_id]'; })
            .filter(function(o) {
                if (o.value === '' || o.value === undefined) return false;
                if (o.name === 'ProductSearch[response_time_to]' && o.value === '5000') return false;
                return true;
            });
        var qs = $.param(params);
        window.location = '/catalog/' + encodeURIComponent(slug) + (qs ? '?' + qs : '');
        return false;
    }
});

$("select").change(function(){
    if ($(this).attr('id') !== 'productsearch-selectedsignaltypes') {
        $(this).closest("form").submit();
    }     else {
        setTimeout(()=>{
            let h = $('.select2-selection--multiple').outerHeight();
            if (h < 45) {
                $('#form_div').css('height', 'fit-content');
            } else {
                $('#form_div').css('height', 'fit-content');
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
            if (h < 45) {
                $('#form_div').css('height', 'fit-content');
            } else {
                $('#form_div').css('height', 'fit-content');
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
    /* На малой высоте экрана (14" и т.п.) — не даём футеру схлопнуть блок поиска */
    @media (max-height: 900px) {
        .catalog-index {
            padding-bottom: 2rem;
        }
    }
    .catalog-index .field-productsearch-life_time_to {
        margin-bottom: 16px;
    }
    .catalog-index #form_div {
        align-self: flex-start !important;
        height: fit-content !important;
    }
    .catalog-index #catalog-search-btn-block {
        width: 100%;
    }
    .catalog-index #catalog-search-btn-block .btn-form {
        width: 100%;
        display: block;
        box-sizing: border-box;
    }
    .catalog-index .field-productsearch-life_time_to .invalid-feedback {
        display: block;
        margin-top: 4px;
        line-height: 1.2;
    }
</style>
<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> px-2'>
    <h1 class="text-center">
        <?= $catalogH1 ?>
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
        <div style="height: fit-content; align-self: flex-start;" class="col-lg-2 col-md-3 bg-light border py-1" id="form_div">
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
