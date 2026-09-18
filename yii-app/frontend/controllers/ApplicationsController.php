<?php

namespace frontend\controllers;

use common\helpers\BotDetector;
use common\helpers\Tools;
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
        // SEO: у «Статей» пагинации нет вовсе — и /applications/page/<N>, и
        // ?page=<N> отдают тот же полный список. Оба адреса 301-им на /applications.
        if ($target = Tools::getPageParamStrippedRedirectTarget('/applications')) {
            return $this->redirect($target, 301);
        }

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

        if (!BotDetector::isSearchBot() && $model->hasAttribute('views')) {
            $model->updateCounters(['views' => 1]);
        }

        return $this->render($this->action->id, compact('model'));
    }
}