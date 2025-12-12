<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

use frontend\widgets\CartAddWidget;

$formAdd = new common\components\cart\AddToCartForm(['productId' => $model->id, 'count' => 1]);

?>

<?= CartAddWidget::widget([
    'formAdd' => $formAdd,
    'model' => $model,
    //'button_label' => 'Купить',
    ]) ?>


