<?php
/**
 * generated 21-10-19 14:22:24
 *
 */

namespace common\models;

use common\helpers\Tools;
use common\models\base\MeasurementTypeBase;
use common\models\query\MeasurementTypeQuery;
use common\models\search\ProductSearch;
use yii\helpers\ArrayHelper;
use Yii;

class MeasurementType extends MeasurementTypeBase
{

    /**
     * @param false $isPrependEmpty
     * @return array
     */
    public static function getDropDownData(bool $isPrependEmpty = false): array
    {
        $rows = self::find()->orderBy('name')->cache(10)->all();
        $rows = ArrayHelper::map($rows, 'id', 'name');

        if ($isPrependEmpty) {
            $rows = Tools::array_unshift_assoc($rows);
        }

        $newArray = [];
        foreach ($rows as $key => $value) {
            $newArray[$key] = $value ? mb_ucfirst($value) : null;
        }

        return $newArray;
    }


    /**
     * @inheritdoc
     * @return MeasurementTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MeasurementTypeQuery(get_called_class());
    }

    /**
     * @param ProductSearch $searchModel
     * @return array
     */
    public static function measurementTypeOption(ProductSearch $searchModel): array
    {
        $rows = self::find()->orderBy('name')->asArray()->all();

        $q = self::find()->select(['measurement_type.id']);
        $q->leftJoin('product', 'product.measurement_type_id = measurement_type.id');

        if ($searchModel->gaz_id) {
            $q->leftJoin('product_gaz', 'product_gaz.product_id = product.id');
            $q->andWhere(['product_gaz.gaz_id' => $searchModel->gaz_id]);
        }

        if ($searchModel->manufacture_id) {
            $q->andWhere(['product.manufacture_id' => $searchModel->manufacture_id]);
        }

        $options = ['' => ['label' => ' ']];

        foreach ($rows as $row) {
            if (in_array($row['id'], $q->column()) === false) $options[$row['id']] = ['disabled' => true];
        }

        return $options;
    }

    public static function findAvailableMeasurementTypeIds(ProductSearch $searchModel): array
    {
        return self::findAvailableMeasurementTypeIdsByParams([
            'ProductSearch' => array_filter(ArrayHelper::merge(
                Yii::$app->request->queryParams['ProductSearch'] ?? [],
                $searchModel->getAttributes(),
                [
                    'gaz_id' => $searchModel->gaz_id,
                    'gaz_group_id' => $searchModel->gaz_group_id,
                    'selectedSignalTypes' => $searchModel->selectedSignalTypes,
                    'response_time_from' => $searchModel->response_time_from,
                    'response_time_to' => $searchModel->response_time_to,
                    'life_time_to' => $searchModel->life_time_to,
                    'resolution_from' => $searchModel->resolution_from,
                    'resolution_to' => $searchModel->resolution_to,
                ]
            ), static function ($value) {
                return $value !== null && $value !== '' && $value !== [];
            }),
        ]);
    }

    public static function findAvailableMeasurementTypeIdsByParams(array $params): array
    {
        unset($params['ProductSearch']['measurement_type_id']);

        $ids = (new ProductSearch())->searchFront($params, false)->query->select(['product.id'])->column();

        if (!$ids) {
            return [];
        }

        return self::find()
            ->select(['measurement_type.id'])
            ->leftJoin('product', 'product.measurement_type_id = measurement_type.id')
            ->where(['in', 'product.id', $ids])
            ->groupBy(['measurement_type.id'])
            ->column();
    }

    public static function findAvailableMeasurementTypeAjaxIds(): array
    {
        return self::findAvailableMeasurementTypeIdsByParams(Yii::$app->request->queryParams);
    }

    /**
     * @param ProductSearch $searchModel
     * @return array
     */
    public static function measurementTypeOption2(ProductSearch $searchModel): array
    {
        $measurementTypeIds = self::findAvailableMeasurementTypeIds($searchModel);
        $measurementTypeOption = ['' => ['label' => 'Тип измерения']];

        foreach (self::getDropDownData(true) as $id => $label) {
            if (!empty($id) && !in_array($id, $measurementTypeIds)) {
                $measurementTypeOption[$id] = ['disabled' => true];
            }
        }

        return $measurementTypeOption;
    }
}
