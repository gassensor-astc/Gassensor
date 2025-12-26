<?php
/* @var $this yii\web\View */

use common\models\Seo;
use yii\helpers\Html;

$seo = Seo::findOne(['type' => Seo::TYPE_APPLICATIONS])->registerMetaTags($this);
$this->params['breadcrumbs'][] = $this->title;
$months = [
    1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
    'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
];
?>

<style>
    .new-grid-3 {
        display: grid;
        grid-row-gap: 40px;
        grid-template-columns: repeat(3, 1fr);
        position: relative;
    }
    .new-grid-item {
        gap: 5px;
        display: flex;
        flex-direction: column;
    }
    .new-preview {
        opacity: 1;
        transition: opacity 300ms;
        border-radius: 8px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 381px;
        height: 240px;
    }
    .new-grid-item-a {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .new-grid-item-title {
        padding-left: 15px;
        padding-right: 15px;
        flex-direction: row;
        display: flex;
    }
    .new-grid-item-hrefs {
        flex-direction: row;
        display: flex;
    }
</style>

<div class='<?= $this->context->id ?>-<?= $this->context->action->id ?> container'>
    <h1 class="text-center"><?= $seo->h1 ?></h1>

    <div class="new-grid-3 mb-4">
        <?php foreach ($all ?? [] as $application): ?>
            <div class="new-grid-item">
                <div class="new-grid-item-hrefs">
                    <a href="/applications/<?=$application->slug?>" class="new-grid-item-a">
                        <span class="new-preview" style="background-image: url('<?=$application->preview?>');"></span>
                        <span class="new-grid-item-title">
                            <?=$application->title?>
                        </span>
                    </a>
                </div>
                <div class="new-grid-item-title">
                    <?=date('d.m.Y', $application->created_at)?>
                    <i class="fa fa-eye" aria-hidden="true" style="margin-left: 15px; margin-top: 4px; margin-right: 4px;"></i> <?=$application->views?>
                </div>
                <?php if (Yii::$app->user->isAdmin()): ?>
                    <?= Html::a('<i class="fa fa-edit m-1"></i>',
                        ['backend/applications/update',
                            'id' => $application->id,],
                        ['class' => "btn d-inline rounded-pill",
                            'target' => "_blank",
                            'style' => "font-size: 0.8rem; padding: 4px; background: red; max-width: 60px; width: 60px; min-width: 60px; position: relative; top: -250px;"
                        ]) ?>

                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>