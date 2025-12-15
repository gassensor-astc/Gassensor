<?php
/* @var $this yii\web\View */
/* @var $model common\models\Order */

?>
<style>
table, tr, th, td {
    border-collapse: collapse;
    border: 1px solid #444;
}

table {
    margin: 8px;
}

th, td {
    padding: 4px;
}

</style>
<h1>Создан заказ #<?= $model->id ?></h1>

Дата: <?= Yii::$app->formatter->asDateTime($model->created_at) ?>

<table class="table">
    <tr>
        <th>#</th>
        <th>Товар</th>
        <th>Кол-во</th>
    </tr>
    <?php foreach ($model->orderProducts as $k => $orderProduct):
        $id = $orderProduct->product->id;
    ?>
    <tr>
        <td><?= $k +1 ?></td>
        <td>
          <!-- backend
          <a href="https://gassensor.ru/backend/product/view?id=<?= $id ?>"> #<?= $id ?> </a>
           -->
          <a href="https://gassensor.ru<?= $orderProduct->product->url ?>">#<?= $id ?></a>
          &nbsp;<b><?= $orderProduct->product->name ?></b>
        </td>
        <td><?= $orderProduct->count ?></td>
    </tr>
    <?php endforeach; ?>

</table>

<hr />

<h3>Клиент</h3>
Получатель: <?= $model->name ?>
<br />
E-mail: <?= $model->email ?>
<br />
Телефон: <?= $model->phone ?>
<br />
Информация о доставке: <?= $model->delivery_info ?>
<br />
Комментарий: <?= $model->comment ?>
