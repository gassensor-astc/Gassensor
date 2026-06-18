<?php
/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm|null  */

use yii\helpers\Html;
use common\models\Product;

?>


<div class="card d-block p-2 bg-light">

    <div class="d-flex align-items-center">
        <h3>Картинка</h3>
        <?= Html::a('<i class="fa fa-trash-o"></i>', ['delete-pict', 'id' => $model->id], ['class' => 'btn btn-sm btn-danger ms-3']) ?>
    </div>

    <?php if ($model->pictUrl): ?>

      <?php if (is_file($model->pictPath)): ?>
        <?= Html::img($model->pictUrl, ['style' => 'max-height: 200px; width: auto;']) ?>
      <?php else: ?>
        БД ссылается на файл, который не найден на диске <i><?= $model->pictPath ?></i>
      <?php endif; ?>

    <?php else: ?>
      Не найдена
    <?php endif; ?>

    <?php if ($form ?? null): ?>
        <hr />
        Загрузить:
        <?= $form->field($model, 'uploadPict')->fileInput(['accept' => '.jpg,.png,.gif'])->label(false) ?>
    <?php endif; ?>

</div>

<?php foreach (Product::getPdfIndexes() as $v): ?>
<div class="card d-block my-2 p-2 bg-light">

    <div class="d-flex align-items-center mt-4">
      <h3>PDF<?= $v ?></h3>
      <?= Html::a('<i class="fa fa-trash-o"></i>', ['delete-pdf', 'id' => $model->id, 'i' => $v], ['class' => 'btn btn-sm btn-danger ms-3']) ?>
    </div>

    <?php if ($url = $model->getPdfUrl($v)):
        $attr = Product::getPdfAttr($v);
    ?>
      <?= Html::a($model->$attr, $url, ['target' => '_blank', 'download' => $model->$attr]) ?>

      <br />

      <?php if ($model->isFilePdfExists($v)): ?>
        <embed src="<?= $url ?>" type="application/pdf" style="width: 100%; height: 600px;" />
      <?php else: ?>
        БД ссылается на файл, который не найден на диске.
      <?php endif; ?>

    <?php else: ?>
      Не найден
    <?php endif; ?>

    <?php if ($form ?? null): ?>
        <hr />
        Загрузить:
        <?= $form->field($model, 'uploadPdf' . $v)->fileInput(['accept' => '.pdf'])->label(false) ?>
    <?php endif; ?>

</div>

<?php endforeach; ?>


