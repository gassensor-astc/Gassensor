<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$f = trim((string)$model->formfactor);
$u = trim((string)($model->formfactor_unit ?? ''));
$isEmpty = $f === '' && $u === '';

if ($isEmpty) {
    echo '&mdash;';
    return;
}
?>
<?= $model->formfactor ?><?= $u !== '' ? ' ' . $model->formfactor_unit : '' ?>