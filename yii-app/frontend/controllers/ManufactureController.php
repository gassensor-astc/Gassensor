<?php
/**
 *
 * @since 2021-10-16 14:56:34
 */

namespace frontend\controllers;

use common\models\Manufacture;
use common\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ManufactureController extends Controller
{
    /**
     * Сколько последних добавленных товаров бренда показывать в блоке «Примеры товаров»
     */
    private const SAMPLE_PRODUCTS_LIMIT = 5;

    public function actionIndex()
    {
        return $this->render($this->action->id, [
        ]);
    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSlug(string $slug)
    {
        if (!$model = Manufacture::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('not found');
        }

        // Последние добавленные товары бренда — для блока «Примеры товаров» внизу страницы
        $products = Product::find()
            ->where(['manufacture_id' => $model->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(self::SAMPLE_PRODUCTS_LIMIT)
            ->all();

        return $this->render($this->action->id, compact('model', 'products'));
    }
}

