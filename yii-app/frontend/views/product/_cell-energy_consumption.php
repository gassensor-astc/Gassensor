<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$analogFrom = trim((string)($model->energy_consumption_analog_from ?? ''));
$analogTo = trim((string)($model->energy_consumption_analog_to ?? ''));
$analogUnit = trim((string)($model->energy_consumption_analog_unit ?? ''));

$digitalFrom = trim((string)($model->energy_consumption_digital_from ?? ''));
$digitalTo = trim((string)($model->energy_consumption_digital_to ?? ''));
$digitalUnit = trim((string)($model->energy_consumption_digital_unit ?? ''));

$hasAnalog = $analogFrom !== '' || $analogTo !== '' || $analogUnit !== '';
$hasDigital = $digitalFrom !== '' || $digitalTo !== '' || $digitalUnit !== '';

if (!$hasAnalog && !$hasDigital) {
    echo '&mdash;';
    return;
}

function buildEnergyValue($from, $to, $unit) {
    $parts = [];
    if ($from !== '' && $to !== '') {
        $parts[] = $from . '&ndash;' . $to;
    } elseif ($from !== '') {
        $parts[] = $from;
    } elseif ($to !== '') {
        $parts[] = $to;
    }
    if ($unit !== '') {
        $parts[] = $unit;
    }
    return implode(' ', $parts);
}

$analogValue = buildEnergyValue($analogFrom, $analogTo, $analogUnit);
$digitalValue = buildEnergyValue($digitalFrom, $digitalTo, $digitalUnit);

if ($hasAnalog && $hasDigital): ?>
<div class="d-flex gap-4">
    <div>
        <div class="text-muted small">Аналоговый</div>
        <div><?= $analogValue ?></div>
    </div>
    <div>
        <div class="text-muted small">Цифровой</div>
        <div><?= $digitalValue ?></div>
    </div>
</div>
<?php elseif ($hasAnalog): ?>
    <?= $analogValue ?>
<?php else: ?>
    <?= $digitalValue ?>
<?php endif; ?>
