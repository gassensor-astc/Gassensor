<?php

use common\helpers\Tools;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $showActions bool */
/* @var $showNames bool */

?>

<?php
$showActions = $showActions ?? true;
$showNames = $showNames ?? $showActions;
foreach ($model->getUploadFilenames() as $filename):

    $url = $model->getUploadUrl() . '/' . basename($filename);
    $basename = basename($filename);

?>

    <?php if (Tools::isPict($url)): ?>
    <div style="display: inline-block; margin: 8px; padding: 8px; border: 1px solid #ddd; text-align: center; vertical-align: top;">
        <img src="<?= $url ?>" style="width: 120px; display: block; margin-bottom: 8px;"/>
        <?php if ($showNames): ?>
            <div style="font-size: 12px; margin-bottom: 6px; word-break: break-all; max-width: 120px;">
                <?= Html::encode($basename) ?>
            </div>
        <?php endif; ?>
        <?php if ($showActions): ?>
            <div>
                <?= Html::a(
                'Удалить',
                ['delete-file', 'id' => $model->id, 'basename' => $basename],
                [
                    'style' => 'display: inline-block; padding: 6px 10px; background: #d9534f; color: #fff; text-decoration: none; border-radius: 3px;',
                    'data-confirm' => 'Удалить файл ' . $basename . '?',
                ]
                ) ?>
                <a
                    href="#"
                    class="btn btn-xs js-insert-news-image"
                    style="margin-left: 6px; display: inline-block; padding: 6px 10px; background: #5cb85c; color: #fff; text-decoration: none; border-radius: 3px;"
                    data-url="<?= Html::encode($url) ?>"
                >
                    Вставить в контент
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

<?php endforeach; ?>
