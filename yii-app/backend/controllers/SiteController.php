<?php

namespace backend\controllers;

use common\models\Order;
use common\models\OrderProduct;
use Yii;
use common\helpers\{Uploader,FlashTrait};
use yii\filters\VerbFilter;
use yii\web\{Controller,Response};

/**
 * Site controller
 */
class SiteController extends Controller
{
    use FlashTrait;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        //$this->addFlashSuccess('test1');
        //$this->addFlashError('test2');

        $chartData = [
            's1' => [],
            'ticks' => [],
        ];
        $chartDataLine = [];

        $allOrders = Order::find()->orderBy('created_at ASC')->asArray()->all();
        $dates = [];
        $dates2 = [];

        foreach ($allOrders as $order) {
            $data = date('d.m.Y', $order['created_at']);
            $data2 = date('Y-m-d', $order['created_at']);

            if (!isset($dates[$data])) {
                $dates[$data] = 0;
            }
            if (!isset($dates2[$data2])) {
                $dates2[$data2] = 0;
            }

            $orderProduct = OrderProduct::find()->where(['=', 'order_id', $order['id']])->asArray()->one();

            if ($orderProduct) {
                $dates[$data] += $orderProduct['count'];
                $dates2[$data2] += $orderProduct['count'];
            }
        }

        foreach ($dates as $date => $counts) {
            $chartData['s1'][] = $counts;
            $chartData['ticks'][] = $date;
        }

        foreach ($dates2 as $date => $counts) {
            $chartDataLine[] = [$date, $counts];
        }


        return $this->render('index', compact('chartData', 'chartDataLine'));
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        @Yii::$app->user->logout();

        return $this->redirect('/site/login');
    }

    public function actionUpload()
    {

        $uploader = new Uploader([
            'name' => key($_FILES),
        ]);

        $filename = $uploader->upload();

        $url = $uploader->baseUrl . '/' . basename($filename);

        return $url;
    }

}
