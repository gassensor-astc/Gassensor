<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$hasFirst = $model->first === 1 && trim((string)($model->sensitivity_first ?? '')) !== '';
$hasAnalog = $model->analog === 1 && trim((string)($model->sensitivity_analog ?? '')) !== '';
$hasDigital = $model->digital === 1 && trim((string)($model->sensitivity_digital ?? '')) !== '';

if (!$hasFirst && !$hasAnalog && !$hasDigital) {
    echo '&mdash;';
    return;
}
?>

<div class="d-flex gap-1">
    <?php if ($hasFirst): ?>
        <div><?= $model->sensitivity_first ?></div>
    <?php endif; ?>

    <?php if ($hasAnalog): ?>
        <?php if ($hasFirst): ?>
            <div style="max-width: 10px;">/</div>
        <?php endif; ?>
        <div><?= $model->sensitivity_analog ?></div>
    <?php endif; ?>

    <?php if ($hasDigital): ?>
        <?php if ($hasFirst || $hasAnalog): ?>
            <div style="max-width: 10px;">/</div>
        <?php endif; ?>
        <div><?= $model->sensitivity_digital ?></div>
    <?php endif; ?>
</div>
