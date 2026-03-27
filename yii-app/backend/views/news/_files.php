<?php

use common\helpers\Tools;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\News */

?>

<?php foreach ($model->getUploadFilenames() as $filename):

    $url = $model->getUploadUrl() . '/' . basename($filename);
    $basename = basename($filename);

?>

    <?php if (Tools::isPict($url)): ?>
    <div style="display: inline-block; margin: 8px; padding: 8px; border: 1px solid #ddd; text-align: center; vertical-align: top;">
        <img src="<?= $url ?>" style="width: 120px; display: block; margin-bottom: 8px;"/>
        <div style="font-size: 12px; margin-bottom: 6px; word-break: break-all; max-width: 120px;">
            <?= Html::encode($basename) ?>
        </div>
        <?= Html::a(
            'Удалить',
            ['delete-file', 'id' => $model->id, 'basename' => $basename],
            [
                'style' => 'display: inline-block; padding: 6px 10px; background: #d9534f; color: #fff; text-decoration: none; border-radius: 3px;',
                'data-confirm' => 'Удалить файл ' . $basename . '?',
            ]
        ) ?>
    </div>
    <?php endif; ?>

<?php endforeach; ?>
