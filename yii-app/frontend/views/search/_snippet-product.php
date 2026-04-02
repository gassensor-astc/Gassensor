<?php
/* @var $this yii\web\View */
/* @var $q string */
/* @var $model common\models\Product */
/* @var $isFirst bool|null */

use yii\helpers\Html;

$url = $model->url;
$pictUrl = $model->getPictUrl(false);

// Берём H1 из SEO, если есть, иначе name
$seo = $model->seo ?? null;
$title = ($seo && $seo->h1) ? $seo->h1 : $model->name;
$pattern = preg_quote(Html::encode($q), '/');
$description = $model->description ?? null;
$updatedDate = Yii::$app->formatter->asDate($model->updated_at, 'dd.MM.yyyy');
$borderTop = !empty($isFirst) ? 'border-top: 1px solid #e0e0e0;' : '';

?>

<div class="snippet-product d-flex align-items-center gap-3 p-2 mb" style="line-height: 1.2; border-bottom: 1px solid #e0e0e0; <?= $borderTop ?>">
    <div style="width: 60px; height: 50px; flex-shrink: 0;">
        <?php if ($pictUrl): ?>
            <a href="<?= $url ?>" target="_blank">
                <img src="<?= $pictUrl ?>" alt="<?= Html::encode($title) ?>" style="width: 60px; height: 50px;">
            </a>
        <?php endif; ?>
    </div>
    <div>
        <a href="<?= $url ?>" target="_blank">
            <?= preg_replace("/($pattern)/iu", "<b>$1</b>", Html::encode($title)) ?>
        </a>
        <?php if (!empty($description)): ?>
            <?php
            $short = mb_substr($description, 0, 150);
            if (mb_strlen($description) > 150) {
                $short .= '...';
            }
            ?>
            <div class="text-muted" style="margin-top: 4px;">
                <?= Html::encode($short) ?>
            </div>
        <?php endif; ?>
        <div class="text-muted" style="margin-top: 4px; font-size: 0.85em;">
            Изменен: <?= Html::encode($updatedDate) ?>
        </div>
    </div>
</div>
