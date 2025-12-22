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
<?php $form = ActiveForm::begin(['id' => 'form-slider', 'action' => '/backend/slider/save']); ?>
<input type="hidden" name="sid" value="<?=$model->id?>">
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <img src="<?=$model->picture?>" style="width: 200px;" />
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
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
