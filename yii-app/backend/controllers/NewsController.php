<?php

namespace backend\controllers;

use Yii;
use common\helpers\FlashTrait;
use common\models\{News, Seo};
use common\models\search\NewsSearch;
use common\helpers\StringHelpers;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use yii\web\{Controller, NotFoundHttpException, UploadedFile};


/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    use FlashTrait;
    public $enableCsrfValidation = false;
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
                        'checkbox-delete' => ['POST'],
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
        $searchModel = new NewsSearch();
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
        $model = new News(['date' => date('Y-m-d'),]);
        $modelSeo = new Seo(['type' => Seo::TYPE_NEWS,]);

        $req = $this->request;

        if ($req->isPost) {
            if ($model->load($req->post()) && $modelSeo->load($req->post())) {
                $isValid = $model->validate();

                if ($isValid) {
                    $model->slug = StringHelpers::slug($model->slug);
                    $model->save();

                    $modelSeo->ref_id = $model->id;
                    $postSeo = $req->post('Seo');
                    if (is_array($postSeo) && array_key_exists('breadcrumb_text', $postSeo)) {
                        $modelSeo->breadcrumb_text = trim((string) ($postSeo['breadcrumb_text'] ?? ''));
                    }
                    $modelSeo->save(false);

                    if ($model->uploadFile = UploadedFile::getInstance($model, 'uploadFile')) {
                        $model->upload();
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
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

        $modelSeo = $model->seo ?: new Seo(['type' => Seo::TYPE_NEWS, 'ref_id' => $model->id]);

        $req = $this->request;

        if ($req->isPost && $model->load($req->post()) && $modelSeo->load($req->post())) {
            $isValid = $model->validate();

            if ($isValid) {
                $model->slug = StringHelpers::slug($model->slug);
                $model->save();

                $modelSeo->ref_id = $model->id;
                $postSeo = $req->post('Seo');
                if (is_array($postSeo) && array_key_exists('breadcrumb_text', $postSeo)) {
                    $modelSeo->breadcrumb_text = trim((string) ($postSeo['breadcrumb_text'] ?? ''));
                }
                $modelSeo->save(false);

                if ($model->uploadFile = UploadedFile::getInstance($model, 'uploadFile')) {
                    Yii::$app->getSession()->setFlash('success', "Данные успешно обновлены");
                    $model->upload();
                }

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
            $seo->delete();//cascade
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param int|null $id
     * @param $basename
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteFile(int $id = null, $basename = null)
    {
        $model = $this->findModel($id);

        $model->delFile($basename);

        $this->addFlashSuccess("Установлен файл $basename");

        return $this->redirect(['update', 'id' => $id]);

    }

    /**
     * @param int|null $id
     * @param $basename
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionFixFilename(int $id = null, $basename = null)
    {
        $model = $this->findModel($id);

        $filename = "{$model->uploadDir}/$basename";

        if (!is_file($filename)) {
            throw new \Exception('file not found');
        }

        $info = pathinfo($filename);
        $ext = $info['extension'];
        $raw = $info['filename'];

        $fixed = Inflector::slug($raw);

        $filenameNew = "{$model->uploadDir}/$fixed.$ext";

        if (rename($filename, $filenameNew)) {
            Yii::$app->session->addFlash('success', "renamed to '$filenameNew'");
        } else {
            Yii::$app->session->addFlash('error', 'fail renaming');
        }

        return $this->redirect(['update', 'id' => $id]);
    }

    public function actionCheckboxDelete()
    {
        $selection = Yii::$app->request->post('selection');

        if ($selection != null) {
            $news = News::find()->where(['in', 'id', $selection]);

            foreach ($news ?? [] as $new) {
                $seo = $new->seo ?? null;
                if ($seo) {
                    $seo->delete();
                }
            }

            News::deleteAll([
                'id' => $selection
            ]);

            Yii::$app->session->setFlash('success', 'Выбранные данные удалены!');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Нечего удалять!');

            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
