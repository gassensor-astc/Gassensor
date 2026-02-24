<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $withHint bool */
?>

<?php
$lifeTime = $model->life_time;
$warrantyPeriod = $model->warranty_period;
$withHint = $withHint ?? false;

if ($lifeTime === null && $warrantyPeriod === null) {
    return;
}

$value = $lifeTime !== null && $lifeTime !== '' ? $lifeTime : '';
if ($warrantyPeriod !== null && $warrantyPeriod !== '') {
    $value = trim($value . ' (' . $warrantyPeriod . ')');
}
?>

<?php if ($withHint): ?>
    <span title="Срок жизни (гарантийный срок)"><?= $value ?></span>
<?php else: ?>
    <?= $value ?>
<?php endif; ?>
