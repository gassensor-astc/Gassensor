<?php
/* @var $this yii\web\View */

/* @var $model common\models\Product */

use \yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<div class="container" style="font-size: 12px; line-height: 1.25;">
    <?php
    $titles = [
        ArrayHelper::getValue($model, 'mainGaz.title'),
        ArrayHelper::getValue($model, 'mainGaz2.title'),
        ArrayHelper::getValue($model, 'mainGaz3.title'),
        ArrayHelper::getValue($model, 'mainGaz4.title'),
    ];

    for ($i = 0; $i < 4; $i++):
        $title = $titles[$i] ?? null;
        $cleanTitle = $title ? trim($title) : null;
        $ranges = $model->ProductRangesByPos($i);
        if (!$title && (!$ranges || !is_array($ranges))) {
            continue;
        }
        $rangeParts = [];
        if ($ranges && is_array($ranges)) {
            foreach ($ranges as $v) {
                $rangeParts[] = trim($v->from . ' - ' . $v->to . ' ' . $v->unit);
            }
        }
    ?>
        <div style="margin-bottom: 4px; white-space: normal;">
            <?php if ($cleanTitle): ?>
                <b><?= Html::encode($cleanTitle) ?>:</b><?= !empty($rangeParts) ? ' ' : '' ?>
            <?php endif; ?>
            <?php if (!empty($rangeParts)): ?>
                <?= Html::encode(implode(', ', $rangeParts)) ?>
            <?php endif; ?>
        </div>
    <?php endfor; ?>
</div>