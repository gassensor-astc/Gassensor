<?php
/* @var $this yii\web\View */

/* @var $model common\models\News */

use common\helpers\Tools;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $model->title;
$seo = $model->seo;
if ($seo) {
    $seo->registerMetaTags($this);
}
// Если SEO нет или description пустой — выводим meta description из контента/заголовка
$hasSeoDescription = $seo && trim((string) ($seo->description ?? '')) !== '';
if (!$hasSeoDescription) {
    $fallback = trim(strip_tags($model->content));
    $fallback = $fallback !== '' && mb_strlen($fallback) > 160 ? mb_substr($fallback, 0, 157) . '...' : $fallback;
    if ($fallback === '') {
        $fallback = $model->title;
    }
    if ($fallback !== '') {
        $this->registerMetaTag(['name' => 'description', 'content' => $fallback]);
    }
}
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => '/news'];
$breadcrumbLabel = ($seo && trim((string) ($seo->breadcrumb_text ?? '')) !== '') ? trim($seo->breadcrumb_text) : (($seo && $seo->h1 !== null && $seo->h1 !== '') ? $seo->h1 : $this->title);
$this->params['breadcrumbs'][] = $breadcrumbLabel;

$time = strtotime($model->date);

?>

<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> container'>

      <span class="date text-right">
          <?= date('d', $time) ?>
          <?= Tools::$months[date('n', $time) - 1] ?>
          <?= date('Y', $time) ?>
       </span>

    <h1>
        <?= $model->seo->h1 ?? $this->title ?>
        <?php if (Yii::$app->user->isAdmin()): ?>
            <?= Html::a('<i class="fa fa-edit m-1"></i>',
                ['backend/news/update',
                    'id' => $model->id,],
                ['class' => "btn d-inline rounded-pill",
                    'target' => "_blank",
                    'style' => "font-size: 0.8rem; padding: 4px; background: red;"
                ]) ?>
        <?php endif; ?>
    </h1>

    <div class="single-img w-100">
        <div class="news-content" id="news-photo-block">
            <div class="row w-100">
                <div class="col-md-412">
                    <div style="text-align: center;">
                        <?php
                        $url = $model->getPictUrl() ?: 'https://dummyimage.com/240x160/fff/aaa.png&text=no%20foto';
                        ?>
                        <?= Html::img($url, ['alt' => $model->title, 'loading' => "lazy", 'title' => $model->title]) ?>
                    </div>
                </div>
                <div class="col">

                    <?php foreach (glob($model->getUploadDir() . '/*.pdf') as $filename):
                        $url = $model->getUploadUrl() . '/' . basename($filename);
                        ?>
                        <div class="border m-1 p-1 rounded">
                            <a href="<?= $url ?>" target="_blank">
                                <i class="fa fa-2x fa-file-pdf"></i>
                                <?= basename($filename) ?>
                            </a>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>

            <p id="news-content">
                <?= $model->content ?>
                <a class="share" href="<?= Url::to(['/news']) ?>">Назад</a>
            </p>

        </div>

    </div>

</div>


