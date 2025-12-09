<?php
use common\models\Order;
use common\models\Product;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
?>


<ul>
    <?php foreach ($model->orderProducts as $v): ?>
      <li>
          <?php
          $myProduct = Product::findOne($v->product_id);
          $mySlug = '';
          if ($myProduct) {
              $mySlug = '/product/' . $myProduct->slug;
          }
          ?>
        <?= Html::a($v->product_info, $mySlug, ['target' => '_blank', 'data-pjax' => 0]) ?>
        (<?= $v->count ?> шт)
      </li>
    <?php endforeach; ?>
</ul>

