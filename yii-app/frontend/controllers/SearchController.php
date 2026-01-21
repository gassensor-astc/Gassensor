<?php
/**
 *
 * @since 2021-11-23 13:11:40
 */

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Seo;
use yii\web\BadRequestHttpException;

class SearchController extends Controller
{
    /**
     * @param null $q
     * @return string
     */
    public function actionIndex($q = null)
    {
        $q = trim($q);
        $minLength = 3;
        
        if (mb_strlen($q) < $minLength) {
            return $this->render('index-cards', [
                'q' => $q,
                'error' => "Введите минимум {$minLength} символа для поиска",
            ]);
        }
        
        return $this->render('index-cards', ['q' => $q, 'error' => null]);
    }

    /**
     * @param null $id
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionSeo(int $id = null)
    {
        if (!$model = Seo::findOne($id)) {
            throw new BadRequestHttpException('bad request');
        }

        if ($url = $model->getRefUrl()) {
            return $this->redirect($url);
        }

        return 'ok';
    }
}
