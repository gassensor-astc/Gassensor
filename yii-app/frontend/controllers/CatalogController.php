<?php
/**
 *
 * @since 2021-10-19 10:20:27
 */

namespace frontend\controllers;

use common\models\Gaz;
use common\models\Manufacture;
use common\models\search\ProductSearch;
use common\models\Seo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();

        if ($this->request->isAjax) {
            return $this->renderPartial('popover-filter', [
                'searchModel' => $searchModel,
                'dataProvider' => $searchModel->searchFront($this->request->post()),
            ]);
        }

        $params = $this->request->queryParams;

        $dataProvider = $searchModel->searchFront($params);
        $dataProvider->sort = false;

        $seo = null;
        if ($searchModel->gaz_id && ($gaz = Gaz::findOne((int) $searchModel->gaz_id))) {
            $gasTitle = trim(rtrim($gaz->title, '.'));
            $count = (int) $dataProvider->totalCount;
            $seo = new Seo();
            $seo->setAttribute('title', 'Сенсоры и датчики газа ' . $gasTitle);
            $seo->setAttribute('h1', 'Сенсоры и датчики газа ' . $gasTitle);
            $seo->setAttribute('description', 'Каталог сенсоров и датчиков газа ' . $gasTitle . ' по доступной цене. ' . $count . ' сенсоров и датчиков на базе газа ' . $gasTitle . ' купить в Москве. ');
        } else {
            $seo = $searchModel->manufacture->seo ?? null;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'seo' => $seo,
        ]);
    }

    /**
     * @param string $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGas(string $slug)
    {
        $searchModel = new ProductSearch();

        $params = $this->request->queryParams;

        if (!$gaz = Gaz::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException();
        }

        $params['ProductSearch']['gaz_id'] = $gaz->id;

        $_GET['filter'] = false;

        $dataProvider = $searchModel->searchFront($params);

        $dataProvider->query->joinWith('manufacture')->orderBy('manufacture.weight, id');
        $dataProvider->sort = false;

        // SEO: как в actionIndex при выборе газа — единый формат H1/title/description
        $gazSeo = $gaz->seo;
        $gasTitle = trim(rtrim($gaz->title, '.'));
        $count = (int) $dataProvider->totalCount;
        if ($gazSeo && (string)$gazSeo->h1 !== '' && (string)$gazSeo->title !== '') {
            $seo = $gazSeo;
        } else {
            $seo = new Seo();
            $seo->setAttribute('title', 'Сенсоры и датчики газа ' . $gasTitle);
            $seo->setAttribute('h1', 'Сенсоры и датчики газа ' . $gasTitle);
            $seo->setAttribute('description', 'Каталог сенсоров и датчиков газа ' . $gasTitle . ' по доступной цене. ' . $count . ' сенсоров и датчиков на базе газа ' . $gasTitle . ' купить в Москве.');
        }
        // Canonical задаём отдельно — всегда чистый URL без query (не из БД)
        $canonicalUrl = 'https://gassensor.ru/catalog/' . $gaz->slug;
        $seo->setAttribute('url_canonical', null);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'seo' => $seo,
            'canonicalUrl' => $canonicalUrl,
        ]);
    }

    /**
     * @param string $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionManufacture(string $slug)
    {
        $searchModel = new ProductSearch();

        $params = $this->request->queryParams;

        if (!$manufacture = Manufacture::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException();
        }

        $seo = Seo::find()->where(['type' => Seo::TYPE_CATALOG_MANUFACTURES, 'ref_id' => $manufacture->id])->one();

        $params['ProductSearch']['manufacture_id'] = $manufacture->id;
        $dataProvider = $searchModel->searchFront($params);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'seo' => $seo,
            'manufacture' => $manufacture,
        ]);
    }
}

