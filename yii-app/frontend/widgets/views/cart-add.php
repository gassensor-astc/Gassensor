<?php
/* @var $this yii\web\View */
/* @var $formAdd common\components\cart\AddToCartForm */

/* @var $hiddenCount bool */

use common\widgets\bs5\ModalCustomHeader;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="add-product-to-cart-wrap">
    <?php $form = ActiveForm::begin(['action' => ['cart/add']]) ?>

    <?= $form->field($formAdd, 'productId')->hiddenInput(['id' => 'addtocartform-productid-p' . $model->id])->label(false) ?>

    <?php

    if ($tableGrid) {
        $countControl = $form->field($formAdd, 'count', [
            'template' => '<div class="row">
            <div class="col-md-6">
                {input}
            </div>
            <div class="col-md-6 text-right">
                <button type="submit" class="btn btn-primary p-2">Добавить в корзину</button>
            </div>
        </div>
        {error}{hint}'
        ]);
    } else {
        $countControl = $form->field($formAdd, 'count');
    }

    ?>

    <?php if ($hiddenCount): ?>
        <?= $countControl->hiddenInput(['id' => 'addtocartform-productid-' . $model->id])->label(false) ?>
    <?php else: ?>
        <?= $countControl->input('number', ['class' => 'cart-item-count'])->label('Кол-во') ?>

    <?php endif; ?>

    <?php if ($tableGrid === false): ?>
        <?= Html::submitButton('<span class="icon ion-md-basket"></span>', ['class' => 'btn btn-primary mt-1', 'style' => 'min-width: 50px;']) ?>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

    <?php ModalCustomHeader::begin([
        'title' => 'Корзина',
        'footer' => Html::button('Продолжить покупки', ['class' => 'btn btn-continue', 'data-bs-dismiss' => 'modal'])
            . Html::a('Оформить заказ', '/cart', ['class' => 'btn', 'onclick' => "ym(85084891,'reachGoal','CLICK_ON_CART')"])
    ]) ?>

    <table class="cart-item table">
        <tr>
            <th>Фото</th>
            <th>Наименование</th>
            <th>Кол-во, шт</th>
        </tr>
        <tr>
            <td>
                <?php
                $url = $model->getPictUrl() ?: '/i/no-photo.gif';
                ?>

                <div class="col">
                    <?= Html::img($url, [
                        'alt' => $model->name,
                        'loading' => "lazy",
                        'title' => $model->name,
                        'style' => 'width: 120px;',
                    ]) ?>
                </div>

            </td>
            <td><?= $model->name ?></td>
            <td>
                <?= Html::input('number', 'count', 1, ['style' => 'width: 100px;', 'class' => 'cart-item-count']) ?>
            </td>
        </tr>
    </table>

    <?php ModalCustomHeader::end(); ?>

</div>