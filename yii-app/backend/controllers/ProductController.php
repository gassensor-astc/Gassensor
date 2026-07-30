<?php

namespace backend\controllers;

use Yii;
use common\helpers\FlashTrait;
use common\models\{Gaz, Manufacture, MeasurementType, ProductRange, SensorsList, Seo, Product, ProductGaz, Setting};
use backend\models\ProductSearch;
use yii\filters\VerbFilter;
use yii\helpers\{ArrayHelper, FileHelper, Html};
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

                    $deviceType = trim((string)($model->device_type ?: 'сенсор'));

                    $gasTitle = '';
                    $mainGazId = (int)($req->post('ProductGaz')['is_main'] ?? 0);
                    if ($mainGazId > 0 && ($gaz = \common\models\Gaz::findOne($mainGazId))) {
                        $gasTitle = trim((string)$gaz->title);
                    }

                    $name = trim((string)$model->name);
                    $manufacturerTitle = '';
                    if ((int)$model->manufacture_id > 0 && ($manufacture = \common\models\Manufacture::findOne((int)$model->manufacture_id))) {
                        $manufacturerTitle = trim((string)$manufacture->title);
                    }

                    $seoTemplates = $this->getSeoTemplates();
                    $replaces = [
                        '{product_name}' => $name,
                        '{gasname}' => $gasTitle,
                        '{manufacturer}' => $manufacturerTitle,
                        '{type_ru}' => $deviceType,
                    ];

                    $modelSeo->title = strtr($seoTemplates['title'] ?? '{product_name} {type_ru} газа {gasname} от производителя {manufacturer}', $replaces);
                    $modelSeo->h1 = strtr($seoTemplates['h1'] ?? '{product_name} {manufacturer} {type_ru} {gasname}', $replaces);
                    $modelSeo->breadcrumb_text = strtr($seoTemplates['крошка'] ?? '{product_name} {manufacturer} {type_ru} {gasname}', $replaces);
                    $modelSeo->description = strtr($seoTemplates['desc'] ?? '{product_name} {type_ru} газа {gasname} от производителя {manufacturer} можно купить в компании Газсенсор в розницу и оптом в Москве.', $replaces);
                    $modelSeo->opisanie_ai = $modelSeo->opisanie_ai ?? '';

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
                    if (isset($req->post('ProductGaz')['is_main_4']) && !empty($req->post('ProductGaz')['is_main_4'])) $model->saveMainbGaz4($req->post('ProductGaz')['is_main_4']);
                    if (isset($req->post('ProductGaz')['is_main_5']) && !empty($req->post('ProductGaz')['is_main_5'])) $model->saveMainbGaz5($req->post('ProductGaz')['is_main_5']);

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

        if ($req->isPost && $this->isPostSizeExceeded()) {
            $this->addFlashError('Слишком большой размер запроса. Увеличьте post_max_size / upload_max_filesize на сервере.');
            return $this->render('update', compact('model', 'modelSeo', 'modelProductGaz', 'modelsRange'));
        }

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
                    if (isset($req->post('ProductGaz')['is_main_4']) && !empty($req->post('ProductGaz')['is_main_4'])) $ids[] = $req->post('ProductGaz')['is_main_4'];
                    if (isset($req->post('ProductGaz')['is_main_5']) && !empty($req->post('ProductGaz')['is_main_5'])) $ids[] = $req->post('ProductGaz')['is_main_5'];

                    $ids = array_unique($ids);

                    $model->saveGazs($ids); //select2 array $modelProductGaz->gaz_id

                    if (isset($req->post('ProductGaz')['is_main'])) {
                        $model->saveMainbGaz($req->post('ProductGaz')['is_main']);
                    }

                    if (isset($req->post('ProductGaz')['is_main_2']) && !empty($req->post('ProductGaz')['is_main_2'])) $model->saveMainbGaz2($req->post('ProductGaz')['is_main_2']);
                    if (isset($req->post('ProductGaz')['is_main_3']) && !empty($req->post('ProductGaz')['is_main_3'])) $model->saveMainbGaz3($req->post('ProductGaz')['is_main_3']);
                    if (isset($req->post('ProductGaz')['is_main_4']) && !empty($req->post('ProductGaz')['is_main_4'])) $model->saveMainbGaz4($req->post('ProductGaz')['is_main_4']);
                    if (isset($req->post('ProductGaz')['is_main_5']) && !empty($req->post('ProductGaz')['is_main_5'])) $model->saveMainbGaz5($req->post('ProductGaz')['is_main_5']);

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
        $modelProductGaz->is_main_4 = $model->mainGaz4->id ?? null;
        $modelProductGaz->is_main_5 = $model->mainGaz5->id ?? null;

        return $this->render('update', compact('model', 'modelSeo', 'modelProductGaz', 'modelsRange'));
    }

    private function isPostSizeExceeded(): bool
    {
        if (!$this->request->isPost) {
            return false;
        }

        $contentLength = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
        if ($contentLength <= 0) {
            return false;
        }

        $max = $this->parseSizeToBytes(ini_get('post_max_size'));
        return $max > 0 && $contentLength > $max;
    }

    private function parseSizeToBytes($size): int
    {
        if ($size === null) {
            return 0;
        }

        $size = trim((string)$size);
        if ($size === '') {
            return 0;
        }

        $unit = strtolower(substr($size, -1));
        $value = (int)$size;
        switch ($unit) {
            case 'g':
                return $value * 1024 * 1024 * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'k':
                return $value * 1024;
            default:
                return (int)$size;
        }
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

    /**
     * Импорт товаров из Excel по шаблону "3. Gassensor - Импорт 330 новых товаров.xlsx"
     */
    public function actionImportFromExcel()
    {
        $model = new DynamicModel(['file' => 'Файл импорта товаров']);
        $model->addRule(['file'], 'required');
        $model->addRule(['file'], 'file', ['extensions' => 'ods,xls,xlsx,csv'], ['maxSize' => 1024 * 1024 * 10]);

        $log = [];
        $imported = 0;
        $skipped = 0;

        if (Yii::$app->request->post()) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
                $extension = strtolower($model->file->getExtension());

                $inputFileType = match ($extension) {
                    'xlsx' => 'Xlsx',
                    'xls' => 'Xls',
                    'csv' => 'Csv',
                    'ods' => 'Ods',
                    default => null,
                };

                if (!$inputFileType) {
                    Yii::$app->getSession()->setFlash('error', 'Неподдерживаемый формат файла');
                    return $this->render('import-from-excel', ['model' => $model]);
                }

                try {
                    $reader = IOFactory::createReader($inputFileType);
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load($model->file->tempName);
                } catch (\Exception $e) {
                    Yii::$app->getSession()->setFlash('error', 'Ошибка чтения файла: ' . $e->getMessage());
                    return $this->render('import-from-excel', ['model' => $model]);
                }
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray(null, true, true, true);

                $gazByTitle = [];
                foreach (Gaz::find()->all() as $gaz) {
                    $gazByTitle[mb_strtolower(trim($gaz->title))] = $gaz;
                }

                $seoTemplates = $this->getSeoTemplates();

                try {
                foreach ($rows as $i => $row) {
                    if ($i === 1) continue;

                    $rowNum = $i;
                    $row = array_map(fn($v) => is_string($v) ? trim($v) : $v, $row);

                    $excelManId = $row['A'] ?? '';
                    $gasTitle = $row['B'] ?? '';
                    $productName = $row['C'] ?? '';
                    $measTypeId = $row['D'] ?? '';
                    $measTypeId = $measTypeId === '' || $measTypeId === null ? '1' : $measTypeId;
                    $rangeFrom = $row['F'] ?? '';
                    $rangeTo = $row['G'] ?? '';
                    $rangeUnit = $row['H'] ?? '';
                    $energyFrom = $row['P'] ?? '';
                    $energyTo = $row['Q'] ?? '';
                    $energyUnit = $row['R'] ?? '';

                    if (empty($productName)) {
                        $skipped++;
                        $log[] = "Строка {$rowNum}: пустое название товара";
                        continue;
                    }

                    if (Product::find()->where(['name' => $productName])->exists()) {
                        $existing = Product::find()->where(['name' => $productName])->one();
                        $this->copyImagesAndPdf($productName, $existing, $rowNum, $log);
                        $skipped++;
                        $log[] = "Строка {$rowNum}: товар '{$productName}' уже существует (картинки обновлены)";
                        continue;
                    }

                    $manId = (int)$excelManId;
                    $manufacture = Manufacture::findOne($manId);
                    if (!$manufacture) {
                        $skipped++;
                        $log[] = "Строка {$rowNum}: производитель с id={$manId} не найден";
                        continue;
                    }

                    $gazKey = mb_strtolower(trim((string)$gasTitle));
                    $gaz = $gazByTitle[$gazKey] ?? null;
                    if (!$gaz) {
                        $skipped++;
                        $log[] = "Строка {$rowNum}: газ '{$gasTitle}' не найден в БД";
                        continue;
                    }

                    $measType = MeasurementType::findOne((int)$measTypeId);
                    if (!$measType) {
                        $skipped++;
                        $log[] = "Строка {$rowNum}: тип измерения id={$measTypeId} не найден";
                        continue;
                    }

                    $product = new Product();
                    $product->manufacture_id = $manId;
                    $product->name = $productName;
                    $product->measurement_type_id = (int)$measTypeId;
                    $product->bias_voltage = '';
                    $product->formfactor = !empty($row['E']) ? (string)$row['E'] : null;
                    $product->first = !empty($row['I']) ? (int)$row['I'] : 0;
                    $product->analog = !empty($row['K']) ? (int)$row['K'] : 0;
                    $product->digital = !empty($row['M']) ? (int)$row['M'] : 0;
                    $product->sensitivity_first = !empty($row['J']) ? (string)$row['J'] : null;
                    $product->sensitivity_analog = !empty($row['L']) ? (string)$row['L'] : null;
                    $product->sensitivity_digital = !empty($row['N']) ? (string)$row['N'] : null;
                    $product->response_time = $row['O'] !== '' ? (float)$row['O'] : null;
                    $product->life_time = $row['U'] !== '' ? (int)$row['U'] : null;
                    $product->warranty_period = $row['V'] !== '' ? (int)$row['V'] : null;
                    $product->temperature_range_from = $row['S'] !== '' ? (int)$row['S'] : null;
                    $product->temperature_range_to = $row['T'] !== '' ? (int)$row['T'] : null;

                    $product->digital = 1;
                    $product->device_type = 'модуль';
                    if ($energyFrom !== '') {
                        $product->energy_consumption_digital_from = (float)str_replace(',', '.', (string)$energyFrom);
                        $product->energy_consumption_from = (float)str_replace(',', '.', (string)$energyFrom);
                    }
                    if ($energyTo !== '') {
                        $product->energy_consumption_digital_to = (float)str_replace(',', '.', (string)$energyTo);
                        $product->energy_consumption_to = (float)str_replace(',', '.', (string)$energyTo);
                    }
                    if ($energyUnit !== '') {
                        $product->energy_consumption_digital_unit = (string)$energyUnit;
                        $product->energy_consumption_unit = (string)$energyUnit;
                    }

                    if (!$product->save()) {
                        $skipped++;
                        $errors = implode('; ', $product->getFirstErrors());
                        $log[] = "Строка {$rowNum}: ошибка сохранения товара '{$productName}': {$errors}";
                        continue;
                    }

                    $product->saveGazs([$gaz->id]);
                    $product->saveMainbGaz($gaz->id);

                    if ($rangeFrom !== '' || $rangeTo !== '') {
                        $productRange = new ProductRange();
                        $productRange->product_id = $product->id;
                        $productRange->from = (float)$rangeFrom;
                        $productRange->to = (float)$rangeTo;
                        $productRange->unit = !empty($rangeUnit) ? (string)$rangeUnit : '';
                        $productRange->pos = 0;
                        if (!$productRange->save()) {
                            $log[] = "Строка {$rowNum}: ошибка сохранения диапазона: " . implode('; ', $productRange->getFirstErrors());
                        }
                    }

                    $deviceType = $product->device_type ?: 'модуль';
                    $replaces = [
                        '{product_name}' => $productName,
                        '{gasname}' => $gaz->title,
                        '{manufacturer}' => $manufacture->title,
                        '{type_ru}' => $deviceType,
                    ];

                    $seo = new Seo();
                    $seo->ref_id = $product->id;
                    $seo->type = Seo::TYPE_PRODUCT;
                    $seo->title = strtr($seoTemplates['title'], $replaces);
                    $seo->h1 = strtr($seoTemplates['h1'], $replaces);
                    $seo->breadcrumb_text = strtr($seoTemplates['крошка'], $replaces);
                    $seo->description = strtr($seoTemplates['desc'], $replaces);
                    $seo->opisanie = '';
                    $seo->opisanie_ai = '';
                    if (!$seo->save()) {
                        $log[] = "Строка {$rowNum}: ошибка сохранения SEO: " . implode('; ', $seo->getFirstErrors());
                    }

                    $this->copyImagesAndPdf($productName, $product, $rowNum, $log);

                    $imported++;
                }
                } catch (\Exception $e) {
                    $skipped++;
                    $log[] = 'Критическая ошибка при обработке строки: ' . $e->getMessage();
                }

                $msg = "Импорт завершен. Импортировано: {$imported}, пропущено: {$skipped}";
                if ($imported > 0) {
                    Yii::$app->getSession()->setFlash('success', $msg);
                } else {
                    Yii::$app->getSession()->setFlash('warning', $msg);
                }
                if (!empty($log)) {
                    Yii::$app->getSession()->setFlash('info', nl2br('Лог импорта:<br>' . Html::encode(implode("<br>", $log))));
                }
            } else {
                Yii::$app->getSession()->setFlash('error', 'Ошибка при загрузке файла');
            }
        }

        return $this->render('import-from-excel', ['model' => $model]);
    }

    private function getSeoTemplates(): array
    {
        return [
            'title' => '{product_name} {type_ru} газа {gasname} от производителя {manufacturer}',
            'h1' => '{product_name} {manufacturer} {type_ru} {gasname}',
            'крошка' => '{product_name} {manufacturer} {type_ru} {gasname}',
            'desc' => '{product_name} {type_ru} газа {gasname} от производителя {manufacturer} можно купить в компании Газсенсор в розницу и оптом в Москве.',
        ];
    }

    private function copyImagesAndPdf(string $productName, Product $product, int $rowNum, array &$log): void
    {
        $iiiDir = Yii::getAlias('@documentroot') . '/iii/' . $productName;
        if (!is_dir($iiiDir)) {
            return;
        }

        foreach (['jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF'] as $ext) {
            $files = glob($iiiDir . "/*.$ext");
            if ($files) {
                $destDir = Product::getUploadPictDir();
                FileHelper::createDirectory($destDir);
                $lowExt = strtolower($ext);
                $dest = $destDir . "/{$product->id}.$lowExt";
                if (@copy($files[0], $dest)) {
                    $product->img = $lowExt;
                    $product->save(false);
                } else {
                    $log[] = "Строка {$rowNum}: не удалось скопировать картинку для '{$productName}'";
                }
                break;
            }
        }

        $pdfFiles = array_merge(
            glob($iiiDir . '/*.pdf'),
            glob($iiiDir . '/*.PDF')
        );
        if ($pdfFiles) {
            $destDirPdf = Product::getUploadPdfDir();
            FileHelper::createDirectory($destDirPdf);
            $destPdf = $destDirPdf . "/{$product->id}.pdf";
            if (@copy($pdfFiles[0], $destPdf)) {
                $product->pdf = basename($pdfFiles[0]);
                $product->save(false);
            } else {
                $log[] = "Строка {$rowNum}: не удалось скопировать PDF для '{$productName}'";
            }
        }
    }
}
