<?php
/**
 * generated 21-10-19 11:57:47
 *
 */

namespace common\models;

use common\helpers\Tools;
use common\models\base\GazBase;
use common\models\query\GazQuery;
use common\models\search\ProductSearch;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * @property Seo $seo
 */
class Gaz extends GazBase
{

    /**
     * {@inheritDoc}
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            'sluggable' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
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
            ->andOnCondition(['type' => Seo::TYPE_CATALOG_GAZ]);
    }

    /**
     * @param bool $isPrependEmpty
     * @return array
     */
    public static function getDropDownData(bool $isPrependEmpty = false)
    {
        $rows = self::find()->orderBy('title')->cache(10)->all();
        $rows = ArrayHelper::map($rows, 'id', 'title');

        if ($isPrependEmpty) {
            $rows = Tools::array_unshift_assoc($rows);
        }

        return $rows;
    }

    /**
     * @inheritdoc
     * @return GazQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GazQuery(get_called_class());
    }

    /**
     * @param float $value
     * @return array
     */
    public function covertFromMg(float $value): array
    {
        $result = [];
        $result['mg'] = $value;
        $result['ppm'] = (8312.6 * $value * 293.15) / ($this->weight * 101325);
        $result['obd'] = $result['ppm'] / 10000;
        $result['nkpr'] = ($result['obd'] / 4) * 100;

        return $result;
    }

    /**
     * @param float $value
     * @return array
     */
    public function covertFromPpm(float $value): array
    {
        $result = [];
        $result['mg'] = 0.12 * ($value / 1000) * $this->weight * 101325 / 293.15;
        $result['ppm'] = $value;
        $result['obd'] = $value / 10000;
        $result['nkpr'] = ($result['obd'] / 4) * 100;

        return $result;
    }

    /**
     * @param float $value
     * @return array
     */
    public function covertFromObd(float $value): array
    {
        $result = [];
        $result['mg'] = 0.12 * ($value / 0.1) * $this->weight * 101325 / 293.15;
        $result['ppm'] = $value * 10000;
        $result['obd'] = $value;
        $result['nkpr'] = ($value / 4) * 100;

        return $result;
    }

    /**
     * @param float $value
     * @return array
     */
    public function covertFromNkpr(float $value): array
    {
        $result = [];
        $result['mg'] = 0;//0.12 * ($value / 0.1) * $gazWeight * 101325 / 293.15;
        $result['ppm'] = 0;//$value * 10000;
        $result['pbd'] = 0;//($value / 4) * 100;

        return $result;
    }

    /**
     * @param ProductSearch $searchModel
     * @return array[]
     */
    public static function gazOption(ProductSearch $searchModel): array
    {
        $rows = self::find()->orderBy('title')->asArray()->all();

        $q = self::find()->select(['gaz.id']);
        $q->leftJoin('product_gaz', 'product_gaz.gaz_id = gaz.id');
        $q->leftJoin('product', 'product.id = product_gaz.product_id');

        if ($searchModel->manufacture_id) {
            $q->andWhere(['product.manufacture_id' => $searchModel->manufacture_id]);
        }

        $options = ['' => ['label' => ' ']];

        foreach ($rows as $row) {
            if (in_array($row['id'], $q->column()) === false) $options[$row['id']] = ['disabled' => true];
        }

        return $options;
    }

    public static function findAvailableGazIds(ProductSearch $searchModel): ?array
    {
        $params = Yii::$app->request->queryParams;

        if ($searchModel->manufacture_id) {
            unset($params['ProductSearch']['gaz_id']);

            $ids = (new ProductSearch())->searchFront($params)->query->select(['product.id'])->column();

            if ($ids) {
                $gazAvailableIds = self::find()
                    ->select(['gaz.id'])
                    ->leftJoin('product_gaz','product_gaz.gaz_id = gaz.id')
                    ->leftJoin('product','product.id = product_gaz.product_id')
                    ->where(['in', 'product.id', $ids])
                    ->groupBy(['gaz.id'])
                    ->column();
            } else {
                $gazAvailableIds = null;
            }
        } else {
            $gazAvailableIds = self::find()->select(['id'])->column();
        }

        return $gazAvailableIds;
    }

    /**
     * @param ProductSearch $searchModel
     * @return array[]
     */
    public static function gazOption2(ProductSearch $searchModel): array
    {
        $gazAvailableIds = self::findAvailableGazIds($searchModel);
        $gazOption = ['' => ['label' => 'Газ']];

        if ($gazAvailableIds) {
            foreach (self::getDropDownData(true) as $id => $label) {
                if (!in_array($id, $gazAvailableIds) && !empty($id)) {
                    $gazOption[$id] = ['disabled' => true];
                }
            }
        }

        return $gazOption;
    }

}
