<?php

namespace frontend\controllers;

use common\helpers\BotDetector;
use common\helpers\Tools;
use common\models\News;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ListView;

class NewsController extends Controller
{
    public function actionIndex()
    {
        // SEO: /news/page/<N> — дубль формы /news?page=<N> (каноническая —
        // именно она: её отдаёт canonical-тег и ссылки постраничной навигации).
        // Путевую форму и page<=1 301-им на каноническую.
        if ($target = Tools::getQueryFormPageRedirectTarget('/news')) {
            return $this->redirect($target, 301);
        }

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

