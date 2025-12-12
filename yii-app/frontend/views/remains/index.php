<?php
/* @var $this yii\web\View */

use common\models\Seo;
use common\models\Setting;
use common\helpers\StringHelpers;
use yii\widgets\LinkPager;

$seo = Seo::findOne(['type' => Seo::TYPE_PAGE_REMAINS])->registerMetaTags($this);

$this->params['breadcrumbs'][] = $this->title;

$filename = './upload/' . Setting::getSensorsList();
$filename_dwnld = '/upload/sensors_list.xlsx';
?>
<style>
    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #4c5d8d;
        border-color: #4c5d8d;
    }
    .page-link {
        position:relative;
        display:block;
        color:#4c5d8d;
        text-decoration:none;
        background-color:#fff;
        border:1px solid #dee2e6;
        transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out
    }
</style>
<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> container'>
    <h1><?= $seo->h1 ?></h1>

    <div class="row">
        <div class="col-12 col-sm-12">

            <?php if (file_exists($filename)): ?>

            <p><a class="share" target="_blank" href="<?= $filename_dwnld ?>">Скачать список доступной продукции в формате Excel</a> (<?= StringHelpers::humanFilesize(filesize($filename),0)?>)</p>

            <?php endif ?>

            <table class="table  table-striped table-bordered">
                <thead>
                <tr>
                    <th style="text-align:center;">Позиция</th>
                    <th style="text-align:center;">Газ</th>
                    <th style="text-align:center;" class="text-nowrap">Количество</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($sensors ?? [] as $sensor): ?>
                    <tr>
                        <td>
                            <?php if ($sensor->link): ?>
                                <a href="<?= $sensor->link ?>"><?= $sensor->name ?></a>
                            <?php else: ?>
                                <?= $sensor->name ?>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center;"><?= $sensor->name2 ?></td>
                        <td style="text-align:right;"><?= $sensor->count ?></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        </div>

        <div class="col-12 mt-3">
            <?= LinkPager::widget([
                    'pagination' => $pages,
            ]); ?>
        </div>
    </div>
</div>
