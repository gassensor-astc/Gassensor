<?php
/**
 * generated 21-10-19 14:21:17
 *
 */

namespace common\models;

use common\helpers\Tools;
use common\models\base\ProductBase;
use common\models\query\ProductQuery;
use common\models\traits\CreatedUpdateAtText;
use common\models\traits\DynamicForm;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;


/**
 *
 * @property Seo $seo
 * @property ProductGaz $mainProductGaz
 * @property Gaz $mainGaz
 * @property Gaz $mainGaz2
 * @property Gaz $mainGaz3
 * @property Gaz $notMainGazes
 * @property string $pictUrl
 * @property string $pictPath
 * @property string $url
 */
class Product extends ProductBase
{
    use CreatedUpdateAtText;
    use DynamicForm;

    /**
     * @var UploadedFile
     */
    public $uploadPict;

    /**
     * @var UploadedFile
     */
    public $uploadPdf;

    /**
     * @var UploadedFile
     */
    public $uploadPdf2;

    /**
     * @var UploadedFile
     */
    public $uploadPdf3;

    public function getMainGazId()
    {
        return $this->mainGaz->id ?? null;
    }

    public function getMainGaz2Id()
    {
        return $this->mainGaz2->id ?? null;
    }

    public function getMainGaz3Id()
    {
        return $this->mainGaz3->id ?? null;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = ['uploadPict', 'file', 'extensions' => 'png, jpg, gif'];
        $rules[] = [['uploadPdf', 'uploadPdf2', 'uploadPdf3'], 'file', 'extensions' => 'pdf'];

        return $rules;
    }

    /**
     * {@inheritDoc}
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
            'sluggable' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'immutable' => true,
            ],
        ];

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::class, ['ref_id' => 'id'])
            ->andOnCondition(['type' => Seo::TYPE_PRODUCT]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainProductGaz()
    {
        return $this->hasOne(ProductGaz::class, ['product_id' => 'id'])->andOnCondition(['is_main' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainProductGaz2()
    {
        return $this->hasOne(ProductGaz::class, ['product_id' => 'id'])->andOnCondition(['is_main_2' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainProductGaz3()
    {
        return $this->hasOne(ProductGaz::class, ['product_id' => 'id'])->andOnCondition(['is_main_3' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainGaz()
    {
        return $this->hasOne(Gaz::class, ['id' => 'gaz_id'])->via('mainProductGaz');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainGaz2()
    {
        return $this->hasOne(Gaz::class, ['id' => 'gaz_id'])->via('mainProductGaz2');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainGaz3()
    {
        return $this->hasOne(Gaz::class, ['id' => 'gaz_id'])->via('mainProductGaz3');
    }

    /**
     * @return Gaz[]
     */
    public function getNotMainGazes()
    {
        return Gaz::find()
            ->innerJoin('product_gaz', 'product_gaz.gaz_id=gaz.id')
            ->andWhere(['product_gaz.product_id' => $this->id])
            ->andWhere(['is_main' => 0])
            ->andWhere(['is_main_2' => 0])
            ->andWhere(['is_main_3' => 0])
            ->all();
    }


    /**
     * @param $colname
     * @param false $isPrependEmpty
     * @return array|false
     * @throws \yii\db\Exception
     */
    public static function getDropDownDataGroupCol($colname, $isPrependEmpty = false)
    {
        $q = self::find()->select($colname)->groupBy($colname);
        //->having(['not', [$colname => null]]);
        $rows = $q->createCommand()->queryColumn();

        $rows = array_combine($rows, $rows);

        if ($isPrependEmpty) {
            $rows = Tools::array_unshift_assoc($rows);
        }

        return $rows;
    }

    /**
     * @return false|string
     */
    public static function getUploadPictDir()
    {
        return \Yii::getAlias('@documentroot' . self::getUploadPictBaseUrl());
    }

    /**
     * @return string
     */
    public static function getUploadPictBaseUrl()
    {
        return '/i/products';
    }

    /**
     * @param bool $addTimestamp
     * @return string|void
     */
    public function getPictUrl($addTimestamp = true)
    {
        if (!$this->img) {
            return;
        }

        $url = self::getUploadPictBaseUrl() . "/{$this->id}.{$this->img}";

        if ($addTimestamp) {
            $url .= '?t=' . (int)$this->updated_at;
        }

        return $url;
    }

    /**
     * @return false|string
     */
    public function getPictPath()
    {
        return \Yii::getAlias('@documentroot' . $this->getPictUrl(false));
    }

    /**
     * @param string $i
     * @return string
     */
    public static function getPdfAttr($i = '')
    {
        return 'pdf' . ($i ? (int)$i : '');
    }

    /**
     * @return array
     */
    public static function getPdfIndexes()
    {
        return ['', 2, 3,];
    }

    /**
     * @param string $i
     * @param bool $addTimestamp
     * @return string|void
     */
    public function getPdfUrl($i = '', $addTimestamp = true)
    {
        $attr = self::getPdfAttr($i);
        if (!$this->$attr) {
            return;
        }

        $url = self::getUploadPdfBaseUrl($i) . "/{$this->id}.pdf";

        if ($addTimestamp) {
            $url .= '?t=' . (int)$this->updated_at;
        }

        return $url;
    }

    /**
     * @param string $i
     * @return false|string
     */
    public function getPdfPath($i = '')
    {
        return \Yii::getAlias('@documentroot' . $this->getPdfUrl($i, false));
    }

    /**
     * @param string $i
     * @return bool
     */
    public function isFilePdfExists($i = '')
    {
        return is_file($this->getPdfPath($i));
    }

    public static function getUploadPdfDir($i = '')
    {
        return \Yii::getAlias('@documentroot' . self::getUploadPdfBaseUrl($i));
    }

    public static function getUploadPdfBaseUrl($i = '')
    {
        return '/pdf/products' . ($i ? (int)$i : '');
    }

    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

    /**
     * @return bool
     */
    public function uploadPict()
    {
        $ext = strtolower($this->uploadPict->extension);
        $this->img = $ext;

        $filename = $this->getPictPath();

        if ($result = $this->uploadPict->saveAs($filename)) {
            $this->save(false);//save attr img
        }

        return $result;
    }

    /**
     * @param string $i
     * @return mixed
     */
    public function uploadPdf($i = '')
    {
        $i = $i ? (int)$i : '';
        $attr = 'pdf' . $i;
        $attrUpload = 'uploadPdf' . $i;

        $this->$attr = $this->$attrUpload->name;

        $filename = $this->getPdfPath($i);

        if ($result = $this->$attrUpload->saveAs($filename)) {
            $this->save(false);//save attr pdf
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if ($this->gaz->slug ?? null) {
            return "/catalog/{$this->gaz->slug}/{$this->slug}";
        }

        return "/product/{$this->slug}";
    }

    /**
     * @param false $backend
     * @param bool $visible
     * @return array
     */
    public static function getGazesGridCol($backend = false, $visible = true)
    {
        return [
            'attribute' => 'gaz_title',
            'label' => 'Дополнительный газ',
            'visible' => $visible,
            'format' => 'raw',
            'value' => function ($model) use ($backend) {
                $gazs = $model->notMainGazes;
                $items = ArrayHelper::getColumn($gazs, function ($model) use ($backend) {
                    $label = $model->title;
                    if ($backend) {
                        $label .= " (#{$model->id})";
                        return Html::a($label, ['gaz/view', 'id' => $model->id],
                            ['class' => 'btn btn-sm btn-outline-primary m-1',
                                'target' => '_blank', 'data-pjax' => 0,]);
                    }

                    return Html::tag('div', $label, ['class' => 'fs-07']);
                    //return Html::a($label, "/catalog/{$model->slug}", ['class' => 'px-1']);
                });

                sort($items);
                return join("\n", $items);
            }
        ];

    }

    public static function getColumnNameField()
    {
        return [
            'attribute' => 'name',
            'label' => 'Название',
            'headerOptions' => ['class' => 'table-top'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'format' => 'raw',
            'value' => function ($model) {
                //$label = $model->name;

                $url = "/product/{$model->slug}";

                $text = '<a href="' . $url . '" target="_blank" data-pjax="0" title="'.$model->name.'">'.$model->name.'</a>';
                $text .= '<div style="height: 20px;"></div>';
                $text .= '<a href="/backend/product/update?id='.$model->id.'" title="Редактировать" aria-label="Редактировать" target="_blank" data-pjax="0"><svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"></path></svg></a>';
                $text .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/backend/product/delete?id='.$model->id.'" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg></a>';
                return $text;
            }
        ];
    }

    /**
     * @param false $backend
     * @return array
     */
    public static function getManufactureTitleGridCol(bool $backend = false)
    {
        return [
            'attribute' => 'manufacture_title',
            'label' => 'Производитель',
            'headerOptions' => ['class' => 'table-top'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'format' => 'raw',
            'value' => function ($model) use ($backend) {
                $label = $model->manufacture->title ?? null;
                $url = "/catalog/manufacture/{$model->manufacture->slug}";

                /*
                if ($backend) {
                    $url = ['manufacture/view', 'id' => $model->manufacture_id];
                } else {
                    $url = "/manufacture/{$model->manufacture->slug}";
                }
                */

                if (isset($_GET['ProductSearch']['manufacture_id']) && $_GET['ProductSearch']['manufacture_id']) {
                    $text = '<p>' . $label;
                    $text .= '<a target="_blank" href="/manufacture/' . $model->manufacture->slug . '">
                            ' . Html::img('/i/new-window.gif', ['loading' => "lazy"]) . '
                        </a></p>';

                    return $text;
                } elseif (isset($_GET['slug']) && $_GET['slug'] && !isset($_GET['filter'])) {
                    $text = '<p>' . $label;
                    $text .= '<a target="_blank"  href="/manufacture/' . $model->manufacture->slug . '">
                            ' . Html::img('/i/new-window.gif', ['loading' => "lazy"]) . '
                        </a></p>';

                    return $text;
                } else {
                    return Html::a($label, $url, ['data-pjax' => 0]);
                }
            }
        ];
    }

    /**
     * @return array
     */
    public static function getMeasurementTypeNameGridCol()
    {
        return [
            'attribute' => 'measurement_type_name',
            'label' => 'Тип измерения',
            'headerOptions' => ['class' => 'table-top'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'format' => 'raw',
            'value' => function ($model) {
                return $model->measurementType->name ?? null;
            }
        ];
    }

    /**
     * @param array $ids
     * @return void
     * @throws \Exception
     */
    public function saveGazs(array $ids)
    {
        ProductGaz::deleteAll(['product_id' => $this->id]);

        foreach ($ids as $id) {
            $cond = ['product_id' => $this->id, 'gaz_id' => $id];

            $model = new ProductGaz($cond);
            $model->is_main = 0;

            if (!$model->save()) {
                throw new \Exception('fail saving ProductGaz');
            }
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function saveMainbGaz(int $id)
    {
        if (!$id) {
            return;
        }

        $cond = ['product_id' => $this->id, 'gaz_id' => $id];
        $model = ProductGaz::findOne($cond);

        if (!$model) {
            $model = new ProductGaz($cond);
        }

        if ($model->is_main) {
            return; //not changed
        }

        //todo check exists gaz?

        $model->is_main = 1;

        if (!$model->save()) {
            throw new \Exception('fail saving ProductGaz');
        }
    }

    /**
     * @param int|null $id
     * @return void
     * @throws \Exception
     */
    public function saveMainbGaz2(?int $id = null)
    {
        if (!$id) {
            return;
        }

        $cond = ['product_id' => $this->id, 'gaz_id' => $id];
        $model = ProductGaz::findOne($cond);

        if (!$model) {
            $model = new ProductGaz($cond);
        }

        if ($model->is_main_2) {
            return; //not changed
        }

        $model->is_main = 0;
        $model->is_main_2 = 1;
        $model->is_main_3 = 0;

        if (!$model->save()) {
            throw new \Exception('fail saving ProductGaz');
        }
    }


    /**
     * @param int $pos
     * @return \yii\db\ActiveQuery
     */
    public function ProductRangesByPos(int $pos = 0)
    {
        return ProductRange::find()->where(['pos' => $pos, 'product_id' => $this->id])->all();


        //  return $this->hasMany(ProductRange::class, ['product_id' => 'id'])->where(['pos' => $pos]);
    }


    /**
     * @param int|null $id
     * @return void
     * @throws \Exception
     */
    public function saveMainbGaz3(?int $id = null)
    {
        if (!$id) {
            return;
        }

        $cond = ['product_id' => $this->id, 'gaz_id' => $id];
        $model = ProductGaz::findOne($cond);

        if (!$model) {
            $model = new ProductGaz($cond);
        }

        if ($model->is_main_3) {
            return; //not changed
        }

        $model->is_main = 0;
        $model->is_main_2 = 0;
        $model->is_main_3 = 1;

        if (!$model->save()) {
            throw new \Exception('fail saving ProductGaz');
        }
    }

    /**
     * @return false|string
     */
    public function getJsonLd()
    {
        $images = [];
        if ($this->pictUrl) {
            $images[] = 'https://gassensor.ru' . $this->getPictUrl(false);
        }

        $data = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $this->name,
            'image' => $images,
            'description' => $this->name,
            'sku' => $this->id,
            'mpn' => $this->id,
            'brand' => [
                '@type' => 'Brand',
                'name' => $this->manufacture->title ?? null,
            ],
            'review' => [
                '@type' => 'Review',
                'reviewRating' => [
                    '@type' => 'Rating',
                    'ratingValue' => 4.99,
                    'bestRating' => 5,
                ],
                'author' => [
                    '@type' => 'Person',
                    'name' => 'anonymous',
                ],

            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => 4.99,
                'reviewCount' => 1000,
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => 0.01,
                'availability' => 'https://schema.org/InStock',
                'url' => 'https://gassensor.ru/product/' . $this->slug,
                'priceValidUntil' => date('Y-m-d', time() + 3600 * 24 * 365),
                'priceCurrency' => 'RUR',
            ],
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @return Response
     * @throws \yii\db\Exception
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public static function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getStyle("A:E")
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        $cols = ['name', 'measurement_type_id', "CONCAT(range_from, '-', range_to, ' ', range_unit) AS range"];

        $q = self::find()->select($cols);
        $rows = $q->createCommand()->queryAll();

        if ($rows) {
            array_unshift($rows, array_keys($q->select));

            $i = 1;

            $dimensions = $sheet->getColumnDimensions();

            foreach ($dimensions as $v) {
                $v->setWidth(40);
                if ($i++ >= count($rows[0])) {
                    break;
                }
            }

            $dimensions['A']->setWidth(30);
            $dimensions['B']->setWidth(20);
            $dimensions['C']->setWidth(30);
        }

        $sheet->fromArray($rows);

        /* @var $response \yii\web\Response */
        $response = Yii::$app->response;

        $tmpResource = tmpfile();
        if ($tmpResource === false) {
            throw new \RuntimeException('Unable to create temporary file.');
        }

        $tmpResourceMetaData = stream_get_meta_data($tmpResource);
        $tmpFileName = $tmpResourceMetaData['uri'];

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tmpFileName);
        unset($writer);

        $attachmentName = date('Ymd-His') . '.xlsx';
        $options = [];

        $tmpFileStatistics = fstat($tmpResource);
        if ($tmpFileStatistics['size'] > 0) {
            return $response->sendStreamAsFile($tmpResource, $attachmentName, $options);
        }

        $response->on(Response::EVENT_AFTER_SEND, function () use ($tmpResource) {
            // with temporary file resource closing file matching its URI will be deleted, even if resource is invalid
            fclose($tmpResource);
        });

        return $response->sendFile($tmpFileName, $attachmentName, $options);
    }

}