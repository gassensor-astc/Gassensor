<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$analogFrom = $model->energy_consumption_analog_from;
$analogTo = $model->energy_consumption_analog_to;
$analogUnit = trim((string)($model->energy_consumption_analog_unit ?? ''));

$digitalFrom = $model->energy_consumption_digital_from;
$digitalTo = $model->energy_consumption_digital_to;
$digitalUnit = trim((string)($model->energy_consumption_digital_unit ?? ''));

$hasAnalog = $analogFrom !== null
    && $analogTo !== null
    && trim((string)$analogFrom) !== ''
    && trim((string)$analogTo) !== ''
    && $analogUnit !== '';

$hasDigital = $digitalFrom !== null
    && $digitalTo !== null
    && trim((string)$digitalFrom) !== ''
    && trim((string)$digitalTo) !== ''
    && $digitalUnit !== '';

if (!$hasAnalog && !$hasDigital) {
    echo '-';
    return;
}

if ($hasAnalog && $hasDigital): ?>
<div class="d-flex gap-4">
    <div>
        <div class="text-muted small">Аналоговый выход</div>
        <div>    <?= $analogFrom ?>-<?= $analogTo ?> <?= $analogUnit ?></div>
    </div>
    <div>
        <div class="text-muted small">Цифровой выход</div>
        <div><?= $digitalFrom ?>-<?= $digitalTo ?> <?= $digitalUnit ?></div>
    </div>
</div>
<?php elseif ($hasAnalog): ?>
    <?= $analogFrom ?>-<?= $analogTo ?> <?= $analogUnit ?>
<?php else: ?>
    <?= $digitalFrom ?>-<?= $digitalTo ?> <?= $digitalUnit ?>
<?php endif; ?>
