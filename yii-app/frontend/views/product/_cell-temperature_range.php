<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$from = $model->temperature_range_from;
$to = $model->temperature_range_to;
$isEmpty = ($from === null || $from === '' || trim((string)$from) === '' || (is_numeric($from) && (float)$from == 0))
    && ($to === null || $to === '' || trim((string)$to) === '' || (is_numeric($to) && (float)$to == 0));

if ($isEmpty) {
    echo '&mdash;';
    return;
}
?>
<?= $model->temperature_range_from ?>C°..
<?= $model->temperature_range_to > 0 ? '+' : '' ?>
<?= $model->temperature_range_to ?>C°
