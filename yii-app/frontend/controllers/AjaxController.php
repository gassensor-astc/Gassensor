<?php

namespace frontend\controllers;

use common\models\Applications;
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
        $news = News::find()->orderBy('date DESC')->limit(20)->all();
        $articles = Applications::find()->orderBy('created_at DESC')->limit(20)->all();

        $items = [];
        foreach ($news as $model) {
            $items[] = [
                'date' => strtotime($model->date),
                'title' => $model->title,
                'url' => '/news/' . $model->slug,
                'type' => 'news',
            ];
        }
        foreach ($articles as $model) {
            $items[] = [
                'date' => (int)$model->created_at,
                'title' => $model->title,
                'url' => '/applications/' . $model->slug,
                'type' => 'article',
            ];
        }

        usort($items, function ($a, $b) {
            return $b['date'] - $a['date'];
        });

        $items = array_slice($items, 0, 20);

        $html = '<p style="font-size: 16px; font-weight: bold">Новости и статьи</p>';

        foreach ($items as $item) {
            $html .= '
                <p>
                    <span class="new-block">' . date("d.m.Y", $item['date']) . ' - </span>
                    <a href="' . $item['url'] . '">' . StringHelpers::shortText($item['title'], 66) . '</a>
                </p>
            ';
        }

        $html .= '<br>
            <p><a class="share" href="' . Url::to(['/news']) . '">Читать все новости...</a></p>';

        $data = ['message' => 'success', 'code' => 200, 'html' => $html];

        return $this->asJson($data);
    }
}
