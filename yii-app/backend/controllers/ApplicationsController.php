<?php

namespace backend\controllers;

use common\helpers\StringHelpers;
use common\models\Applications;
use common\models\search\ApplicationsSearch;
use common\models\Seo;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\UploadedFile;

class ApplicationsController extends Controller
{
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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
       $searchModel = new ApplicationsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * Displays a single News model.
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
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new Applications();
        $modelSeo = new Seo(['type' => Seo::TYPE_APPLICATIONS,]);

        $req = $this->request;

        if ($req->isPost) {
            if ($model->load($req->post()) && $modelSeo->load($req->post())) {
                $isValid = $model->validate();

                if ($isValid) {
                    $model->slug = StringHelpers::slug($model->slug);
                    $model->created_at = strtotime($req->post()['Applications']['created_at']);

                    if ($preview = UploadedFile::getInstance($model, 'preview')) {
                        [$path, $url] = $model->getPictPath($preview->name);
                        copy($preview->tempName, $path);
                        $model->preview = $url;
                    }

                    $model->save();

                    $modelSeo->ref_id = $model->id;
                    $modelSeo->save(false);

                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model', 'modelSeo'));
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $model->created_at = date('d.m.Y', $model->created_at);
        $url = $model->preview;
        $modelSeo = $model->seo ?: new Seo(['type' => Seo::TYPE_APPLICATIONS, 'ref_id' => $model->id]);
        $req = $this->request;

        if ($req->isPost && $model->load($req->post()) && $modelSeo->load($req->post())) {
            $isValid = $model->validate();

            if ($isValid) {
                $model->slug = StringHelpers::slug($model->slug);
                $model->created_at = strtotime($req->post()['Applications']['created_at']);

                if ($preview = UploadedFile::getInstance($model, 'preview')) {
                    [$path, $url] = $model->getPictPath($preview->name);
                    copy($preview->tempName, $path);
                }

                $model->preview = $url;

                $model->save();
                $modelSeo->save(false);

                Yii::$app->getSession()->setFlash('success', "Данные успешно обновлены");

                return $this->redirect(['update', 'id' => $id]);
            }
        }

        return $this->render('update', compact('model', 'modelSeo'));
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);

        if ($seo = $model->seo) {
            $seo->delete();
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Applications the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Applications::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}