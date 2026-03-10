<?php
/* @var $this yii\web\View */

/* @var $model common\models\Product */

use \yii\helpers\ArrayHelper;

$titles = [
    ArrayHelper::getValue($model, 'mainGaz.title'),
    ArrayHelper::getValue($model, 'mainGaz2.title'),
    ArrayHelper::getValue($model, 'mainGaz3.title'),
    ArrayHelper::getValue($model, 'mainGaz4.title'),
];

$hasAnyContent = false;
for ($i = 0; $i < 4; $i++) {
    $productRanges = $model->ProductRangesByPos($i);
    $title = $titles[$i] ?? null;
    if ($title) {
        $hasAnyContent = true;
        break;
    }
    if ($productRanges && is_array($productRanges)) {
        foreach ($productRanges as $v) {
            $emptyFrom = $v->from === null || $v->from === '' || (is_numeric($v->from) && (float)$v->from == 0);
            $emptyTo = $v->to === null || $v->to === '' || (is_numeric($v->to) && (float)$v->to == 0);
            if (!$emptyFrom || !$emptyTo) {
                $hasAnyContent = true;
                break 2;
            }
        }
    }
}

if (!$hasAnyContent) {
    echo '&mdash;';
    return;
}
?>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 8px 16px;">
        <?php
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
                            <?php
                            $emptyFrom = $v->from === null || $v->from === '' || (is_numeric($v->from) && (float)$v->from == 0);
                            $emptyTo = $v->to === null || $v->to === '' || (is_numeric($v->to) && (float)$v->to == 0);
                            if ($emptyFrom && $emptyTo) {
                                echo '&mdash;';
                            } else {
                                echo $v->from . ' &ndash; ' . $v->to . ($v->unit ? ' ' . $v->unit : '');
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
</div>
