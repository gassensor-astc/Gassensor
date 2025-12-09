<?php

namespace frontend\controllers;

use common\models\News;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use common\helpers\Tools;
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\StringHelpers;

class AjaxController extends Controller
{
    public function actionMainnews()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->orderBy('date DESC'),
            'pagination' => [
                'pageSize' => 20,
                'pageSizeParam' => false
            ],
        ]);

        $html = '<p style="font-size: 16px; font-weight: bold">Новости</p>';

        foreach ($dataProvider->getModels() ?? [] as $model) {
            $time = strtotime($model->date);
            $html .= '
                <p>
                    <span class="new-block">' . date("d.m.Y", $time) . ' - </span>
                    <a href="/news/' . $model->slug . '">' . StringHelpers::shortText($model->title, 66) . '</a>
                </p>
            ';
        }

        $html .= '<br>
            <p><a class="share" href="' . Url::to(['/news']) . '">Читать все новости...</a></p>';

        $data = ['message' => 'success', 'code' => 200, 'html' => $html];

        return $this->asJson($data);
    }
}
