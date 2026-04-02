<?php
/**
 * generated 2022-02-13 15:56:00
 */

namespace common\models\base;

use Yii;

use common\models\Gaz;
use common\models\Manufacture;
use common\models\MeasurementType;
use common\models\OrderProduct;
use common\models\Order;
use common\models\ProductGaz;
use common\models\ProductRange;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $manufacture_id
 * @property string $name
 * @property string $img
 * @property string $pdf
 * @property string $pdf2
 * @property string $pdf3
 * @property string $price
 * @property string $slug
 * @property integer $measurement_type_id
 * @property string $formfactor
 * @property string $formfactor_unit
 * @property double $range_from
 * @property double $range_to
 * @property string $range_unit
 * @property double $resolution
 * @property string $sensitivity_first
 * @property string $sensitivity_analog
 * @property string $sensitivity_digital
 * @property double $sensitivity_from
 * @property double $sensitivity_to
 * @property string $sensitivity_unit
 * @property integer $response_time
 * @property string $response_time_unit
 * @property integer $life_time
 * @property integer $warranty_period
 * @property double $energy_consumption_from
 * @property double $energy_consumption_to
 * @property string $energy_consumption_unit
 * @property integer $temperature_range_from
 * @property integer $temperature_range_to
 * @property string $info
 * @property string $bias_voltage
 * @property bool $first
 * @property bool $analog
 * @property bool $digital
 *
 * @property Gaz[] $gazs
 * @property Manufacture $manufacture
 * @property MeasurementType $measurementType
 * @property OrderProduct[] $orderProducts
 * @property Order[] $orders
 * @property ProductGaz[] $productGazs
 * @property ProductRange[] $productRanges
 */
class ProductBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'manufacture_id', 'measurement_type_id', 'temperature_range_from', 'temperature_range_to'], 'integer'],
            [['manufacture_id', 'name', 'measurement_type_id'], 'required'],
            [['price', 'range_from', 'range_to', 'resolution', 'sensitivity_from', 'sensitivity_to', 'energy_consumption_from', 'energy_consumption_to', 'response_time', 'life_time', 'warranty_period'], 'number'],
            [['formfactor', 'range_unit'], 'string', 'max' => 30],
            [['sensitivity'], 'string',],
            [['name'], 'string', 'max' => 100],
            [['img'], 'string', 'max' => 3],
            [['pdf', 'pdf2', 'pdf3', 'slug'], 'string', 'max' => 100],
            [['info'], 'string', 'max' => 512],
            [['bias_voltage'], 'string', 'max' => 255],
            [['sensitivity_first','sensitivity_analog','sensitivity_digital'], 'string', 'max' => 25],
            [['formfactor_unit', 'sensitivity_unit'], 'string', 'max' => 20],
            [['response_time_unit', 'energy_consumption_unit'], 'string', 'max' => 10],
            [['slug'], 'unique'],
            [['first', 'analog', 'digital'], 'boolean'],
            [['manufacture_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManufactureBase::class, 'targetAttribute' => ['manufacture_id' => 'id']],
            [['measurement_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MeasurementTypeBase::class, 'targetAttribute' => ['measurement_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'manufacture_id' => Yii::t('app', 'Manufacture ID'),
            'name' => Yii::t('app', 'Name'),
            'img' => Yii::t('app', 'Img'),
            'pdf' => Yii::t('app', 'Pdf'),
            'pdf2' => Yii::t('app', 'Pdf 2'),
            'pdf3' => Yii::t('app', 'Pdf 3'),
            'price' => Yii::t('app', 'Price'),
            'slug' => Yii::t('app', 'Slug'),
            'measurement_type_id' => Yii::t('app', 'Measurement Type ID'),
            'formfactor' => Yii::t('app', 'Formfactor'),
            'formfactor_unit' => Yii::t('app', 'Formfactor Unit'),
            'range_from' => Yii::t('app', 'Range From'),
            'range_to' => Yii::t('app', 'Range To'),
            'range_unit' => Yii::t('app', 'Range Unit'),
            'resolution' => Yii::t('app', 'Resolution'),
            'sensitivity' => Yii::t('app', 'Sensitivity'),
            'first' => Yii::t('app', 'First'),
            'analog' => Yii::t('app', 'Analog'),
            'digital' => Yii::t('app', 'Digital'),
            'sensitivity_first' => Yii::t('app', 'Sensitivity First'),
            'sensitivity_analog' => Yii::t('app', 'Sensitivity Analog'),
            'sensitivity_digital' => Yii::t('app', 'Sensitivity Digital'),
            'sensitivity_from' => Yii::t('app', 'Sensitivity From'),
            'sensitivity_to' => Yii::t('app', 'Sensitivity To'),
            'sensitivity_unit' => Yii::t('app', 'Sensitivity Unit'),
            'response_time' => Yii::t('app', 'Response Time'),
            'response_time_unit' => Yii::t('app', 'Response Time Unit'),
            'life_time' => Yii::t('app', 'Life Time'),
            'warranty_period' => Yii::t('app', 'Warranty Period'),
            'energy_consumption_from' => Yii::t('app', 'Energy Consumption From'),
            'energy_consumption_to' => Yii::t('app', 'Energy Consumption To'),
            'energy_consumption_unit' => Yii::t('app', 'Energy Consumption Unit'),
            'temperature_range_from' => Yii::t('app', 'Temperature Range From'),
            'temperature_range_to' => Yii::t('app', 'Temperature Range To'),
            'info' => Yii::t('app', 'Info'),
            'bias_voltage' => Yii::t('app', 'Bias Voltage'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGazs()
    {
        return $this->hasMany(Gaz::class, ['id' => 'gaz_id'])->viaTable('product_gaz', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacture()
    {
        return $this->hasOne(Manufacture::class, ['id' => 'manufacture_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeasurementType()
    {
        return $this->hasOne(MeasurementType::class, ['id' => 'measurement_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::class, ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['id' => 'order_id'])->viaTable('order_product', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductGazs()
    {
        return $this->hasMany(ProductGaz::class, ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductRanges()
    {
        return $this->hasMany(ProductRange::class, ['product_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ProductBaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductBaseQuery(get_called_class());
    }
}
