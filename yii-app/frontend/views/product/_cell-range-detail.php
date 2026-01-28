<?php
/* @var $this yii\web\View */

/* @var $model common\models\Product */

use \yii\helpers\ArrayHelper;

?>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 8px 16px;">
        <?php
        $titles = [
            ArrayHelper::getValue($model, 'mainGaz.title'),
            ArrayHelper::getValue($model, 'mainGaz2.title'),
            ArrayHelper::getValue($model, 'mainGaz3.title'),
            ArrayHelper::getValue($model, 'mainGaz4.title'),
        ];
        for ($i = 0; $i < 4; $i++):
            $productRanges = $model->ProductRangesByPos($i);
            $title = $titles[$i] ?? null;
            if (!$title && (!$productRanges || !is_array($productRanges))) {
                continue;
            }
        ?>
            <div>
                <?php if ($title): ?>
                    <div style="font-weight: 600; margin-bottom: 2px;"><?= $title ?></div>
                <?php endif; ?>
                <?php if ($productRanges && is_array($productRanges)): ?>
                    <?php foreach ($productRanges as $v): ?>
                        <div style="white-space: normal;">
                            <?= $v->from ?> - <?= $v->to ?> <?= $v->unit ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
</div>
