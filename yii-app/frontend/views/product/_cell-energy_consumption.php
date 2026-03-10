<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$from = $model->energy_consumption_from;
$to = $model->energy_consumption_to;
$isEmpty = ($from === null || $from === '' || trim((string)$from) === '' || (is_numeric($from) && (float)$from == 0))
    && ($to === null || $to === '' || trim((string)$to) === '' || (is_numeric($to) && (float)$to == 0));

if ($isEmpty) {
    echo '&mdash;';
    return;
}
$unit = $model->energy_consumption_unit !== null && (string)$model->energy_consumption_unit !== '' ? ' ' . $model->energy_consumption_unit : '';
?>
<?= $model->energy_consumption_from ?>&ndash;<?= $model->energy_consumption_to ?><?= $unit ?>