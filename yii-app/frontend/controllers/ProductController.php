<?php
/**
 *
 * @since 2021-10-20 14:39:28
 */

namespace frontend\controllers;

use common\components\cart\AddToCartForm;
use common\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Gaz;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;

class ProductController extends Controller
{
    /**
     * @param $slug
     * @param null $slugGaz
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionSlug(string $slug, $slugGaz = null)
    {
        if (!$model = Product::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('not found');
        }

        $gazSlugs = ArrayHelper::getColumn($model->gazs, 'slug');

        // Конечный URL товара (страница основного газа) — из модели, чтобы ссылки
        // в каталоге и редиректы здесь не разъезжались.
        $finalUrl = $model->url;

        if ($slugGaz) {
            if (!Gaz::findOne(['slug' => $slugGaz])) {
                throw new BadRequestHttpException('invalid gaz');
            }

            if (!in_array($slugGaz, $gazSlugs)) {
                throw new NotFoundHttpException('not match gaz');
            }

            if ($finalUrl !== "/catalog/{$slugGaz}/$slug") {
                return $this->redirect($finalUrl, 301);
            }

        } elseif ($gazSlugs) {
            return $this->redirect($finalUrl, 301);
        }

        $formAdd = new AddToCartForm(['count' => 1, 'productId' => $model->id,]);

        return $this->render($this->action->id, compact('model', 'formAdd'));
    }
}
