<?php

namespace backend\controllers;

use Yii;
use common\models\Product;
use common\models\search\SeoSearch;
use common\models\{search\ManufactureSearch, UploadSitemap, Seo, Manufacture};
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\{Controller,NotFoundHttpException,Response,UploadedFile};
use yii\filters\VerbFilter;

/**
 * SeoController implements the CRUD actions for Seo model.
 */
class SeoController extends Controller
{
    public function beforeAction($action)
    {
        if (in_array($action->id, ['save-product-description', 'generate-ai-description'], true)) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Seo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SeoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * Редактирование robots.txt
     * @return string
     */
    public function actionRobots()
    {
        $content = file_get_contents('../../public_html/robots.txt', true);

        return $this->render('robots', compact('content'));
    }

    /**
     * Обновление robots.txt
     * @return void|\yii\web\Response
     */
    public function actionUpdateRobots()
    {
        if (Yii::$app->request->post()) {
            $content = Yii::$app->request->post('content');

            file_put_contents('../../public_html/robots.txt', $content);

            return $this->redirect(['/seo']);
        }
    }

    public function actionGoogle()
    {
        $content = file_get_contents('../../public_html/url_list.txt', true);

        return $this->render('google', compact('content'));
    }

    public function actionUpdateGoogle()
    {
        if (Yii::$app->request->post()) {
            $content = Yii::$app->request->post('content');

            file_put_contents('../../public_html/url_list.txt', $content);

            return $this->redirect(['/seo']);
        }
    }

    /**
     * @return string
     */
    public function actionSitemap()
    {
        return $this->render('sitemap');
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionUploadSitemap()
    {
        $model = new UploadSitemap();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
                Yii::$app->session->setFlash('success', 'Файл загружен успешно');

                return $this->redirect(['seo/sitemap']);
            }
        }

        return $this->render('sitemap-upload', compact('model'));
    }

    /**
     * Displays a single Seo model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Seo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Seo();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model'));
    }

    /**
     * Updates an existing Seo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', "Данные успешно обновлены");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', compact('model'));
    }

    /**
     * Deletes an existing Seo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @return string
     */
    public function actionManufacture()
    {
        $searchModel = new ManufactureSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination->pageSize = 100;

        return $this->render('manufacture', compact('searchModel', 'dataProvider'));
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionManufactureUpdate(int $id)
    {
        $model = Manufacture::findOne($id);
        $seo = Seo::find()->where(['type' => Seo::TYPE_CATALOG_MANUFACTURES, 'ref_id' => $id])->one();
        $modelSeo = $seo ?: new Seo(['type' => Seo::TYPE_CATALOG_MANUFACTURES, 'ref_id' => $id]);

        if (!isset($modelSeo->type)) $modelSeo->type = Seo::TYPE_CATALOG_MANUFACTURES;

        $modelSeo->ref_id = $id;
        $req = $this->request;

        if ($req->isPost && $modelSeo->load($req->post())) {
            $isValid = $modelSeo->validate();

            if ($isValid) {
                $modelSeo->save(false);

                return $this->redirect(['manufacture']);
            }
        }

        return $this->render('manufacture-update', compact('modelSeo', 'model'));
    }

    /**
     * SEO-описания товаров
     * @return string
     */
    public function actionProductDescriptions()
    {
        $id = trim((string)$this->request->get('id', ''));
        $name = trim((string)$this->request->get('name', ''));
        $filter = $this->request->get('filter', ''); // no_title, no_h1, no_description

        $query = Product::find()->with('seo')->orderBy('id DESC');

        if ($id !== '') {
            $query->andWhere(['product.id' => (int)$id]);
        }
        if ($name !== '') {
            $query->andWhere(['like', 'product.name', $name]);
        }

        $emptySeoCondition = function ($field) {
            return ['or', ["seo.{$field}" => ''], ["seo.{$field}" => null], ['seo.id' => null]];
        };

        if ($filter === 'no_title') {
            $query->joinWith(['seo' => function($q) {
                $q->andOnCondition(['seo.type' => Seo::TYPE_PRODUCT]);
            }], false, 'LEFT JOIN')
                ->andWhere($emptySeoCondition('title'))
                ->groupBy('product.id');
        } elseif ($filter === 'no_h1') {
            $query->joinWith(['seo' => function($q) {
                $q->andOnCondition(['seo.type' => Seo::TYPE_PRODUCT]);
            }], false, 'LEFT JOIN')
                ->andWhere($emptySeoCondition('h1'))
                ->groupBy('product.id');
        } elseif ($filter === 'no_description') {
            $query->joinWith(['seo' => function($q) {
                $q->andOnCondition(['seo.type' => Seo::TYPE_PRODUCT]);
            }], false, 'LEFT JOIN')
                ->andWhere($emptySeoCondition('description'))
                ->groupBy('product.id');
        }

        // Подсчет статистики по текущему набору товаров
        $baseQuery = Product::find();
        if ($id !== '') {
            $baseQuery->andWhere(['product.id' => (int)$id]);
        }
        if ($name !== '') {
            $baseQuery->andWhere(['like', 'product.name', $name]);
        }
        
        $withoutTitle = (clone $baseQuery)
            ->joinWith(['seo' => function($q) {
                $q->andOnCondition(['seo.type' => Seo::TYPE_PRODUCT]);
            }], false, 'LEFT JOIN')
            ->andWhere($emptySeoCondition('title'))
            ->count();
        
        $withoutH1 = (clone $baseQuery)
            ->joinWith(['seo' => function($q) {
                $q->andOnCondition(['seo.type' => Seo::TYPE_PRODUCT]);
            }], false, 'LEFT JOIN')
            ->andWhere($emptySeoCondition('h1'))
            ->count();
        
        $withoutDescription = (clone $baseQuery)
            ->joinWith(['seo' => function($q) {
                $q->andOnCondition(['seo.type' => Seo::TYPE_PRODUCT]);
            }], false, 'LEFT JOIN')
            ->andWhere($emptySeoCondition('description'))
            ->count();

        $perPage = (int)$this->request->get('per-page', 50) ?: 50;
        $perPage = in_array($perPage, [20, 50, 100, 200], true) ? $perPage : 50;
        $currentPage = max(1, (int)$this->request->get('page', 1));
        $get = $this->request->getQueryParams();

        $totalCount = (int)(clone $query)->count();

        $pagination = new Pagination([
            'totalCount' => $totalCount,
            'pageParam' => 'page',
            'pageSizeParam' => 'per-page',
            'defaultPageSize' => 50,
            'pageSize' => $perPage,
            'forcePageParam' => false,
            'route' => 'seo/product-descriptions',
            'params' => $get,
            'validatePage' => false,
        ]);
        $pagination->setPage($currentPage - 1, false);

        $products = (clone $query)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('product-descriptions', compact('products', 'pagination', 'totalCount', 'id', 'name', 'filter', 'withoutTitle', 'withoutH1', 'withoutDescription'));
    }

    /**
     * AJAX сохранение SEO-описания товара
     * @return array
     */
    public function actionSaveProductDescription()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $productId = (int)$this->request->post('product_id');
        if (!$productId) {
            return ['success' => false, 'error' => 'Не указан товар'];
        }

        if (!$product = Product::findOne($productId)) {
            return ['success' => false, 'error' => 'Товар не найден'];
        }

        $seo = Seo::findOne(['type' => Seo::TYPE_PRODUCT, 'ref_id' => $productId]);
        if (!$seo) {
            $seo = new Seo(['type' => Seo::TYPE_PRODUCT, 'ref_id' => $productId]);
        }

        $seo->title = (string)$this->request->post('title');
        $seo->h1 = (string)$this->request->post('h1');
        $seo->description = (string)$this->request->post('description');
        $seo->opisanie = (string)$this->request->post('opisanie');
        $seo->opisanie_ai = (string)$this->request->post('opisanie_ai');

        if (!$seo->save()) {
            return ['success' => false, 'error' => $seo->getFirstErrors()];
        }

        return ['success' => true];
    }

    /**
     * Генерация ИИ-описания: вызов внешнего скрипта, затем возврат opisanie_ai из БД
     * @return array
     */
    public function actionGenerateAiDescription()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $productId = (int)$this->request->get('product_id', 0) ?: (int)$this->request->post('product_id', 0);
        if (!$productId) {
            return ['success' => false, 'error' => 'Не указан товар'];
        }

        if (!Product::findOne($productId)) {
            return ['success' => false, 'error' => 'Товар не найден'];
        }

        $url = 'https://gassensor.ru/_scripts/export_tovar_h1_gen-text-ai.php?product_id=' . $productId;
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 120,
                'ignore_errors' => true,
            ],
        ]);
        @file_get_contents($url, false, $ctx);

        $seo = Seo::findOne(['type' => Seo::TYPE_PRODUCT, 'ref_id' => $productId]);
        $opisanieAi = $seo ? (string)$seo->opisanie_ai : '';

        return ['success' => true, 'opisanie_ai' => $opisanieAi];
    }

    /**
     * Finds the Seo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Seo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Seo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
