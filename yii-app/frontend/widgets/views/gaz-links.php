<?php
/* @var $this yii\web\View */

use common\models\Gaz;
use frontend\widgets\gazConverter\GazConverterWidget;
use yii\helpers\Url;

?>

<!-- Модальное окно -->
<div class="modal fade" id="convertModal" tabindex="-1" aria-labelledby="convertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title fs-5" id="convertModalLabel">Конвертер газа</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <?= GazConverterWidget::widget(['title' => false]) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center">
    <button style="width: 100%;" class="btn btn-primary mb-2 popup-open" data-bs-toggle="modal"
            data-bs-target="#convertModal">Конвертер газа
    </button>
</div>

<a href="<?= Url::to(['/remains']) ?>" class="sensors-count card p-2 bg-light">
    <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center">
        <h3 class="text-center">Наличие<br>сенсоров на складе</h3>
        <img src="./i/logo-excel.svg" alt="Логотип Excel" class="d-block text-center">
    </div>
</a>

<div class='gaz-links-widget card p-2 d-block mt-2' id="navbarScroll">

    <p style="font-size: 16px; font-weight: bold">Датчики и сенсоры по типу газа</p>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">
                Газы
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">
                Фреоны (хладагенты)
            </button>
        </li>
    </ul>
    <div class="tab-content bg-white border border-top-0" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

            <div class="row m-1 sensors-table overflow-auto rounded-1" style="height: 1500px;">
                <?php foreach (Gaz::find()->notFreons()->orderBy('title')->all() as $model): ?>
                    <?= $this->render('_item', ['model' => $model]) ?>
                <?php endforeach; ?>
            </div>

        </div>

        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row m-1">
                <?php foreach (Gaz::find()->freons()->orderBy('title')->all() as $model): ?>
                    <?= $this->render('_item', ['model' => $model]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>