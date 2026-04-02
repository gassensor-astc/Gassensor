<?php

namespace frontend\controllers;

use common\helpers\BotDetector;
use common\models\News;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ListView;

class NewsController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->orderBy('date DESC'),
            'pagination' => [
                'pageSize' => 12,
                'pageSizeParam' => false,
                // Не добавляем ?page=1 в URL – первая страница всегда без параметра
                'forcePageParam' => false,
                // Явно фиксируем маршрут, чтобы пагинация вела на /news, а не /news/index
                'route' => '/news',
            ],
        ]);

        $listView = new ListView([
            'dataProvider' => $dataProvider,
        ]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'listView' => $listView,
        ]);
    }

    /**
     * @param string $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSlug(string $slug)
    {
        if (!$model = News::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('not found');
        }

        if (!BotDetector::isSearchBot() && $model->hasAttribute('views')) {
            $model->updateCounters(['views' => 1]);
        }

        return $this->render($this->action->id, compact('model'));
    }
}

