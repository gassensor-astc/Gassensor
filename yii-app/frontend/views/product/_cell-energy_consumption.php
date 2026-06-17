<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$analogFrom = $model->energy_consumption_analog_from;
$analogTo = $model->energy_consumption_analog_to;
$analogUnit = trim((string)($model->energy_consumption_analog_unit ?? ''));

$digitalFrom = $model->energy_consumption_digital_from;
$digitalTo = $model->energy_consumption_digital_to;
$digitalUnit = trim((string)($model->energy_consumption_digital_unit ?? ''));

$hasAnalog = $model->analog
    && $analogFrom !== null
    && $analogTo !== null
    && trim((string)$analogFrom) !== ''
    && trim((string)$analogTo) !== '';

$hasDigital = $model->digital
    && $digitalFrom !== null
    && $digitalTo !== null
    && trim((string)$digitalFrom) !== ''
    && trim((string)$digitalTo) !== '';

if (!$hasAnalog && !$hasDigital) {
    echo '&mdash;';
    return;
}
?>
<div>
    <?php if ($hasAnalog): ?>
        <div>Аналоговый: <?= $analogFrom ?>&ndash;<?= $analogTo ?><?= $analogUnit !== '' ? ' ' . $analogUnit : '' ?></div>
    <?php endif; ?>
    <?php if ($hasDigital): ?>
        <div>Цифровой: <?= $digitalFrom ?>&ndash;<?= $digitalTo ?><?= $digitalUnit !== '' ? ' ' . $digitalUnit : '' ?></div>
    <?php endif; ?>
</div>
