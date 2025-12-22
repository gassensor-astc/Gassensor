<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Product;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Слайдер');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index', 'sort' => '-id',]];
?>
<?php $form = ActiveForm::begin(['id' => 'form-slider']); ?>
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <?= $form->field($model, 'picture')->fileInput(['accept' => '.jpg,.png,.gif'])->label('Картинка') ?>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <?= $form->field($model, 'page')->textInput()->label('Страница') ?>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <?= $form->field($model, 'description')->textarea()->label('Текст') ?>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="form-actions">
            <div class="row">
                <div class="col-md-12">
                    <?= Html::submitButton(Yii::t('app', 'Добавить'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<table cellspacing="1" cellpadding="1" border="1" style="border: 1px solid black; margin-top: 50px; width: 100%;">
    <?php
    foreach ($slides as $slide) {
        echo '
        <tr>
            <td style="padding: 10px; border: 1px solid black;"><img src="' . $slide->picture . '" style="width: 200px;" /></td>
            <td style="padding: 10px; border: 1px solid black;">' . $slide->page . '</td>
            <td style="padding: 10px; border: 1px solid black;">' . $slide->description . '</td>
            <td style="padding: 10px; border: 1px solid black;"><a href="/backend/slider/edit?id='.$slide->id.'">Редактировать</a></td>
            <td style="padding: 10px; border: 1px solid black;"><a href="/backend/slider/delete?id='.$slide->id.'">Удалить</a></td>        
        </tr>
        ';
    }
    ?>
</table>

