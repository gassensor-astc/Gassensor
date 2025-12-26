<?php

namespace frontend\controllers;

use common\models\Applications;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ApplicationsController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $query = Applications::find();
        $applications = $query->where('type=1')->all();
        $detectorTubes = $query->where('type=2')->all();
        $all = Applications::find()->orderBy('created_at DESC')->all();

        return $this->render($this->action->id, compact('applications', 'detectorTubes', 'all'));
    }

    /**
     * @param string $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSlug(string $slug)
    {
        if (!$model = Applications::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        return $this->render($this->action->id, compact('model'));
    }
}