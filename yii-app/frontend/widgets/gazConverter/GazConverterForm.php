<?php
/**
 *
 * @since 2021-11-15 11:38
 */
namespace frontend\widgets\gazConverter;

use common\models\Gaz;
use yii\base\Model;

class GazConverterForm extends Model
{
    const CONVERT_FROM_PPM = 'covertFromPpm';
    const CONVERT_FROM_MG = 'covertFromMg';
    const CONVERT_FROM_OBD = 'covertFromObd';

    public $gazId;

    public $corvertType;

    public $ppm;

    public $mg;

    public $obd;

    public function rules()
    {
        return [
            [['gazId', 'corvertType',], 'required'],
            ['gazId', 'integer'],
            [['gazId'], 'exist', 'skipOnError' => true, 'targetClass' => Gaz::class, 'targetAttribute' => ['gazId' => 'id']],
            ['corvertType', 'in', 'range' => array_keys($this->getTypes())],
            [['ppm', 'mg', 'obd'], 'filter', 'filter' => [$this, 'normalizeDecimalValue']],
            [['ppm', 'mg', 'obd'], 'number'],
            [['ppm', 'mg', 'obd',], 'default', 'value' => 0,],
        ];
    }

    public function normalizeDecimalValue($value)
    {
        if ($value === null) {
            return 0;
        }

        $value = trim((string)$value);
        if ($value === '') {
            return 0;
        }

        return str_replace(',', '.', $value);
    }

    public function getGaz()
    {
        return Gaz::findOne($this->gazId);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gazId' => 'Выберите вещество',
            'ppm' => $this->types[self::CONVERT_FROM_PPM],
            'mg' => $this->types[self::CONVERT_FROM_MG],
            'obd' => $this->types[self::CONVERT_FROM_OBD],
        ];
    }

    public static function getTypes()
    {
        return [
            self::CONVERT_FROM_PPM => 'ppm',
            self::CONVERT_FROM_MG => 'мг/м3',
            self::CONVERT_FROM_OBD => '% об. д.',
        ];
    }

    public function convert()
    {
        if (!$gaz = $this->getGaz()) {
            return;
        }

        switch ($this->corvertType) {
            case self::CONVERT_FROM_PPM:
                $result = $gaz->covertFromPpm($this->ppm);
                break;
            case self::CONVERT_FROM_MG:
                $result = $gaz->covertFromMg($this->mg);
                break;
            case self::CONVERT_FROM_OBD:
                $result = $gaz->covertFromObd($this->obd);
                break;
        }

        $this->ppm = $result['ppm'];
        $this->mg = $result['mg'];
        $this->obd = $result['obd'];
    }

}
