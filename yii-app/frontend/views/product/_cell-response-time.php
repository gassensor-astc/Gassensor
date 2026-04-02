<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$raw = $model->response_time;
$trimmed = trim((string)$raw);
$isEmpty = $raw === null || $raw === '' || $trimmed === '' || $trimmed === '0' || (is_numeric($trimmed) && (float)$trimmed == 0);

if ($isEmpty) {
    echo '&mdash;';
    return;
}
?>
<?= $model->response_time ?><?= $model->response_time_unit !== null && (string)$model->response_time_unit !== '' ? ' ' . $model->response_time_unit : '' ?>