<?php

namespace frontend\controllers;

use common\models\SensorsList;
use yii\data\Pagination;
use yii\web\Controller;

class RemainsController extends Controller
{
    public function actionIndex()
    {
        $query = SensorsList::find();
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 50,
            'pageSizeParam' => false,
            // Не добавляем ?page=1 в URL – первая страница всегда без параметра
            'forcePageParam' => false,
            // Явно фиксируем маршрут, чтобы пагинация вела на /remains, а не /remains/index
            'route' => '/remains',
        ]);
        $sensors = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('name')
            ->all();

        return $this->render($this->action->id, compact('sensors', 'pages'));
    }
}