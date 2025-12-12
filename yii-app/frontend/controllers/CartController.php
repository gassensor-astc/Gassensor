<?php
/**
 *
 * @since 2021-11-05 17:20:12
 */

namespace frontend\controllers;

use common\components\cart\AddToCartForm;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use common\helpers\FlashTrait;

class CartController extends Controller
{
    use FlashTrait;

    public function actionIndex()
    {
        return $this->render($this->action->id, []);
    }

    public function actionAdd()
    {
        $model = new AddToCartForm();

        $req = $this->request;

        if (!$req->isPost) {
            $this->addFlashError('invalid request');
            return $this->redirect('/catalog', 301);
        }

        $model->load($req->post());

        if (!$model->validate()) {
            throw new BadRequestHttpException('invalid request');
        }

        $model->addToCart();

        if ($backUrl = $req->post('backUrl')) {
            $this->addFlashSuccess("Добавлен товар в корзину '{$model->product->name}' ({$model->count} шт)");
            return $this->redirect($backUrl);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        /* @var \common\components\cart\Cart $cart */
        $cart = Yii::$app->cart;
        $item = $cart->getItem($model->productId);



        return [
            'count' => $cart->getItemsCount(),
            'id' => $item->product->id,
        ];
    }

    /**
     * @param null $id
     * @return Response
     */
    public function actionDel($id = null)
    {
        /* @var \common\components\cart\Cart $cart */
        $cart = Yii::$app->cart;

        $id = (int)$id;

        if (!$product = $cart->getItemProduct($id)) {
            $this->addFlashError('invalid request');
        } else {
            $cart->removeItem($id);
            $this->addFlashSuccess("Удален товар {$product->name}");
        }

        return $this->redirect(['index']);
    }

    public function actionClear()
    {
        /* @var \common\components\cart\Cart $cart */
        $cart = Yii::$app->cart;

        $cart->clear();

        $this->addFlashSuccess('Корзина очищена');
        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @param int $count
     * @return int
     */
    public function actionSetCount(int $id, int $count)
    {
        /* @var \common\components\cart\Cart $cart */
        $cart = Yii::$app->cart;
        $cart->changeCount($id, $count);

        return $cart->getItem($id)->count;
    }

    public function actionGetTotalCount()
    {
        return Yii::$app->cart->getItemsCount();
    }

}

