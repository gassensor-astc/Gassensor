<?php
/**
 * generated 21-11-04 13:57:04
 *
 */

namespace common\models;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use common\components\ClassConstNameTrait;
use common\helpers\Tools;
use common\models\base\OrderBase;
use common\models\query\OrderQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\Response;

/**
 *
 * @property string $statusName
 *
 */
class Order extends OrderBase
{
    use ClassConstNameTrait;

    const STATUS_NEW = 10;

    const STATUS_INPROGRESS = 20;

    const STATUS_COMPLETE = 100;

    public function rules()
    {
        $rules = parent::rules();

        $rules[] = ['email', 'email'];
        $rules[] = ['phone', 'filter', 'filter' => function($val) {
            return str_replace(['(', ' ', '-', ')'], '', $val);
        }];
        $rules[] = ['phone', 'match', 'pattern' => '%^\+\d{11}%'];

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
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @throws \Exception
     */
    public function addProductsFromCart()
    {
        if (!$this->id) {
            return;
        }
        /* @var \common\components\cart\Cart $cart */
        $cart = Yii::$app->cart;

        foreach ($cart->getItems() as $item) {
            $product = $item->product;

            $model = new OrderProduct();
            $model->order_id = $this->id;
            $model->product_id = $product->id;
            $model->product_info = $product->name;
            $model->count = $item->count;
            $model->price = $product->price ?: 0;

            if (!$model->save()) {
                throw new \Exception('fail add product to order');
            }

            $cart->removeItem($model->product_id);
        }
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        /* @var \common\components\cart\Cart $cart */
        $cart = Yii::$app->cart;

        $items = [];

        foreach ($cart->getItems() as $item) {
            $items[] = ['name' => $item->product->name, 'count' => $item->count];
        }

        return $items;
    }

    /**
     * @param false $isPrependEmpty
     * @return array
     */
    public static function getStatusDropDownData($isPrependEmpty = false)
    {
        $items = self::getClassConstNames('STATUS_', 'order');

        if ($isPrependEmpty) {
            $items = Tools::array_unshift_assoc($items);
        }

        return $items;
    }

    /**
     * @return mixed
     */
    public function getStatusName()
    {
        return self::getStatusDropDownData()[$this->status];
    }

    /**
     * @inheritdoc
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }

    /**
     * @return bool
     */
    public function sendMailToManager()
    {
        $to = Setting::getEmailManagerOrder();

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'orderCreated-html', 'text' => 'orderCreated-text'],
                ['model' => $this]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'Гассенсор'])
            ->setTo(explode(',', $to))
            ->setSubject("Новый заказ #{$this->id}")
            ->send();
    }

    /**
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public static function exportExcel()
    {
        /*
         Создать возможность выгрузки базы данных, заполняемых форм пользователями при оформлении заказа (заявки) на покупку сенсоров, датчиков в формате Excel.
        форма таблици
        Дата заказа  Имя  E-mail  Номер телефона
         */
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //force text format
        $sheet->getStyle("A:E")
            ->getNumberFormat()
            ->setFormatCode( \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );

        $cols = ['id', 'Дата' => 'FROM_UNIXTIME(created_at)', 'name', 'email', 'phone'];

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

            $dimensions['A']->setWidth(20);

        }

        $sheet->fromArray($rows);

        /* @var $response \yii\web\Response */
        $response = Yii::$app->response;

        //https://github.com/yii2tech/spreadsheet/blob/master/src/Spreadsheet.php#L710

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

        $response->on(Response::EVENT_AFTER_SEND, function() use ($tmpResource) {
            // with temporary file resource closing file matching its URI will be deleted, even if resource is invalid
            fclose($tmpResource);
        });

        return $response->sendFile($tmpFileName, $attachmentName, $options);
    }
}