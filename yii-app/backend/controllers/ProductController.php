<?php

namespace backend\controllers;

use Yii;
use common\helpers\FlashTrait;
use common\models\{ProductRange, SensorsList, Seo, Product, ProductGaz, Setting};
use backend\models\ProductSearch;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\{Controller, NotFoundHttpException, UploadedFile};
use common\helpers\StringHelpers;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use yii\base\DynamicModel;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * Displays a single Product model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new Product();
        $modelSeo = new Seo(['type' => Seo::TYPE_PRODUCT,]);
        $modelProductGaz = new ProductGaz();
        $modelsRange = [new ProductRange];

        $req = $this->request;

        if ($req->isPost) {
            if ($model->load($req->post()) && $modelSeo->load($req->post()) && $modelProductGaz->load($req->post())) {
                $isValid = $model->validate();
                $isValid = $modelSeo->validate() && $isValid;

                $modelsRange = Product::createMultiple(ProductRange::class);

                Product::loadMultiple($modelsRange, $req->post());

                $isValid = Product::validateMultiple($modelsRange, ['from', 'to', 'unit']) && $isValid;

                if ($isValid) {
                    $model->slug = StringHelpers::slug($model->slug);
                    $model->save(false);
                    $modelSeo->ref_id = $model->id;
                    $modelSeo->save(false);

                    if ($model->uploadPict = UploadedFile::getInstance($model, 'uploadPict')) {
                        if (!$model->uploadPict()) {
                            $this->addFlashError('Ошибка загрузки картинки');
                        }
                    }

                    foreach (Product::getPdfIndexes() as $v) {
                        $attr = 'uploadPdf' . $v;
                        if ($model->$attr = UploadedFile::getInstance($model, $attr)) {
                            if (!$model->uploadPdf($v)) {
                                $this->addFlashError("Ошибка загрузки pdf $v");
                            }
                        }
                    }

                    $ids = [];
                    $ids[] = $req->post('ProductGaz')['is_main'];

                    $ids = array_unique($ids);

                    $model->saveGazs($ids); //select2 array $modelGaz->gaz_id
                    $model->saveMainbGaz($req->post('ProductGaz')['is_main']);

                    if (isset($req->post('ProductGaz')['is_main_2']) && !empty($req->post('ProductGaz')['is_main_2'])) $model->saveMainbGaz2($req->post('ProductGaz')['is_main_2']);
                    if (isset($req->post('ProductGaz')['is_main_3']) && !empty($req->post('ProductGaz')['is_main_3'])) $model->saveMainbGaz3($req->post('ProductGaz')['is_main_3']);

                    foreach ($modelsRange as $modelRange) {
                        $modelRange->product_id = $model->id;
                        $modelRange->save(false);
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', compact('model', 'modelSeo', 'modelProductGaz', 'modelsRange'));
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $modelSeo = $model->seo ?: new Seo(['type' => Seo::TYPE_PRODUCT, 'ref_id' => $model->id]);
        $modelProductGaz = new ProductGaz(['product_id' => $model->id]);

        $modelsRange = $model->productRanges ?: [new ProductRange];

        $req = $this->request;

        if ($req->isPost && $model->load($req->post()) && $modelSeo->load($req->post()) && $modelProductGaz->load($req->post())) {
            $isValid = $model->validate();
            $isValid = $modelSeo->validate() && $isValid;

            $modelsRange = Product::createMultiple(ProductRange::class, $modelsRange);
            Product::loadMultiple($modelsRange, $req->post());
            $isValid = Product::validateMultiple($modelsRange, ['from', 'to', 'unit']) && $isValid;

            if ($isValid) {
                $model->slug = StringHelpers::slug($model->slug);
                $model->save(false);
                $modelSeo->save(false);

                if ($model->uploadPict = UploadedFile::getInstance($model, 'uploadPict')) {
                    if (!$model->uploadPict()) {
                        $this->addFlashError('Ошибка загрузки картинки');
                    }
                }

                foreach (Product::getPdfIndexes() as $v) {
                    $attr = 'uploadPdf' . $v;
                    if ($model->$attr = UploadedFile::getInstance($model, $attr)) {
                        if (!$model->uploadPdf($v)) {
                            $this->addFlashError("Ошибка загрузки pdf $v (error code: {$model->$attr->error}) <a href='https://www.php.net/manual/en/features.file-upload.errors.php' target='_blank'>info</a>");
                        }
                    }
                }

                if (isset($req->post('ProductGaz')['is_main'])) {
                    $ids = [];
                    $ids[] = $req->post('ProductGaz')['is_main'];

                    if (isset($req->post('ProductGaz')['is_main']) && is_array($req->post('ProductGaz')['gaz_id'])) $ids = array_merge($ids, $req->post('ProductGaz')['gaz_id']);
                    if (isset($req->post('ProductGaz')['is_main_2']) && !empty($req->post('ProductGaz')['is_main_2'])) $ids[] = $req->post('ProductGaz')['is_main_2'];
                    if (isset($req->post('ProductGaz')['is_main_3']) && !empty($req->post('ProductGaz')['is_main_3'])) $ids[] = $req->post('ProductGaz')['is_main_3'];

                    $ids = array_unique($ids);

                    $model->saveGazs($ids); //select2 array $modelProductGaz->gaz_id

                    if (isset($req->post('ProductGaz')['is_main'])) {
                        $model->saveMainbGaz($req->post('ProductGaz')['is_main']);
                    }

                    if (isset($req->post('ProductGaz')['is_main_2']) && !empty($req->post('ProductGaz')['is_main_2'])) $model->saveMainbGaz2($req->post('ProductGaz')['is_main_2']);
                    if (isset($req->post('ProductGaz')['is_main_3']) && !empty($req->post('ProductGaz')['is_main_3'])) $model->saveMainbGaz3($req->post('ProductGaz')['is_main_3']);

                    $deletedIDs = [];

                    for ($i=0; $i<count($_POST['ProductRange']); $i++) {
                        if (!isset($_POST['ProductRange'][$i]['pos']) || !isset($_POST['ProductRange'][$i]['unit']) || !isset($_POST['ProductRange'][$i]['to'])) {
                            $deletedIDs[] = $_POST['ProductRange'][$i]['id'];
                        }
                    }

                    if ($deletedIDs) {
                        ProductRange::deleteAll(['id' => $deletedIDs]);
                    }

                    foreach ($modelsRange as $modelRange) {
                        $modelRange->product_id = $model->id;
                        $modelRange->save(false);
                    }
                }

                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        $modelProductGaz->gaz_id = ArrayHelper::getColumn($model->notMainGazes, 'id');
        $modelProductGaz->is_main = $model->mainGaz->id ?? null;
        $modelProductGaz->is_main_2 = $model->mainGaz2->id ?? null;
        $modelProductGaz->is_main_3 = $model->mainGaz3->id ?? null;

        return $this->render('update', compact('model', 'modelSeo', 'modelProductGaz', 'modelsRange'));
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);

        if ($seo = $model->seo) {
            $seo->delete();//cascade
        }

        $model->delete();

        return $this->redirect(['index', 'sort' => '-id',]);
    }

    public function actionCheckboxDelete()
    {
        $selection = Yii::$app->request->post('selection');

        if ($selection != null) {
            $news = Product::find()->where(['in', 'id', $selection]);

            foreach ($news ?? [] as $new) {
                $seo = $new->seo ?? null;
                if ($seo) {
                    $seo->delete();
                }
            }

            Product::deleteAll([
                'id' => $selection
            ]);

            Yii::$app->session->setFlash('success', 'Выбранные данные удалены!');
            return $this->redirect(['index', 'sort' => '-id',]);
        } else {
            Yii::$app->session->setFlash('error', 'Нечего удалять!');

            return $this->redirect(['index', 'sort' => '-id',]);
        }
    }

    /**
     * @param $filename
     * @return bool|void
     */
    public function delFile($filename)
    {
        if (is_file($filename)) {
            Yii::$app->session->addFlash('success', "Удаление файла '$filename");
            if (unlink($filename)) {
                return true;
            } else {
                Yii::$app->session->addFlash('error', 'Ошибка удаления');
            }
        } else {
            Yii::$app->session->addFlash('error', "Файл '$filename' не найден");
            return true;
        }
    }

    /**
     * @param null $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeletePict($id = null)
    {
        $model = $this->findModel($id);
        $filename = $model->getPictPath();

        if ($this->delFile($filename)) {
            $model->img = null;
            $model->save(false);
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * @param int $id
     * @param $i
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeletePdf(int $id, $i)
    {
        $i = $i ? (int)$i : '';
        $model = $this->findModel($id);
        $filename = $model->getPdfPath($i);

        if ($this->delFile($filename)) {
            $attr = 'pdf' . $i;
            $model->$attr = null;
            $model->save(false);
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @param $sort
     * @return \yii\web\Response
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function actionExportExcel($sort = null)
    {
        return Product::exportExcel();
    }

    /**
     * Импорт сенсоров
     *
     * @return string
     * @throws \PHPExcel_Reader_Exception
     */
    public function actionUploadSensors()
    {
        $modelImport = new DynamicModel([
            'file' => 'Импорт сенсоров',
        ]);
        $modelImport->addRule(['file'], 'required');
        $modelImport->addRule(['file'], 'file', ['extensions' => 'ods,xls,xlsx,csv'], ['maxSize' => 1024 * 1024]);

        if (Yii::$app->request->post()) {
            $modelImport->file = UploadedFile::getInstance($modelImport, 'file');

            if ($modelImport->file && $modelImport->validate()) {
                $count = self::importFromExcel($modelImport->file);

                $fileName = 'sensors_list.' . $modelImport->file->extension;

                $res = $modelImport->file->saveAs('../upload/' . $fileName);

                if ($res) {
                    Setting::saveValue('SENSORS_LIST', $fileName);
                }

                Yii::$app->getSession()->setFlash('success', 'Импорт успешно завершен. Импортировано: ' . $count);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Ошибка импорта');
            }
        }

        return $this->render('upload', ['model' => $modelImport]);
    }

    /**
     * @param $importFile
     * @return int
     * @throws \yii\db\Exception
     */
    private static function importFromExcel($importFile): int
    {
        $iterator = static function ($data): \Generator {
            yield from new \ArrayIterator($data);
        };

        $processed_data = static function (Worksheet $data) use ($iterator): ?array {
            $key_lists = [];
            $init_data = [];
            foreach ($iterator($data->toArray()) as $item) {
                if (empty($key_lists)) {
                    if (is_null($item[0])) break;

                    $item = array_map(static fn($_item) => trim((string)$_item), $item);
                    $key_lists = $item;
                } else {
                    if (empty($key_lists)) break;

                    $item = array_map(static fn($_item) => trim((string)$_item), $item);
                    $init_data[] = array_combine($key_lists, $item);
                }
            }

            return (empty($init_data))
                ? null
                : $init_data;
        };

        $extension = strtolower($importFile->getExtension());

        $open_file = static function (string $file) use ($extension): ?Spreadsheet {

            switch ($extension) {
                case 'xlsx':
                    $inputFileType = 'Xlsx';
                    break;
                case 'xls':
                    $inputFileType = 'Xls';
                    break;
                case 'csv':
                    $inputFileType = 'Csv';
                    break;
                case 'ods':
                    $inputFileType = 'Ods';
                    break;
            }

            $reader = IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(false);

            return $reader->load($file);
        };

        $processed = static function (Spreadsheet $spreadsheet) use ($iterator, $processed_data): int {
            $count = 0;
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $sheetCount = count($sheetData);

            SensorsList::deleteAll();

            $i = 24;
            while ($i < $sheetCount) {
                if ($sheetData[$i]['A'] && $sheetData[$i]['D']) {
                    $name = trim($sheetData[$i]['A']);
                    $posNumber = $sheetData[$i]['D'];
                    $posNumber = str_replace(' ', '', $posNumber);
                    $posNumber = (int)$posNumber;

                    $gaz = trim($sheetData[$i]['C']);
                    $gaz = str_replace('#NULL!', '', $gaz);

                    $model = new SensorsList();

                    $model->name = $name;
                    $model->name2 = $gaz;
                    $model->count = $posNumber;

                    $link = $sheetData[$i]['E'];
                    $link = str_replace('#NULL!', '', $link);
                    $link = trim($link);

                    if (!empty($link)) {
                        $model->link = $link;
                    }

                    if ($model->save()) {
                        $count++;
                    }
                }

                $i++;
            }

            return $count;
        };

        $worksheetData = $open_file($importFile->tempName);

        return $processed($worksheetData);
    }
}
