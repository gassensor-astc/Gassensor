<?php

/* @var $this yii\web\View */
/* @var $model common\models\Product */

use \yii\helpers\ArrayHelper;

?>
<div class="container">
    <div class="row">
    <?php

    $productRanges1 = $model->ProductRangesByPos(0);

    if ($productRanges1 && is_array($productRanges1)):

    ?>
         <div class="col-sm">

             <?= ArrayHelper::getValue($model, 'mainGaz.title') ?><br>

             <?php foreach ($productRanges1 as $v): ?>

             <?= $v->from ?> - <?= $v->to ?> <?= $v->unit ?>

             <?php endforeach; ?>

         </div>

    <?php endif; ?>

    <?php

    $productRanges2 = $model->ProductRangesByPos(1);

    if ($productRanges2 && is_array($productRanges2)):

    ?>
        <div class="col-sm">

            <?= ArrayHelper::getValue($model, 'mainGaz2.title') ?><br>

            <?php foreach ($productRanges2 as $v): ?>

            <?= $v->from ?> - <?= $v->to ?> <?= $v->unit ?>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php

    $productRanges3 = $model->ProductRangesByPos(2);

    if ($productRanges3 && is_array($productRanges3)):

    ?>
        <div class="col-sm">

            <?=ArrayHelper::getValue($model, 'mainGaz3.title') ?><br>

            <?php foreach ($productRanges3 as $v): ?>

            <?= $v->from ?> - <?= $v->to ?> <?= $v->unit ?>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php

    $productRanges4 = $model->ProductRangesByPos(3);

    if ($productRanges4 && is_array($productRanges4)):

    ?>
        <div class="col-sm">

            <?= ArrayHelper::getValue($model, 'mainGaz4.title') ?><br>

            <?php foreach ($productRanges4 as $v): ?>

            <?= $v->from ?> - <?= $v->to ?> <?= $v->unit ?>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php

    $productRanges5 = $model->ProductRangesByPos(4);

    if ($productRanges5 && is_array($productRanges5)):

    ?>
        <div class="col-sm">

            <?= ArrayHelper::getValue($model, 'mainGaz5.title') ?><br>

            <?php foreach ($productRanges5 as $v): ?>

            <?= $v->from ?> - <?= $v->to ?> <?= $v->unit ?>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    </div>
</div>
