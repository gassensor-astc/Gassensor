<?php

namespace frontend\controllers;

use common\models\Gaz;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class GasesController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataProviderGases = new ActiveDataProvider([
            'query' => Gaz::find()->notFreons()->orderBy('title'),
            'pagination' => false,
        ]);

        $dataProviderFreons = new ActiveDataProvider([
            'query' => Gaz::find()->freons()->orderBy('title'),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProviderGases' => $dataProviderGases,
            'dataProviderFreons' => $dataProviderFreons,
        ]);
    }
}
