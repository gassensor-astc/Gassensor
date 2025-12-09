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
          $product = Product::findOne($v->product_id);
          ?>
        <?= Html::a($v->product_info, '/product/' . $product->slug, ['target' => '_blank', 'data-pjax' => 0]) ?>
        (<?= $v->count ?> шт)
      </li>
    <?php endforeach; ?>
</ul>

