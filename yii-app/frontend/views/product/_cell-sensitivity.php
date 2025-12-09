<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */

?>

<div class="container">
    <div class="flex-row gap-1">

        <?php if ($model->first === 1): ?>

            <div class="">

                <?php /* Yii::t('app', 'First') */?>

                <?= $model->sensitivity_first ?>

            </div>

        <?php endif; ?>

        <?php if ($model->analog === 1): ?>

            <?php
            if ($model->sensitivity_first) {
                echo '<div class="" style="max-width: 10px;">&#47;</div>';
            }
            ?>

            <div class="">

                <?php /* Yii::t('app', 'Analog') */?>

                <?= $model->sensitivity_analog ?>

            </div>

        <?php endif; ?>

        <?php if ($model->digital === 1): ?>

            <?php
            if ($model->sensitivity_first || $model->sensitivity_analog) {
                echo '<div class="" style="max-width: 10px;">&#47;</div>';
            }
            ?>

            <div class="">

                <?php /* Yii::t('app', 'Digital') */?>

                <?= $model->sensitivity_digital ?>

            </div>

        <?php endif; ?>

    </div>
</div>
