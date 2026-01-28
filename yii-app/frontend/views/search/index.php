<?php
/* @var $this yii\web\View */
/* @var $q string */
/* @var $error string|null */

use common\helpers\Search;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = "Поиск по фразе «{$q}»";
$this->params['breadcrumbs'][] = $this->title;

$pageSize = 20;

?>
<style>
    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #4c5d8d;
        border-color: #4c5d8d;
    }
    .page-link {
        position: relative;
        display: block;
        color: #4c5d8d;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #dee2e6;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .page-link:hover {
        color: #fff;
        background-color: #4c5d8d;
        border-color: #4c5d8d;
    }
    .pagination-wrapper {
        margin-top: 20px;
        padding-top: 20px;
    }
    .search-inline-form {
        width: 100%;
        max-width: 520px;
    }
    .search-inline-form .input-group {
        display: flex;
        flex-wrap: nowrap;
        width: 100%;
    }
    .search-inline-form .form-control {
        flex: 1 1 auto;
        min-width: 0;
        height: 36px;
        padding-top: 3px;
        padding-bottom: 3px;
    }
    .search-inline-form .btn {
        height: 36px;
        padding-top: 3px;
        padding-bottom: 3px;
    }
</style>

<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> container'>

<?php if (!empty($error)): ?>
    <p class="alert alert-warning"><?= Html::encode($error) ?></p>
<?php else: ?>

<?php
    $search = new Search(['q' => $q]);
    
    $dpProducts = new ActiveDataProvider([
        'query' => $search->searchProduct(),
        'pagination' => [
            'pageSize' => $pageSize,
            'pageParam' => 'page-products',
        ],
    ]);
?>

<?php if ($dpProducts->totalCount > 0): ?>
    <h1><?= Html::encode($this->title) ?>  (<?= $dpProducts->totalCount ?>)</h1>
    <form action="/search" class="mt-2 mb-3 search-inline-form">
        <div class="input-group">
            <?= Html::textInput('q', $q, ['class' => 'form-control', 'placeholder' => 'Поиск по сайту']) ?>
            <button class="btn btn-outline-secondary" type="submit">Поиск</button>
        </div>
    </form>
<!--    <h4>Товары (--><?php //= $dpProducts->totalCount ?><!--)</h4>-->
    <?php foreach ($dpProducts->getModels() as $i => $v): ?>
        <?= $this->render('_snippet-product', [
            'q' => $q,
            'model' => $v,
            'isFirst' => $i === 0,
        ]) ?>
    <?php endforeach; ?>
    <div class="pagination-wrapper">
        <?= LinkPager::widget(['pagination' => $dpProducts->pagination]) ?>
    </div>
<?php endif; ?>

<?php /*
    // Поиск по новостям
    $dpNews = new ActiveDataProvider([
        'query' => $search->searchNews(),
        'pagination' => [
            'pageSize' => $pageSize,
            'pageParam' => 'page-news',
        ],
    ]);

    if ($dpNews->totalCount > 0):
        echo '<h4 class="mt-4">Новости (' . $dpNews->totalCount . ')</h4>';
        foreach ($dpNews->getModels() as $v) {
            echo $this->render('_snippet-news', [
                'q' => $q,
                'model' => $v,
                'type' => 'news',
            ]);
        }
        echo LinkPager::widget(['pagination' => $dpNews->pagination]);
    endif;

    // Поиск по статьям
    $dpApplications = new ActiveDataProvider([
        'query' => $search->searchApplications(),
        'pagination' => [
            'pageSize' => $pageSize,
            'pageParam' => 'page-articles',
        ],
    ]);

    if ($dpApplications->totalCount > 0):
        echo '<h4 class="mt-4">Статьи (' . $dpApplications->totalCount . ')</h4>';
        foreach ($dpApplications->getModels() as $v) {
            echo $this->render('_snippet-news', [
                'q' => $q,
                'model' => $v,
                'type' => 'applications',
            ]);
        }
        echo LinkPager::widget(['pagination' => $dpApplications->pagination]);
    endif;
*/ ?>

<?php endif; ?>

</div>



