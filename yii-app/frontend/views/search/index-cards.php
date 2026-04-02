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
    .search-cards .card {
        border: 1px solid #eee;
        transition: box-shadow 0.2s;
    }
    .search-cards .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .search-cards .btn-search {
        background-color: #4c5d8d;
        border-color: #4c5d8d;
        color: #fff;
    }
    .search-cards .btn-search:hover {
        background-color: #3d4d7a;
        border-color: #3d4d7a;
        color: #fff;
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
            'pageParam' => 'page',
        ],
    ]);
?>

<?php if ($dpProducts->totalCount > 0): ?>
    <h1><?= Html::encode($this->title) ?> (<?= $dpProducts->totalCount ?>)</h1>
    
    <div class="row search-cards">
        <?php foreach ($dpProducts->getModels() as $v): ?>
            <?= $this->render('_snippet-product-card', [
                'q' => $q,
                'model' => $v,
            ]) ?>
        <?php endforeach; ?>
    </div>
    
    <div class="pagination-wrapper">
        <?= LinkPager::widget(['pagination' => $dpProducts->pagination]) ?>
    </div>
<?php else: ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="alert alert-info">По вашему запросу ничего не найдено.</p>
<?php endif; ?>

<?php endif; ?>

</div>
